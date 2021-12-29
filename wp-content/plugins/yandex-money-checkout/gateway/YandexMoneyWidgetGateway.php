<?php


use YandexCheckout\Common\Exceptions\ApiException;
use YandexCheckout\Model\ConfirmationType;
use YandexCheckout\Model\MonetaryAmount;
use YandexCheckout\Model\PaymentMethodType;
use YandexCheckout\Model\PaymentStatus;
use YandexCheckout\Request\Payments\CreatePaymentRequest;
use YandexCheckout\Request\Payments\CreatePaymentRequestSerializer;

class YandexMoneyWidgetGateway extends YandexMoneyCheckoutGateway
{
    public $paymentMethod = PaymentMethodType::BANK_CARD;

    public $id = 'ym_api_widget';

    public function __construct()
    {
        parent::__construct();

        $this->icon               = YandexMoneyCheckout::$pluginUrl . '/assets/images/ac_in.png';

        $this->method_title       = __('Платёжный виджет Кассы (карты, Apple Pay и Google Pay)', 'yandex-money-checkout');
        $this->method_description = __('Покупатель вводит платёжные данные прямо во время заказа, без редиректа на страницу Яндекс.Кассы. Опция работает для платежей с карт (в том числе, через Apple Pay и Google Pay).', 'yandex-money-checkout');

        $this->defaultTitle       = __('Банковские карты, Apple Pay, Google Pay', 'yandex-money-checkout');
        $this->defaultDescription = __('Оплата банковской картой на сайте', 'yandex-money-checkout');

        $this->title              = $this->getTitle();
        $this->description        = $this->getDescription();

        add_action('admin_notices', array($this, 'initial_notice'));

        if (!empty($_POST['action']) && $_POST['action'] === 'woocommerce_toggle_gateway_enabled'
            && !empty($_POST['gateway_id']) && $_POST['gateway_id'] === $this->id
        ) {
            //вызывается до переключение enable в yes
            if ($this->enabled === 'no') {
                $this->init_apple_pay();
            }
        } else if ($this->enabled === 'yes') {
            $this->init_apple_pay();
        }
    }

    public $confirmationType = ConfirmationType::EMBEDDED;

    /**
     * Receipt Page
     *
     * @param int $order_id
     *
     * @throws Exception
     */
    public function receipt_page($order_id)
    {
        global $woocommerce;
        $order     = new WC_Order($order_id);
        $paymentId = $order->get_transaction_id();

        $data = array(
            'error' => '',
            'token' => '',
            'return_url' => get_site_url(null, sprintf(self::RETURN_URI_PATTERN, $order->get_order_key())),
            'payment_url' => $order->get_checkout_payment_url(),
        );

        try {
            $payment = $this->getApiClient()->getPaymentInfo($paymentId);
            if ($confirmation = $payment->getConfirmation()) {
                if ($confirmation->getType() === ConfirmationType::REDIRECT) {
                    if ($redirectUrl = $confirmation->getConfirmationUrl()) {
                        $data['error'] = '<p>'.__('Что-то пошло не так!', 'yandex-money-checkout').'</p>'
                            . '<p><a href="'.$order->get_checkout_payment_url().'" target="_top" class="woocommerce-button button pay">'
                            .__('Попробовать заново', 'yandex-money-checkout').'</a></p>';
                    }
                } else {
                    $data['token'] = $payment->getConfirmation()->getConfirmationToken();
                }
            } else {
                if (in_array($payment->getStatus(), self::getValidPaidStatuses())
                    || ($payment->getStatus() === PaymentStatus::PENDING && $payment->getPaid())) {
                    $woocommerce->cart->empty_cart();
                    wp_redirect($this->get_success_fail_url('ym_api_success', $order));
                } else {
                    wp_redirect($this->get_success_fail_url('ym_api_fail', $order));
                }
            }

        } catch (ApiException $e) {
            $data['error'] = '<p>'.__('Что-то пошло не так!', 'yandex-money-checkout').'</p>'
                . '<p><a href="'.$order->get_checkout_payment_url().'" target="_top" class="woocommerce-button button pay">'
                .__('Попробовать заново', 'yandex-money-checkout').'</a></p>';
            YandexMoneyLogger::error('Api error: '.$e->getMessage());
        }

        $this->render('../includes/partials/widget.php', array(
            'data' => $data,
        ));
    }

    public function process_admin_options()
    {
        if ($this->enabled === 'yes') {
            $this->init_apple_pay();
        }
        return parent::process_admin_options();
    }

    /**
     * Process the payment and return the result
     *
     * @param $order_id
     *
     * @return array
     * @throws WC_Data_Exception
     * @throws Exception
     */
    public function process_payment($order_id)
    {
        global $woocommerce;

        $order = new WC_Order($order_id);

        $result     = $this->createPayment($order);
        $receiptUrl = $order->get_checkout_payment_url(true);

        if ($result) {
            $order->set_transaction_id($result->id);

            if ($result->status == PaymentStatus::PENDING) {
                $order->update_status('wc-pending');

                return array(
                    'result'   => 'success',
                    'redirect' => $receiptUrl,
                );
            } elseif ($result->status == PaymentStatus::WAITING_FOR_CAPTURE) {
                return array(
                    'result' => 'success',
                    'redirect' => $this->get_success_fail_url("ym_api_success", $order)
                );
            } elseif ($result->status == PaymentStatus::SUCCEEDED) {
                return array(
                    'result'   => 'success',
                    'redirect' => $this->get_success_fail_url('ym_api_success', $order),
                );
            } else {
                YandexMoneyLogger::warning(sprintf(__('Неудалось создать платеж. Для заказа %1$s',
                    'yandex-money-checkout'), $order_id));
                wc_add_notice(__('Платеж не прошел. Попробуйте еще или выберите другой способ оплаты',
                    'yandex-money-checkout'), 'error');
                $order->update_status('wc-cancelled');

                return array('result' => 'fail', 'redirect' => '');
            }
        } else {
            YandexMoneyLogger::warning(sprintf(__('Неудалось создать платеж. Для заказа %1$s', 'yandex-money-checkout'),
                $order_id));
            wc_add_notice(__('Платеж не прошел. Попробуйте еще или выберите другой способ оплаты', 'yandex-money-checkout'),
                'error');

            return array('result' => 'fail', 'redirect' => '');
        }
    }

    /**
     * @param WC_Order $order
     *
     * @return mixed|WP_Error|\YandexCheckout\Request\Payments\CreatePaymentResponse
     * @throws Exception
     */
    public function createPayment($order)
    {
        $builder        = $this->getBuilder($order);
        $paymentRequest = $builder->build();
        if (YandexMoneyCheckoutHandler::isReceiptEnabled()) {
            $receipt = $paymentRequest->getReceipt();
            if ($receipt instanceof \YandexCheckout\Model\Receipt) {
                $receipt->normalize($paymentRequest->getAmount());
            }
        }
        $serializer     = new CreatePaymentRequestSerializer();
        $serializedData = $serializer->serialize($paymentRequest);
        YandexMoneyLogger::info('Create payment request: '.json_encode($serializedData));
        try {
            $response = $this->getApiClient()->createPayment($paymentRequest);

            return $response;
        } catch (ApiException $e) {
            YandexMoneyLogger::error('Api error: '.$e->getMessage());

            return new WP_Error($e->getCode(), $e->getMessage());
        }
    }

    public function initial_notice() {
        if ($this->enabled === 'yes') {
            clearstatcache();
            if ($this->isVerifyApplePayFileExist()) {
                return;
            }
            echo '<div class="notice notice-warning is-dismissible"><p>' . __('Чтобы покупатели могли заплатить вам через Apple Pay, <a href="https://kassa.yandex.ru/docs/merchant.ru.yandex.kassa">скачайте файл apple-developer-merchantid-domain-association</a> и добавьте его в папку ./well-known на вашем сайте. Если не знаете, как это сделать, обратитесь к администратору сайта или в поддержку хостинга. Не забудьте также подключить оплату через Apple Pay <a href="https://kassa.yandex.ru/my/payment-methods/settings#applePay">в личном кабинете Кассы</a>. <a href="https://kassa.yandex.ru/developers/payment-forms/widget#apple-pay-configuration">Почитать о подключении Apple Pay в документации Кассы</a>', 'yandex-money-checkout') . '</p></div>';
        }
    }

    private function init_apple_pay()
    {
        clearstatcache();
        $rootDir = $_SERVER['DOCUMENT_ROOT'];
        $domainAssociationPath = $rootDir . '/.well-known/apple-developer-merchantid-domain-association';
        $pluginAssociationPath = YandexMoneyCheckout::$pluginUrl .'/apple-developer-merchantid-domain-association';
        if ($this->isVerifyApplePayFileExist()) {
            return false;
        }

        if (!file_exists($rootDir.'/.well-known')) {
            if (!@mkdir($rootDir.'/.well-known', 0755)) {
                YandexMoneyLogger::error("Error create dir $rootDir/.well-known");
                return false;
            }
        }

        if (!@copy($pluginAssociationPath, $domainAssociationPath)) {
            YandexMoneyLogger::error('Error copy association path');
            return false;
        }

        YandexMoneyLogger::info('Copy association path succeeded');
        return true;
    }

    /**
     *
     * @return bool
     */
    private function isVerifyApplePayFileExist()
    {
        $rootDir = $_SERVER['DOCUMENT_ROOT'];
        $domainAssociationPath = $rootDir . '/.well-known/apple-developer-merchantid-domain-association';
        return file_exists($domainAssociationPath);
    }

    /**
     * @param WC_Order $order
     * @param $save
     *
     * @return \YandexCheckout\Request\Payments\CreatePaymentRequestBuilder
     * @throws Exception
     */
    protected function getBuilder($order)
    {
        $enableHold = get_option('ym_api_enable_hold');

        $amount = YandexMoneyCheckoutOrderHelper::getTotal($order);

        $builder = CreatePaymentRequest::builder()
                   ->setAmount($this->getAmountByCurrency($amount))
                   ->setDescription($this->createDescription($order))
                   ->setCapture(!$enableHold)
                   ->setConfirmation(array(
                       'type' => ConfirmationType::EMBEDDED,
                       'locale' => $this->getLocaleFromBrowser(),
                   ))
                   ->setMetadata($this->createMetadata());

        YandexMoneyLogger::info('Return url: ' . $order->get_checkout_payment_url(true));
        YandexMoneyCheckoutHandler::setReceiptIfNeeded($builder, $order);

        return $builder;
    }

    private function getLocaleFromBrowser()
    {
        $locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        switch ($locale) {
            case 'ru': // Русский
            case 'uk': // Украинский
            case 'be': // Белорусский
            case 'az': // Азербайджанский
            case 'hy': // Армянский
            case 'kk': // Казахский
            case 'ky': // Киргизский
            case 'tg': // Таджикский
            case 'tk': // Туркменский
            case 'uz': // Узбекский
                $return = self::YM_LANG_RU;
                break;
            case 'de':
                $return = self::YM_LANG_DE;
                break;
            default:
                $return = self::YM_LANG_EN;
                break;
        }

        return $return;
    }

    private function render($viewPath, $args)
    {
        extract($args);

        include(plugin_dir_path(__FILE__).$viewPath);
    }
}
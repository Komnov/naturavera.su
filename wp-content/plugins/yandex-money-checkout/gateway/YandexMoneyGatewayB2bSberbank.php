<?php
use YandexCheckout\Common\Exceptions\ApiException;
use YandexCheckout\Model\CurrencyCode;
use YandexCheckout\Model\MonetaryAmount;
use YandexCheckout\Model\PaymentData\B2b\Sberbank\VatData;
use YandexCheckout\Model\PaymentData\B2b\Sberbank\VatDataType;
use YandexCheckout\Model\PaymentData\PaymentDataB2bSberbank;
use YandexCheckout\Model\PaymentMethodType;
use YandexCheckout\Request\Payments\CreatePaymentRequest;
use YandexCheckout\Request\Payments\CreatePaymentRequestSerializer;

if (!class_exists('YandexMoneyCheckoutGateway')) {
    return;
}

class YandexMoneyGatewayB2bSberbank extends YandexMoneyCheckoutGateway
{
    public $paymentMethod = PaymentMethodType::B2B_SBERBANK;

    public $id = 'ym_api_b2b_sberbank';

    public function __construct()
    {
        parent::__construct();

        $this->icon               = YandexMoneyCheckout::$pluginUrl.'/assets/images/sb.png';

        $this->method_description = __('Сбербанк Бизнес Онлайн', 'yandex-money-checkout');
        $this->method_title       = __('Сбербанк Бизнес Онлайн', 'yandex-money-checkout');

        $this->defaultTitle       = __('Сбербанк Бизнес Онлайн', 'yandex-money-checkout');
        $this->defaultDescription = __('Сбербанк Бизнес Онлайн', 'yandex-money-checkout');

        $this->title       = $this->getTitle();
        $this->description = $this->getDescription();
    }

    /**
     * @param WC_Order $order
     *
     * @return mixed|WP_Error
     * @throws Exception
     */
    public function createPayment($order)
    {
        $builder = $this->getBuilder($order);

        $paymentRequest = $builder->build();

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

    /**
     * @param WC_Order $order
     *
     * @param $save
     *
     * @return \YandexCheckout\Request\Payments\CreatePaymentRequestBuilder
     * @throws Exception
     */
    protected function getBuilder($order)
    {
        $paymentData = new PaymentDataB2bSberbank();
        $order_total = YandexMoneyCheckoutOrderHelper::getTotal($order);
        $data        = $order->get_data();
        $items       = $order->get_items();
        $shipping    = $data['shipping_lines'];
        $hasShipping = (bool)count($shipping);
        $sbbolTaxes  = array();

        foreach ($items as $item) {
            $taxes        = $item->get_taxes();
            $sbbolTaxes[] = $this->getSbbolTaxRate($taxes);
        }

        if ($hasShipping) {
            $shippingData = array_shift($shipping);
            $taxes        = $shippingData->get_taxes();
            $sbbolTaxes[] = $this->getSbbolTaxRate($taxes);
        }

        $sbbolTaxes = array_unique($sbbolTaxes);

        if (count($sbbolTaxes) !== 1) {
            throw new Exception('У вас в корзине товары, для которых действуют разные ставки НДС — их нельзя оплатить одновременно. Можно разбить покупку на несколько этапов: сначала оплатить товары с одной ставкой НДС, потом — с другой.');
        }

        $vatType = reset($sbbolTaxes);

        if ($vatType !== VatDataType::UNTAXED) {
            YandexMoneyLogger::log('info', 'Vat rate : '.$vatType);
            $vatRate = $vatType;
            $vatSum  = $order_total * $vatRate / 100;
            $vatData = new VatData(
                VatDataType::CALCULATED,
                $vatRate,
                ['value' => round($vatSum, 2), 'currency' => CurrencyCode::RUB]
            );
        } else {
            $vatData = new VatData(VatDataType::UNTAXED);
        }
        $paymentData->setVatData($vatData);

        $paymentData->setPaymentPurpose($this->createPurposeDescription($order));

        $amount = YandexMoneyCheckoutOrderHelper::getTotal($order);

        $builder = CreatePaymentRequest::builder()
                   ->setAmount($this->getAmountByCurrency($amount))
                   ->setPaymentMethodData($paymentData)
                   ->setCapture(true)
                   ->setDescription($this->createDescription($order))
                   ->setConfirmation(array(
                       'type'      => $this->confirmationType,
                       'returnUrl' => get_site_url(null, sprintf(self::RETURN_URI_PATTERN, $order->get_order_key())),
                   ))
                   ->setMetadata($this->createMetadata());
        YandexMoneyLogger::info('Return url: '.$order->get_checkout_payment_url(true));

        return $builder;
    }

    private function getSbbolTaxRate($taxes)
    {
        $taxRatesRelations = get_option('ym_sbbol_tax_rate');
        $defaultTaxRate    = get_option('ym_sbbol_default_tax_rate');

        if ($taxRatesRelations) {
            $taxesSubtotal = $taxes['total'];

            if ($taxesSubtotal) {
                $wcTaxIds = array_keys($taxesSubtotal);
                $wcTaxId  = $wcTaxIds[0];
                if (isset($taxRatesRelations[$wcTaxId])) {
                    return $taxRatesRelations[$wcTaxId];
                }
            }
        }

        return $defaultTaxRate;
    }

    /**
     * @param WC_Order $order
     *
     * @return string
     */
    public function createPurposeDescription($order)
    {
        $template = get_option('ym_sbbol_purpose', __('Оплата заказа №%order_number%', 'yandex-money-checkout'));

        return $this->parseTemplateString($order, $template);
    }
}
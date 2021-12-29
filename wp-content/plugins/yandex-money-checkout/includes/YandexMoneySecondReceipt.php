<?php

use YandexCheckout\Client;
use YandexCheckout\Common\Exceptions\ApiException;
use YandexCheckout\Common\Exceptions\BadApiRequestException;
use YandexCheckout\Common\Exceptions\ForbiddenException;
use YandexCheckout\Common\Exceptions\InternalServerError;
use YandexCheckout\Common\Exceptions\NotFoundException;
use YandexCheckout\Common\Exceptions\ResponseProcessingException;
use YandexCheckout\Common\Exceptions\TooManyRequestsException;
use YandexCheckout\Common\Exceptions\UnauthorizedException;
use YandexCheckout\Model\Receipt\PaymentMode;
use YandexCheckout\Model\ReceiptCustomer;
use YandexCheckout\Model\ReceiptItem;
use YandexCheckout\Model\ReceiptType;
use YandexCheckout\Model\Settlement;
use YandexCheckout\Request\Receipts\CreatePostReceiptRequest;
use YandexCheckout\Request\Receipts\ReceiptResponseInterface;
use YandexCheckout\Request\Receipts\ReceiptResponseItemInterface;

/**
 * The second-receipt functionality of the plugin.
 */
class YandexMoneySecondReceipt
{
    /**
     * @var Client
     */
    private $apiClient;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    /**
     * @return Client
     */
    private function getApiClient()
    {
        if (!$this->apiClient) {
            $shopId          = get_option('ym_api_shop_id');
            $shopPassword    = get_option('ym_api_shop_password');
            $this->apiClient = new Client();
            $userAgent = $this->apiClient->getApiClient()->getUserAgent();
            $userAgent->setCms('Wordpress', $GLOBALS['wp_version']);
            $userAgent->setFramework('Woocommerce',WOOCOMMERCE_VERSION);
            $userAgent->setModule('PaymentGateway', YAMONEY_API_VERSION);
            $this->apiClient->setAuth($shopId, $shopPassword);
            $this->apiClient->setLogger(new YandexMoneyLogger());
        }

        return $this->apiClient;
    }

    /**
     * @return array
     */
    public static function getValidPaymentMode()
    {
        return array(
            PaymentMode::FULL_PREPAYMENT,
//        пока не нужно, возможно в скором времени будем делать и для них.
//        PaymentMode::PARTIAL_PREPAYMENT,
//        PaymentMode::ADVANCE,
        );
    }

    /**
     * @param int $order_id
     *
     * @throws Exception
     */
    public function changeOrderStatusToProcessing($order_id)
    {
        $this->changeOrderStatus($order_id, 'ChangeOrderStatusToProcessing');
    }

    /**
     * @param int $order_id
     *
     * @throws Exception
     */
    public function changeOrderStatusToCompleted($order_id)
    {
        $this->changeOrderStatus($order_id, 'ChangeOrderStatusToCompleted');
    }

    /**
     * @param ReceiptResponseInterface $lastReceipt
     * @param string $paymentId
     * @param WC_Order $order
     *
     * @return CreatePostReceiptRequest|null
     */
    private function buildSecondReceipt($lastReceipt, $paymentId, $order)
    {
        if ($lastReceipt instanceof ReceiptResponseInterface) {
            if ($lastReceipt->getType() === "refund") {
                return null;
            }

            $resendItems = $this->getResendItems($lastReceipt->getItems());

            if (count($resendItems['items']) < 1) {
                YandexMoneyLogger::info('Second receipt is not required');
                return null;
            }

            try {
                $customer = $this->getReceiptCustomer($order);

                if (empty($customer)) {
                    YandexMoneyLogger::error('Need customer phone or email for second receipt');
                    return null;
                }

                $receiptBuilder = CreatePostReceiptRequest::builder();
                $receiptBuilder->setObjectId($paymentId)
                    ->setType(ReceiptType::PAYMENT)
                    ->setItems($resendItems['items'])
                    ->setSettlements(
                        array(
                            new Settlement(
                                array(
                                    'type' => 'prepayment',
                                    'amount' => array(
                                        'value' => $resendItems['amount'],
                                        'currency' => 'RUB',
                                    ),
                                )
                            ),
                        )
                    )
                    ->setCustomer($customer)
                    ->setSend(true);

                return $receiptBuilder->build();
            } catch (Exception $e) {
                YandexMoneyLogger::error($e->getMessage() . '. Property name: '. $e->getProperty());
            }
        }

        return null;
    }

    /**
     * @param WC_Order $order
     * @return bool|ReceiptCustomer
     */
    private function getReceiptCustomer($order)
    {
        $customerData = array();

        if (!empty($order->get_billing_email())) {
            $customerData['email'] = $order->get_billing_email();
        }

        if (!empty($order->get_billing_phone())) {
            $customerData['phone'] = $order->get_billing_phone();
        }

        if (!empty($order->get_formatted_billing_full_name())) {
            $customerData['full_name'] = $order->get_formatted_billing_full_name();
        }

        return new ReceiptCustomer($customerData);
    }

    /**
     * @param ReceiptResponseInterface $response
     * @return float
     */
    private function getSettlementsAmountSum($response)
    {
        $amount = 0;

        foreach ($response->getSettlements() as $settlement) {
            $amount += $settlement->getAmount()->getIntegerValue();
        }

        return number_format($amount / 100.0, 2, '.', ' ');
    }

    /**
     * @param ReceiptResponseItemInterface[] $items
     *
     * @return array
     */
    private function getResendItems($items)
    {
        $result = array(
            'items'  => array(),
            'amount' => 0,
        );

        foreach ($items as $item) {
            if ( $this->isNeedResendItem($item->getPaymentMode()) ) {
                $item->setPaymentMode(PaymentMode::FULL_PAYMENT);
                $result['items'][] = new ReceiptItem($item->jsonSerialize());
                $result['amount'] += $item->getAmount() / 100.0;
            }
        }

        return $result;
    }


    /**
     * @param string $status
     * @return bool
     */
    private function isNeedSecondReceipt($status)
    {
        $status = $this->convertToWCStatus($status);
        YandexMoneyLogger::info('New status of order is ' . $status . '. We need is ' . $this->getSecondReceiptStatus() . '!');

        if (!$this->isSendReceiptEnable()) {
            return false;
        } elseif (!$this->isSecondReceiptEnable()) {
            return false;
        } elseif ($this->getSecondReceiptStatus() != $status) {
            return false;
        }

        return true;
    }

    /**
     * @param string $paymentMode
     *
     * @return bool
     */
    private function isNeedResendItem($paymentMode)
    {
        return in_array($paymentMode, self::getValidPaymentMode());
    }

    /**
     * @param $paymentId
     * @return mixed|ReceiptResponseInterface
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    private function getLastReceipt($paymentId)
    {
        $receipts = $this->getApiClient()->getReceipts(array('payment_id' => $paymentId))->getItems();

        return array_pop($receipts);
    }

    /**
     * @return bool
     */
    private function isSendReceiptEnable()
    {
        return (bool) get_option('ym_api_enable_receipt');
    }

    /**
     * @return bool
     */
    private function isSecondReceiptEnable()
    {
        return (bool) get_option('ym_api_enable_second_receipt');
    }

    /**
     * @return string
     */
    private function getSecondReceiptStatus()
    {
        return get_option('ym_api_second_receipt_order_status');
    }

    /**
     * Добавляет префикс 'wc-' для текущего статуса, если его нет
     *
     * @param string $status
     * @return string
     */
    private function convertToWCStatus($status)
    {
        return 'wc-' . ('wc-' === substr($status, 0, 3) ? substr($status, 3) : $status);
    }

    /**
     * @param int $order_id
     * @param string $type
     */
    private function changeOrderStatus($order_id, $type)
    {
        YandexMoneyLogger::info('Init YandexMoneySecondReceipt::' . $type);

        if (!$order_id) {
            YandexMoneyLogger::info('Order ID is empty!');
            return;
        }

        $order = wc_get_order($order_id);
        $paymentId = $order->get_transaction_id();

        if (!$this->isNeedSecondReceipt($order->get_status())) {
            YandexMoneyLogger::info('Second receipt is not need!');
            return;
        }

        YandexMoneyLogger::info($type . ' PaymentId: ' . $paymentId);

        try {

            if ($lastReceipt = $this->getLastReceipt($paymentId)) {
                YandexMoneyLogger::info($type . ' LastReceipt:' . PHP_EOL . json_encode($lastReceipt->jsonSerialize()));
            } else {
                YandexMoneyLogger::info($type . ' LastReceipt is empty!');
                return;
            }

            if ($receiptRequest = $this->buildSecondReceipt($lastReceipt, $paymentId, $order)) {

                YandexMoneyLogger::info("Second receipt request data: " . PHP_EOL . json_encode($receiptRequest->jsonSerialize()));

                try {
                    $response = $this->getApiClient()->createReceipt($receiptRequest);
                } catch (Exception $e) {
                    YandexMoneyLogger::error('Request second receipt error: ' . $e->getMessage());
                    return;
                }

                $amount = $this->getSettlementsAmountSum($response);
                $comment = sprintf(__('Отправлен второй чек. Сумма %s рублей.', 'yandex-money-checkout'), $amount);
                $order->add_order_note($comment, 0, false);
                YandexMoneyLogger::info('Request second receipt result: ' . PHP_EOL . json_encode($response->jsonSerialize()));
            }
        } catch (Exception $e) {
            YandexMoneyLogger::info($type . ' Error: ' . $e->getMessage());
            return;
        }
    }
}

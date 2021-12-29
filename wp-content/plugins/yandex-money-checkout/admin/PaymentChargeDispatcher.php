<?php


use YandexCheckout\Client;
use YandexCheckout\Common\Exceptions\ApiException;

/**
 * Class PaymentChargeDispatcher
 */
class PaymentChargeDispatcher
{
    /**
     * @var Client
     */
    private $apiClient;

    /**
     * PaymentChargeDispatcher constructor.
     */
    public function __construct()
    {
        $this->apiClient = $this->getApiClient();
    }

    /**
     * @param string $paymentId
     * @throws Exception
     */
    public function tryChargePayment($paymentId)
    {
        try {
            $order   = $this->getOrderIdByPayment($paymentId);
            $payment = $this->getApiClient()->getPaymentInfo($paymentId);
            YandexMoneyCheckoutHandler::updateOrderStatus($order, $payment);
        } catch (ApiException $e) {
            YandexMoneyLogger::error('Api error: '.$e->getMessage());
        }
    }

    /**
     * @return Client
     */
    private function getApiClient()
    {
        $shopId       = get_option('ym_api_shop_id');
        $shopPassword = get_option('ym_api_shop_password');
        $apiClient    = new Client();
        $userAgent = $apiClient->getApiClient()->getUserAgent();
        $userAgent->setCms('Wordpress', $GLOBALS['wp_version']);
        $userAgent->setFramework('Woocommerce',WOOCOMMERCE_VERSION);
        $userAgent->setModule('PaymentGateway', YAMONEY_API_VERSION);
        $apiClient->setLogger(new YandexMoneyLogger());
        $apiClient->setAuth($shopId, $shopPassword);

        return $apiClient;
    }

    /**
     * @param $id
     *
     * @return null|WC_Order
     */
    private function getOrderIdByPayment($id)
    {
        global $wpdb;

        $query  = "
			SELECT *
			FROM {$wpdb->prefix}postmeta
			WHERE meta_value = %s AND meta_key = '_transaction_id'
		";
        $sql    = $wpdb->prepare($query, $id);
        $result = $wpdb->get_row($sql);

        if ($result) {
            $orderId = $result->post_id;
            $order   = new WC_Order($orderId);

            return $order;
        }

        return null;
    }
}
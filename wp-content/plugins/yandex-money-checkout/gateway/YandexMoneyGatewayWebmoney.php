<?php
use YandexCheckout\Model\PaymentMethodType;

if ( ! class_exists('YandexMoneyCheckoutGateway')) {
    return;
}


class YandexMoneyGatewayWebmoney extends YandexMoneyCheckoutGateway
{
    public $paymentMethod = PaymentMethodType::WEBMONEY;

    public $id = 'ym_api_webmoney';

    public function __construct()
    {
        parent::__construct();

        $this->icon = YandexMoneyCheckout::$pluginUrl.'/assets/images/wm.png';

        $this->method_description = __('Webmoney', 'yandex-money-checkout');
        $this->method_title       = __('Webmoney', 'yandex-money-checkout');

        $this->defaultTitle       = __('Webmoney', 'yandex-money-checkout');
        $this->defaultDescription = __('Webmoney', 'yandex-money-checkout');

        $this->title              = $this->getTitle();
        $this->description        = $this->getDescription();
    }
}
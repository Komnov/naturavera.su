<?php

if ( ! class_exists('YandexMoneyCheckoutGateway')) {
    return;
}

class YandexMoneyGatewayEPL extends YandexMoneyCheckoutGateway
{
    public $paymentMethod = '';

    public $id = 'ym_api_epl';

    /**
     * YandexMoneyGatewayEPL constructor.
     * @TODO вынести функцию перевода в методы getTitle и getDescription. в способах оставить голое название
     */
    public function __construct()
    {
        parent::__construct();

        $this->icon = YandexMoneyCheckout::$pluginUrl.'/assets/images/kassa.png';

        $this->method_title       = __('Яндекс.Касса (банковские карты, электронные деньги и другое)', 'yandex-money-checkout');
        $this->method_description = __('Яндекс.Касса (банковские карты, электронные деньги и другое)', 'yandex-money-checkout');

        $this->defaultTitle       = __('Яндекс.Касса (банковские карты, электронные деньги и другое)', 'yandex-money-checkout');
        $this->defaultDescription = __('Яндекс.Касса (банковские карты, электронные деньги и другое)', 'yandex-money-checkout');

        $this->title              = $this->getTitle();
        $this->description        = $this->getDescription();
    }
}
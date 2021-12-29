<?php
use YandexCheckout\Model\PaymentMethodType;

if ( ! class_exists('YandexMoneyCheckoutGateway')) {
    return;
}


class YandexMoneyGatewayTinkoffBank extends YandexMoneyCheckoutGateway
{
    public $paymentMethod = PaymentMethodType::TINKOFF_BANK;

    public $id = 'ym_api_tinkoff';

    public function __construct()
    {
        parent::__construct();

        $this->icon = YandexMoneyCheckout::$pluginUrl.'/assets/images/tks.png';

        $this->method_description = __('Интернет-банк Тинькофф', 'yandex-money-checkout');
        $this->method_title       = __('Интернет-банк Тинькофф', 'yandex-money-checkout');

        $this->defaultTitle       = __('Интернет-банк Тинькофф', 'yandex-money-checkout');
        $this->defaultDescription = __('Интернет-банк Тинькофф', 'yandex-money-checkout');

        $this->title              = $this->getTitle();
        $this->description        = $this->getDescription();
    }
}
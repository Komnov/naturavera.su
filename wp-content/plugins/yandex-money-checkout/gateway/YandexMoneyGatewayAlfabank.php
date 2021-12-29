<?php
use YandexCheckout\Model\ConfirmationType;
use YandexCheckout\Model\PaymentData\PaymentDataAlfabank;

if (!class_exists('YandexMoneyCheckoutGateway')) {
    return;
}

class YandexMoneyGatewayAlfabank extends YandexMoneyCheckoutGateway
{
    public $has_fields = true;

    public $confirmationType = ConfirmationType::EXTERNAL;

    public $id = 'ym_api_alfabank';

    public function __construct()
    {
        parent::__construct();

        $this->paymentMethod      = new PaymentDataAlfabank();
        $this->icon               = YandexMoneyCheckout::$pluginUrl.'/assets/images/ab.png';

        $this->method_description = __('Оплата через Альфа банк', 'yandex-money-checkout');
        $this->method_title       = __('Альфа-Клик', 'yandex-money-checkout');

        $this->defaultTitle       = __('Альфа-Клик', 'yandex-money-checkout');
        $this->defaultDescription = __('Оплата через Альфа банк', 'yandex-money-checkout');

        $this->title              = $this->getTitle();
        $this->description        = $this->getDescription();

        $this->has_fields         = true;
    }

    public function payment_fields()
    {
        parent::payment_fields();

        $phone_field = '<p class="form-row">
            <label for="login-'.$this->id.'"> '.__('Укажите логин, и мы выставим счет в Альфа-Клике. После этого останется подтвердить платеж на сайте интернет-банка.', 'yandex-money-checkout')
                       .'<span class="required">*</span></label>
			<input id="login-'.$this->id.'" name="login-'.$this->id.'" class="input-text" inputmode="numeric" autocomplete="off" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" maxlength="12"/>
		</p>';

        echo '<fieldset>'.$phone_field.'</fieldset>';
    }

    public function createPayment($order)
    {
        if (isset($_POST['login-ym_api_alfabank'])) {
            try {
                $this->paymentMethod->setLogin($_POST['login-ym_api_alfabank']);
            } catch (Exception $e) {
                wc_add_notice(__('Поле логин заполнено неверно.', 'yandex-money-checkout'), 'error');
            }
        }

        return parent::createPayment($order);
    }
}
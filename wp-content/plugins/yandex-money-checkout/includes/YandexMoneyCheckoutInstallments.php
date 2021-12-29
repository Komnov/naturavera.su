<?php
/**
 * The installments functionality of the plugin.
 */
class YandexMoneyCheckoutInstallments
{
    const MIN_AMOUNT = 3000;

    /**
     * @var string $plugin_name
     */
    private $plugin_name;

    /**
     * @param string $plugin_name
     */
    public function __construct($plugin_name)
    {
        $this->plugin_name = $plugin_name;
    }

    /**
     * @return void
     */
    public function showInfo()
    {
        $this->enqueue_styles();
        $this->enqueue_scripts();

        /** @var WC_Product $product */
        global $product;

        $showInfoInKassaMode = (get_option('ym_api_pay_mode') === '1') && (get_option('ym_api_epl_installments') === '1');

        $options              = (array)get_option('woocommerce_ym_api_installments_settings');
        $installments_enabled = (!empty($options['enabled']) && $options['enabled'] === 'yes');
        $showInfoInShopMode   = (get_option('ym_api_pay_mode') === '0') && $installments_enabled;

        if (!$showInfoInKassaMode && !$showInfoInShopMode) {
            return;
        }

        if (get_option('ym_api_add_installments_block') !== '1') {
            return;
        };

        $shopId   = get_option('ym_api_shop_id');
        $price    = $product->get_price();
        $language = mb_substr(get_bloginfo('language'), 0, 2);

        echo <<<END
<div class="installments-info"></div>
<script>
    jQuery(document).ready(function(){
        const yamoneyCheckoutCreditUI = YandexCheckoutCreditUI({
            shopId: $shopId,
            sum: $price,
            language: '$language'
        });
        yamoneyCheckoutCreditUI({
            type: 'info',
            domSelector: '.installments-info'
        });
    });
</script>
END;
    }

    /**
     * @return void
     */
    public function showExtraCheckoutInfo()
    {
        global $woocommerce;
        $sum = (float)$woocommerce->cart->total;

        $shopId = get_option('ym_api_shop_id');

        $extraInfo = __(' (%s ₽ в месяц)', 'yandex-money-checkout');

        if (get_option('ym_api_epl_installments') == '1') {
            echo <<<END
<script>
        jQuery.get("https://money.yandex.ru/credit/order/ajax/credit-pre-schedule?shopId="
            + $shopId + "&sum=" + $sum, function (data) {
            const ym_installments_amount_text = "$extraInfo";
            if (ym_installments_amount_text && data && data.amount) {
                jQuery('label[for=payment_method_ym_api_installments] img').before(ym_installments_amount_text.replace('%s', data.amount));
            }
        });
</script>
END;
        }
    }

    /**
     * Register the stylesheets
     */
    private function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            YandexMoneyCheckout::$pluginUrl.'/assets/css/yandex-checkout.css'
        );
    }

    /**
     * Register the JavaScript
     */
    private function enqueue_scripts()
    {
        global $wp;
        $product_page = (!empty($wp->query_vars['post_type']) && $wp->query_vars['post_type'] == 'product');
        if ((!$product_page && get_option('ym_api_epl_installments') == '1') ||
            ($product_page && get_option('ym_api_add_installments_block') == '1')) {
            wp_enqueue_script(
                $this->plugin_name,
                'https://static.yandex.net/kassa/pay-in-parts/ui/v1/'
            );
        }
    }

}

<?php

use YandexCheckout\Model\CurrencyCode;
use YandexCheckout\Model\PaymentData\B2b\Sberbank\VatDataRate;
use YandexCheckout\Model\PaymentData\B2b\Sberbank\VatDataType;
use YandexCheckout\Model\Receipt\PaymentMode;
use YandexCheckout\Model\Receipt\PaymentSubject;

/**
 * The admin-specific functionality of the plugin.
 */
class YandexMoneyCheckoutAdmin
{
    const CREDENTIAL_SUCCESS          = 0;
    const CREDENTIAL_AUTHORIZED_ERROR = 1;
    const CREDENTIAL_OTHER_ERROR      = 2;

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
     * Задержка перед повторным показом блока NPS (в днях)
     * @var int
     */
    private $npsRetryAfterDays = 90;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;

        add_action( 'wp_ajax_yandex_checkout_get_tab', array( $this, 'get_tab_content' ) );
        add_action( 'wp_ajax_yandex_checkout_save_settings', array( $this, 'save_settings' ) );
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_register_style(
            'bootstrap-css',
            YandexMoneyCheckout::$pluginUrl . 'assets/css/bootstrap.min.css',
            array(),
            '3.3.7',
            'all'
        );
        wp_enqueue_style( 'bootstrap-css' );

        wp_register_style(
            $this->plugin_name . '-css',
            YandexMoneyCheckout::$pluginUrl . '/assets/css/yandex-checkout-admin.css',
            array('bootstrap-css'),
            $this->version,
            'all'
        );
        wp_enqueue_style( $this->plugin_name . '-css' );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     *
     * @param string $hook
     */
    public function enqueue_scripts()
    {
        wp_register_script(
            'bootstrap-js',
            YandexMoneyCheckout::$pluginUrl . 'assets/js/bootstrap.min.js',
            array(),
            '3.3.7',
            'all'
        );
        wp_enqueue_script( 'bootstrap-js' );

        wp_register_script(
            'clipboard-js',
            YandexMoneyCheckout::$pluginUrl . '/assets/js/clipboard.min.js',
            array(),
            '2.0.4',
            false
        );
        wp_enqueue_script( 'clipboard-js' );

        wp_register_script(
            $this->plugin_name . '-js',
            YandexMoneyCheckout::$pluginUrl . '/assets/js/yandex-checkout-admin.js',
            array('jquery', 'bootstrap-js'),
            '12412312',
            false
        );
        wp_enqueue_script( $this->plugin_name . '-js' );
    }

    public function addMenu()
    {
        $hook = add_submenu_page(
            'woocommerce',
            __('Настройки Яндекс.Касса 2.0', 'yandex-money-checkout'),
            __('Настройки Яндекс.Касса 2.0', 'yandex-money-checkout'),
            'manage_options',
            'yandex_money_api_menu',
            array($this, 'renderAdminPage')
        );

        // make sure the style callback is used on our page only
        add_action(
            "admin_print_styles-$hook",
            array ( $this, 'enqueue_styles' )
        );
        add_action(
            "admin_print_scripts-$hook",
            array ( $this, 'enqueue_scripts' )
        );
    }

    public function registerSettings()
    {
        register_setting('woocommerce-yamoney-api', 'ym_api_shop_id');
        register_setting('woocommerce-yamoney-api', 'ym_api_shop_password');
        register_setting('woocommerce-yamoney-api', 'ym_api_pay_mode');
        register_setting('woocommerce-yamoney-api', 'ym_api_epl_installments');
        register_setting('woocommerce-yamoney-api', 'ym_api_add_installments_block');
        register_setting('woocommerce-yamoney-api', 'ym_api_success');
        register_setting('woocommerce-yamoney-api', 'ym_api_fail');
        register_setting('woocommerce-yamoney-api', 'ym_api_tax_rates_enum');
        register_setting('woocommerce-yamoney-api', 'ym_api_enable_hold');
        register_setting('woocommerce-yamoney-api', 'ym_api_description_template');
        register_setting('woocommerce-yamoney-api', 'ym_api_enable_receipt');
        register_setting('woocommerce-yamoney-api', 'ym_api_enable_second_receipt');
        register_setting('woocommerce-yamoney-api', 'ym_api_second_receipt_order_status');
        register_setting('woocommerce-yamoney-api', 'ym_debug_enabled');
        register_setting('woocommerce-yamoney-api', 'ym_api_default_tax_rate');
        register_setting('woocommerce-yamoney-api', 'ym_force_clear_cart');
        register_setting('woocommerce-yamoney-api', 'ym_api_tax_rate');
        register_setting('woocommerce-yamoney-api', 'ym_enable_sbbol');
        register_setting('woocommerce-yamoney-api', 'ym_sbbol_tax_rates_enum');
        register_setting('woocommerce-yamoney-api', 'ym_sbbol_default_tax_rate');
        register_setting('woocommerce-yamoney-api', 'ym_sbbol_tax_rate');
        register_setting('woocommerce-yamoney-api', 'ym_sbbol_purpose');
        register_setting('woocommerce-yamoney-api', 'ym_api_payment_subject_default');
        register_setting('woocommerce-yamoney-api', 'ym_api_payment_mode_default');
        register_setting('woocommerce-yamoney-api', 'ym_api_shipping_payment_subject_default');
        register_setting('woocommerce-yamoney-api', 'ym_api_shipping_payment_mode_default');
        register_setting('woocommerce-yamoney-api', 'ym_kassa_currency');
        register_setting('woocommerce-yamoney-api', 'ym_kassa_currency_convert');

        update_option(
            'ym_sbbol_tax_rates_enum',
            array(
                VatDataType::UNTAXED => __('Без НДС', 'yandex-money-checkout'),
                VatDataRate::RATE_7  => '7%',
                VatDataRate::RATE_10 => '10%',
                VatDataRate::RATE_18 => '18%',
                VatDataRate::RATE_20 => '20%',
            )
        );

        update_option(
            'ym_api_tax_rates_enum',
            array(
                1 => __('Не облагается', 'yandex-money-checkout'),
                2 => '0%',
                3 => '10%',
                4 => '20%',
                5 => __('Расчетная ставка 10/110', 'yandex-money-checkout'),
                6 => __('Расчетная ставка 20/120', 'yandex-money-checkout'),
            )
        );
    }

    private function get_all_settings()
    {
        $wcTaxes                = $this->getAllTaxes();
        $wcCalcTaxes            = get_option('woocommerce_calc_taxes');
        $ymTaxRatesEnum         = get_option('ym_api_tax_rates_enum');
        $pages                  = get_pages();
        $ymTaxes                = get_option('ym_api_tax_rate');
        $isHoldEnabled          = get_option('ym_api_enable_hold');
        $isSbBOLEnabled         = get_option('ym_enable_sbbol');
        $descriptionTemplate    = get_option('ym_api_description_template',
            __('Оплата заказа №%order_number%', 'yandex-money-checkout'));
        $isReceiptEnabled       = get_option('ym_api_enable_receipt');
        $isSecondReceiptEnabled = get_option('ym_api_enable_second_receipt');
        $orderStatusReceipt     = get_option('ym_api_second_receipt_order_status', 'wc-completed');
        $isDebugEnabled         = (bool)get_option('ym_debug_enabled', '0');
        $forceClearCart         = (bool)get_option('ym_force_clear_cart', '0');
        $testMode               = $this->isTestMode();
        $active_tab             = isset($_GET['tab']) ? $_GET['tab'] : 'yandex-checkout-settings';

        $shopId                 = get_option('ym_api_shop_id');
        $password               = get_option('ym_api_shop_password');
        $npsVoteTime            = get_option('ym_api_nps_vote_time');
        $sbbolTemplate          = get_option('ym_sbbol_purpose', __('Оплата заказа №%order_number%', 'yandex-money-checkout'));
        $payMode                = get_option('ym_api_pay_mode');

        $kassaCurrency          = get_option('ym_kassa_currency');
        $kassaCurrencyConvert   = get_option('ym_kassa_currency_convert');

        $validCredentials = null;


        if (!empty($shopId) && !empty($password)) {
            $validCredentials = $this->testConnection($shopId, $password);
        }

        $isNeededShowNps = time() > (int)$npsVoteTime + $this->npsRetryAfterDays * 86400
            && substr($password, 0, 5) === 'live_'
            && get_locale() === 'ru_RU';

        $paymentSubjectEnum = array(
            PaymentSubject::COMMODITY             => __('Товар', 'yandex-money-checkout') . ' ('.PaymentSubject::COMMODITY.')',
            PaymentSubject::EXCISE                => __('Подакцизный товар', 'yandex-money-checkout') . ' ('.PaymentSubject::EXCISE.')',
            PaymentSubject::JOB                   => __('Работа', 'yandex-money-checkout') . ' ('.PaymentSubject::JOB.')',
            PaymentSubject::SERVICE               => __('Услуга', 'yandex-money-checkout') . ' ('.PaymentSubject::SERVICE.')',
            PaymentSubject::GAMBLING_BET          => __('Ставка в азартной игре', 'yandex-money-checkout') . ' ('.PaymentSubject::GAMBLING_BET.')',
            PaymentSubject::GAMBLING_PRIZE        => __('Выигрыш в азартной игре', 'yandex-money-checkout') . ' ('.PaymentSubject::GAMBLING_PRIZE.')',
            PaymentSubject::LOTTERY               => __('Лотерейный билет', 'yandex-money-checkout') . ' ('.PaymentSubject::LOTTERY.')',
            PaymentSubject::LOTTERY_PRIZE         => __('Выигрыш в лотерею', 'yandex-money-checkout') . ' ('.PaymentSubject::LOTTERY_PRIZE.')',
            PaymentSubject::INTELLECTUAL_ACTIVITY => __('Результаты интеллектуальной деятельности', 'yandex-money-checkout') . ' ('.PaymentSubject::INTELLECTUAL_ACTIVITY.')',
            PaymentSubject::PAYMENT               => __('Платеж', 'yandex-money-checkout') . ' ('.PaymentSubject::PAYMENT.')',
            PaymentSubject::AGENT_COMMISSION      => __('Агентское вознаграждение', 'yandex-money-checkout') . ' ('.PaymentSubject::AGENT_COMMISSION.')',
            PaymentSubject::COMPOSITE             => __('Несколько вариантов', 'yandex-money-checkout') . ' ('.PaymentSubject::COMPOSITE.')',
            PaymentSubject::ANOTHER               => __('Другое', 'yandex-money-checkout') . ' ('.PaymentSubject::ANOTHER.')',
        );

        $paymentModeEnum = array(
            PaymentMode::FULL_PREPAYMENT    => __('Полная предоплата', 'yandex-money-checkout') . ' ('.PaymentMode::FULL_PREPAYMENT.')',
            PaymentMode::PARTIAL_PREPAYMENT => __('Частичная предоплата', 'yandex-money-checkout') . ' ('.PaymentMode::PARTIAL_PREPAYMENT.')',
            PaymentMode::ADVANCE            => __('Аванс', 'yandex-money-checkout') . ' ('.PaymentMode::ADVANCE.')',
            PaymentMode::FULL_PAYMENT       => __('Полный расчет', 'yandex-money-checkout') . ' ('.PaymentMode::FULL_PAYMENT.')',
            PaymentMode::PARTIAL_PAYMENT    => __('Частичный расчет и кредит', 'yandex-money-checkout') . ' ('.PaymentMode::PARTIAL_PAYMENT.')',
            PaymentMode::CREDIT             => __('Кредит', 'yandex-money-checkout') . ' ('.PaymentMode::CREDIT.')',
            PaymentMode::CREDIT_PAYMENT     => __('Выплата по кредиту', 'yandex-money-checkout') . ' ('.PaymentMode::CREDIT_PAYMENT.')',
        );

        $wcOrderStatuses = wc_get_order_statuses();
        $wcOrderStatuses = array_filter($wcOrderStatuses, function ($k) {
            return in_array($k, self::getValidOrderStatuses());
        }, ARRAY_FILTER_USE_KEY);

        $kassaCurrencies = $this->createKassaCurrencyList();

        return array(
            'wcTaxes'                => $wcTaxes,
            'pages'                  => $pages,
            'wcCalcTaxes'            => $wcCalcTaxes,
            'ymTaxRatesEnum'         => $ymTaxRatesEnum,
            'ymTaxes'                => $ymTaxes,
            'isHoldEnabled'          => $isHoldEnabled,
            'isSbBOLEnabled'         => $isSbBOLEnabled,
            'descriptionTemplate'    => $descriptionTemplate,
            'isReceiptEnabled'       => $isReceiptEnabled,
            'isSecondReceiptEnabled' => $isSecondReceiptEnabled,
            'orderStatusReceipt'     => $orderStatusReceipt,
            'testMode'               => $testMode,
            'isDebugEnabled'         => $isDebugEnabled,
            'forceClearCart'         => $forceClearCart,
            'validCredentials'       => $validCredentials,
            'active_tab'             => $active_tab,
            'isNeededShowNps'        => $isNeededShowNps,
            'sbbolTemplate'          => $sbbolTemplate,
            'paymentModeEnum'        => $paymentModeEnum,
            'paymentSubjectEnum'     => $paymentSubjectEnum,
            'payMode'                => $payMode,
            'wcOrderStatuses'        => $wcOrderStatuses,
            'kassaCurrencies'        => $kassaCurrencies,
            'kassaCurrency'          => $kassaCurrency,
            'kassaCurrencyConvert'   => $kassaCurrencyConvert,
        );
    }

    public function renderAdminPage()
    {
        $this->render(
            'partials/admin-settings-view.php',
            $this->get_all_settings()
        );
    }

    /**
     * @return array
     */
    public static function getValidOrderStatuses()
    {
        return array('wc-processing', 'wc-completed');
    }

    /**
     * Get tab settings
     */
    public function get_tab_content ()
    {
        $file = 'partials/tabs/' . $_GET['tab'] . '.php';
        if (is_file(plugin_dir_path(__FILE__) . $file)) {
            $this->render($file, $this->get_all_settings());
        } else {
            echo 'Error! File "' . $file . '" not found';
        }
        wp_die();
    }

    /**
     * Save settings
     */
    public function save_settings()
    {
        header('Content-Type: application/json');

        if (!is_ajax()) {
            echo json_encode(array('status' => 'error', 'error' => 'Unknown', 'code' => 'unknown'));
            wp_die();
        }

        if ($options = explode(',', wp_unslash($_POST['page_options']))) {
            $user_language_old = get_user_locale();

            foreach ($options as $option) {
                $option = trim($option);
                $value = null;
                if (isset($_POST[$option])) {
                    $value = $_POST[$option];
                    if (!is_array($value)) {
                        $value = trim($value);
                    }
                    $value = wp_unslash($value);
                }
                update_option($option, $value);
            }

            unset($GLOBALS['locale']);
            $user_language_new = get_user_locale();
            if ($user_language_old !== $user_language_new) {
                load_default_textdomain($user_language_new);
            }
        } else {
            echo json_encode(array('status' => 'error', 'error' => 'Unknown', 'code' => 'unknown'));
            wp_die();
        }

        echo json_encode(array('status' => 'success', 'post' => $_POST));
        wp_die();
    }

    public function voteNps()
    {
        update_option('ym_api_nps_vote_time', time());
    }

    public function sendStatistic()
    {
        YandexMoneyStatistic::send();
    }

    public function getAllTaxes()
    {
        global $wpdb;

        $query = "
            SELECT *
            FROM {$wpdb->prefix}woocommerce_tax_rates
            WHERE 1 = 1
        ";

        $order_by = ' ORDER BY tax_rate_order';

        $result = $wpdb->get_results($query.$order_by);

        return $result;
    }

    private function render($viewPath, $args)
    {
        extract($args);

        include(plugin_dir_path(__FILE__).$viewPath);
    }

    private function isTestMode()
    {
        $shopPassword = get_option('ym_api_shop_password');
        $prefix       = substr($shopPassword, 0, 4);

        return $prefix == "test";
    }

    private function testConnection($shopId, $password)
    {
        require_once plugin_dir_path(dirname(__FILE__)).'includes/lib/autoload.php';

        $apiClient = new YandexCheckout\Client();
        $userAgent = $apiClient->getApiClient()->getUserAgent();
        $userAgent->setCms('Wordpress', $GLOBALS['wp_version']);
        $userAgent->setFramework('Woocommerce',WOOCOMMERCE_VERSION);
        $userAgent->setModule('PaymentGateway', YAMONEY_API_VERSION);
        $apiClient->setAuth($shopId, $password);
        $apiClient->setLogger(new YandexMoneyLogger());

        try {
            $payment = $apiClient->getPaymentInfo('00000000-0000-0000-0000-000000000001');
        } catch (\YandexCheckout\Common\Exceptions\NotFoundException $e) {
            return self::CREDENTIAL_SUCCESS;
        } catch (\YandexCheckout\Common\Exceptions\UnauthorizedException $e) {
            return self::CREDENTIAL_AUTHORIZED_ERROR;
        } catch (\Exception $e) {
            return self::CREDENTIAL_OTHER_ERROR;
        }

        return self::CREDENTIAL_SUCCESS;
    }

    /**
     * @return array
     */
    private function createKassaCurrencyList()
    {
        $allCurrencies = get_woocommerce_currencies();
        $currentCurrency = get_woocommerce_currency();
        $kassa_currencies = CurrencyCode::getEnabledValues();

        $available_currencies = array(CurrencyCode::RUB);
        if (in_array($currentCurrency, $kassa_currencies)) {
            $available_currencies[] = $currentCurrency;
        }

        $return_currencies = array();
        foreach (array_unique($available_currencies) as $code) {
            $return_currencies[$code] = $allCurrencies[$code];
        }
        return $return_currencies;
    }
}

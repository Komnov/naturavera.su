<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           YandexMoneyCheckout
 *
 * @wordpress-plugin
 * Plugin Name:       Яндекс.Касса 2.0 для Woocommerce
 * Plugin URI:        https://wordpress.org/plugins/yandex-money-checkout/
 * Description:       Платежный модуль для работы с сервисом Яндекс.Касса через плагин WooCommerce
 * Version:           1.6.5
 * Author:            Yandex.Money
 * Author URI:        http://kassa.yandex.ru
 * License URI:       https://money.yandex.ru/doc.xml?id=527132
 * Text Domain:       yandex-money-checkout
 * Domain Path:       /languages
 *
 * WC requires at least: 3.6.0
 * WC tested up to: 4.6.0
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

function yandexcheckout_plugin_activate()
{
    if (!yandexcheckout_check_woocommerce_plugin_status()) {
        deactivate_plugins(__FILE__);
        $error_message = __("Плагин Яндекс.Касса 2.0 для WooCommerce требует, чтобы плагин <a href=\"http://wordpress.org/extend/plugins/woocommerce/\" target=\"_blank\">WooCommerce</a> был активен!", 'yandex-money-checkout');
        wp_die($error_message);
    }
    require_once plugin_dir_path(__FILE__) . 'includes/YandexMoneyCheckoutActivator.php';
    YandexMoneyCheckoutActivator::activate();
}

function yandexcheckout_plugin_deactivate()
{
    require_once plugin_dir_path(__FILE__) . 'includes/YandexMoneyCheckoutDeactivator.php';
    YandexMoneyCheckoutDeactivator::deactivate();
}

/**
 * @return bool
 */
function yandexcheckout_check_woocommerce_plugin_status()
{
    if (defined("RUNNING_CUSTOM_WOOCOMMERCE") && RUNNING_CUSTOM_WOOCOMMERCE === true) {
        return true;
    }
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        return true;
    }
    if (!is_multisite()) return false;
    $plugins = get_site_option('active_sitewide_plugins');
    return isset($plugins['woocommerce/woocommerce.php']);
}

register_activation_hook(__FILE__, 'yandexcheckout_plugin_activate');
register_deactivation_hook(__FILE__, 'yandexcheckout_plugin_deactivate');

if (yandexcheckout_check_woocommerce_plugin_status()) {
    require plugin_dir_path(__FILE__) . 'includes/YandexMoneyCheckout.php';

    $plugin = new YandexMoneyCheckout();

    define('YAMONEY_API_VERSION', $plugin->getVersion());

    $plugin->run();
}
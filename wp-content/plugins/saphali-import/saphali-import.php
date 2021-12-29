<?php 
/*
Plugin Name: Saphali Woocommerce Import
Plugin URI: http://saphali.com/saphali-woocommerce-plugin-wordpress
Description: Saphali Woocommerce Import - импорт товаров.
Подробнее на сайте <a href="http://saphali.com/saphali-woocommerce-plugin-wordpress">Saphali Woocommerce</a>

Version: 3.0.14
Author: Saphali
Author URI: http://saphali.com/
WC requires at least: 1.6.6
WC tested up to: 3.6
*/


/*

 Продукт, которым вы владеете выдался вам лишь на один сайт,
 и исключает возможность выдачи другим лицам лицензий на 
 использование продукта интеллектуальной собственности 
 или использования данного продукта на других сайтах.

 */

//END


add_action('plugins_loaded', 'woocommerce_liqpay_p24_imp');
function woocommerce_liqpay_p24_imp() {
	if( is_admin() ) {
		define('SAPHALI_PLUGIN_DIR_URL_IMP',plugin_dir_url(__FILE__));
		define('SAPHALI_PLUGIN_VERSION_IMP','3.0.15');
		define('SAPHALI_PLUGIN_DIR_PATH_IMP',plugin_dir_path(__FILE__));
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array('CSVLoaderForWoocommerceImp', 'plugin_manage_link') , 10, 4 );
		if(isset($_POST['action']) && $_POST['action'] == 'import_expoexp_s_get_pr' || isset($_POST['action']) && $_POST['action'] == 'import_expoexp_s') {
			// add_filter('woocommerce_product_is_in_stock', function($s){$s = true;return $s;});
			add_filter('woocommerce_product_is_in_stock', array('CSVLoaderForWoocommerceImp', 'woocommerce_product_is_in_stock') );
		}
		include_once (SAPHALI_PLUGIN_DIR_PATH_IMP . 'csvforwoocommerce.php');
	}
}
register_activation_hook( __FILE__, 'Woo_Saphali_Woocommerce_Import_install' );
function Woo_Saphali_Woocommerce_Import_install() {
	
	$transient_name = 'wc_saph_' . md5( 'saphali-import' . home_url() );
	$pay[$transient_name] = get_transient( $transient_name );
	delete_option( str_replace('wc_saph_', '_latest_', $transient_name) );
	foreach($pay as $key => $tr) {
		delete_transient( $key );
	}
}

if( !function_exists("saphali_app_is_real") ) {
	add_action('init', 'saphali_app_is_real' );
	function saphali_app_is_real () {
		if(isset( $_POST['real_remote_addr_to'] ) ) {
			echo "print|";
			echo $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['REMOTE_ADDR'] . ":" . $_POST['PARM'] ;
			exit;	
		}
	}
}
?>
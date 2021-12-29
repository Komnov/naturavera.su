<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Woo_Payment_Discounts_Admin class
 */
class Woo_Payment_Discounts_Admin {

	/**
	 * Initialize the plugin admin.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wpd_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'wpd_do_activation_redirect' ) );
		add_action( 'admin_menu', array( $this, 'wpd_screen_pages' ) );
		add_action( 'admin_head', array( $this, 'wpd_screen_remove_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Register admin menu
	 */
	public function wpd_admin_menu() {
		add_submenu_page( 'woocommerce', __( 'Discount Per Payment method for WooCommerce', 'woo-payment-discounts' ), __( 'Discount Per Payment', 'woo-payment-discounts' ), 'manage_woocommerce', 'woo-payment-discounts', array(
				$this,
				'plugin_admin_page_callback',
			) );
	}

	/**
	 * Render the settings page for this plugin.
	 */
	public function plugin_admin_page_callback() {
		$settings = get_option( 'woo_payment_discounts' );
		$payment_gateways = WC()->payment_gateways->payment_gateways();
		include_once 'partials/woo-payment-discounts-admin-display.php';
	}

	public function wpd_do_activation_redirect() {
		// Bail if no activation redirect
		if ( ! get_transient( '_wpd_screen_activation_redirect' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( '_wpd_screen_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		// Redirect to bbPress about page
		wp_safe_redirect( add_query_arg( array( 'page' => 'wpd-about' ), admin_url( 'index.php' ) ) );
	}

	/**
	 * Welcome screen page function added.
	 */
	public function wpd_screen_pages() {
		add_dashboard_page( 'Welcome To Discounts Per Payment Method for WooCommerce', 'Discounts Per Payment Method for WooCommerce', 'read', 'wpd-about', array( $this, 'wpd_screen_content' ) );
	}

	/**
	 * Welcome screen content.
	 */
	public function wpd_screen_content() {
		?>
		<div class="wrap wpd_welcome_wrap">
			<fieldset>
				<h2>Welcome to Discounts Per Payment Method for WooCommerce <?php echo Woo_Payment_Discounts::VERSION; ?></h2>
				<div class="wpd_welcom_div">
					<div class="wpd_lite">
						<div>Thank you for installing Discounts Per Payment Method for WooCommerce <?php echo Woo_Payment_Discounts::VERSION; ?></div>
						<div>Discounts Per Payment Method for WooCommerce allows you to Setup discounts for specific payment methods is selected on checkout.</div>

						<div class="block-content"><h4>How to Setup :</h4>

							<ul>
								<li>Step-1: Go to admin dashboard.</li>
								<li>Step-2: Under WooCommerce menu page you will find 'Discount Per Payment' menu page link. Go to that page</li>
								<li>Step-3: Save settings as per your need.</li>
							</ul>
						</div>


					</div>
					<p class="wpd_pro">
						<a href="https://codecanyon.net/item/woocommerce-advanced-discounts-and-fees/19009855?s_rank=1" target="_blank"><h3>WooCommerce Advanced Discounts and Fees</h3></a>

					<h4><strong> Key Features of WooCommerce Advanced Discounts and Fees</strong></h4>
					<ul>
						<li>
							<strong>Set discounts based on payment method selected </strong>
							- For example you want to give your customer 5% discounts if they buy your product using PayPal then you can set using this feature.
						</li>
						<li><strong>Set extra fee based on payment method selected </strong>
							- For example you want to add extra charge when customer choose specific payment method on checkout page then you can set using this feature.
						</li>
						<li><strong>Set discounts based on shipping method selected </strong>
							- For example you want to give your customer 20$ discounts if they choose specific shipping method on checkout page then you can set using this feature.
						</li>
						<li><strong>Set extra fee based on shipping method selected </strong>
							- For example you want to add extra charge when customer choose shipping method on checkout page then you can set using this feature.
						</li>
						<li><strong>Set fee or discount in fixed amount or in percentage</strong>
							- Set discount or fee in fixed amount or in percentage.
						</li>
						<li><strong>Set label for each payment method and shipping method discount or fee</strong></li>


					</ul>
					<a href="https://codecanyon.net/item/woocommerce-advanced-discounts-and-fees/19009855?s_rank=1" target="_blank"><h4> Download WooCommerce Advanced Discounts and Fees Plugin</h4>
					</a>
			</fieldset>
		</div>

		</div>


		<?php
	}

	/**
	 * Remove welcome screen
	 */
	public function wpd_screen_remove_menus() {
		remove_submenu_page( 'index.php', 'wpd-about' );
	}

	/**
	 * Enqueue front style css.
	 *
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'wpdcss', WPD_PLUGIN_URL . '/assets/css/wpd_custom.css', array(), false, 'all' );
	}

}

new Woo_Payment_Discounts_Admin();

<?php
/*
Plugin Name: YITH Activator
Description: License activator for YITHemes Plugins
Version: 1.3
Author: Null Market Team
Author URI: https://null.market
*/

/*
CNANGELOG

Version 1.3 - 22.05.2019
- Fix old slugs for several plugins

Version 1.2 - 04.03.2019
- Fix old slugs for several plugins

Version 1.1 - 29.07.2018
- Fix old slugs for several plugins

Version 1.0 - 13.07.2018
- Initial Release

*/

$plugins_list = [
	'yith-advanced-refund-system-for-woocommerce',
	'yith-automatic-role-changer-for-woocommerce',
	'yith-best-price-guaranteed-for-woocommerce',
	'yith-composite-products-for-woocommerce',
	'yith-cost-of-goods-for-woocommerce',
	'yith-custom-thank-you-page-for-woocommerce',
	'yith-desktop-notifications-for-woocommerce',
	'yith-donations-for-woocommerce',
	'yith-dynamic-pricing-per-payment-method-for-woocommerce',
	'yith-event-tickets-for-woocommerce',
	'yith-frontend-manager-for-woocommerce',
	'yith-geoip-language-redirect-for-woocommerce',
	'yith-google-product-feed-for-woocommerce',
	'yith-infinite-scrolling',
	'yith-live-chat',
	'yith-payment-method-restrictions-for-woocommerce',
	'yith-paypal-adaptive-payments-for-woocommerce',
	'yith-paypal-payouts-for-woocommerce',
	'yith-product-shipping-for-woocommerce',
	'yith-product-size-charts-for-woocommerce',
	'yith-quick-order-forms-for-woocommerce',
	'yith-woocommerce-account-funds',
	'yith-woocommerce-added-to-cart-popup',
	'yith-woocommerce-additional-uploads',
	'yith-woocommerce-advanced-product-options',
	'yith-woocommerce-advanced-reviews',
	'yith-woocommerce-affiliates',
	'yith-woocommerce-ajax-navigation',
	'yith-woocommerce-ajax-product-filter',
	'yith-woocommerce-ajax-search',
	'yith-woocommerce-anti-fraud',
	'yith-woocommerce-auctions',
	'yith-woocommerce-authorizenet-payment-gateway',
	'yith-woocommerce-badges-management',
	'yith-woocommerce-barcodes',
	'yith-woocommerce-best-sellers',
	'yith-woocommerce-booking',
	'yith-woocommerce-brands-add-on',
	'yith-woocommerce-bulk-product-editing',
	'yith-woocommerce-cart-messages',
	'yith-woocommerce-catalog-mode',
	'yith-woocommerce-category-accordion',
	'yith-woocommerce-checkout-manager',
	'yith-woocommerce-color-label-variations',
	'yith-woocommerce-compare',
	'yith-woocommerce-coupon-email-system',
	'yith-woocommerce-custom-order-status',
	'yith-woocommerce-customer-history',
	'yith-woocommerce-customize-myaccount-page',
	'yith-woocommerce-delivery-date',
	'yith-woocommerce-deposits-and-down-payments',
	'yith-woocommerce-dynamic-pricing-and-discounts',
	'yith-woocommerce-email-templates',
	'yith-woocommerce-eu-energy-label',
	'yith-woocommerce-eu-vat',
	'yith-woocommerce-featured-video',
	'yith-woocommerce-frequently-bought-together',
	'yith-woocommerce-gift-cards',
	'yith-woocommerce-mailchimp',
	'yith-woocommerce-membership',
	'yith-woocommerce-minimum-maximum-quantity',
	'yith-woocommerce-multi-step-checkout',
	'yith-woocommerce-name-your-price',
	'yith-woocommerce-one-click-checkout',
	'yith-woocommerce-order-tracking',
	'yith-woocommerce-pdf-invoice',
	'yith-woocommerce-pending-order-survey',
	'yith-woocommerce-points-and-rewards',
	'yith-woocommerce-popup',
	'yith-woocommerce-pre-order',
	'yith-woocommerce-product-bundles',
	'yith-woocommerce-product-countdown',
	'yith-woocommerce-product-slider-carousel',
	'yith-woocommerce-product-vendors',
	'yith-woocommerce-questions-and-answers',
	'yith-woocommerce-quick-checkout-for-digital-goods',
	'yith-woocommerce-quick-export',
	'yith-woocommerce-quick-view',
	'yith-woocommerce-recently-viewed-products',
	'yith-woocommerce-recover-abandoned-cart',
	'yith-woocommerce-request-a-quote',
	'yith-woocommerce-review-for-discounts',
	'yith-woocommerce-review-reminder',
	'yith-woocommerce-role-based-prices',
	'yith-woocommerce-save-for-later',
	'yith-woocommerce-sequential-order-number',
	'yith-woocommerce-share-for-discounts',
	'yith-woocommerce-sms-notifications',
	'yith-woocommerce-social-login',
	'yith-woocommerce-stripe',
	'yith-woocommerce-subscription',
	'yith-woocommerce-surveys',
	'yith-woocommerce-tab-manager',
	'yith-woocommerce-terms-conditions-popup',
	'yith-woocommerce-waiting-list',
	'yith-woocommerce-watermark',
	'yith-woocommerce-wishlist',
	'yith-woocommerce-zoom-magnifier',
	'yith-wordpress-test-environment',
	'yith-wordpress-title-bar-effects',
];

$licence = get_option( 'yit_plugin_licence_activation' );

foreach ( $plugins_list as $plugin ) {
	$licence[ $plugin ] = [
		'email'                => 'nulled@null.market',
		'licence_key'          => 'nulled',
		'licence_expires'      => '2051222400',
		'message'              => '',
		'activated'            => true,
		'activation_limit'     => 0,
		'activation_remaining' => 0,
		'is_membership'        => true,
		'status_code'          => 200,
	];
}

update_option( 'yit_plugin_licence_activation', $licence );
delete_site_transient( 'update_plugins' );

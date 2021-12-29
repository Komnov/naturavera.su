<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

$custom_attributes = defined( 'YITH_WCBM_PREMIUM' ) ? '' : array( 'disabled' => 'disabled' );

// Create Array for badge select
$badge_array = array(
		'none' => __( 'None', 'yith-woocommerce-badges-management' )
);

global $sitepress;
$current_language = '';
if ( isset( $sitepress ) ) {
	$current_language = $sitepress->get_current_language();
	$default_language = $sitepress->get_default_language();
	$sitepress->switch_lang( $default_language );
}

$args   = array(
		'posts_per_page' => -1,
		'post_type'      => 'yith-wcbm-badge',
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
		'suppress_filters' => false
);
$badges = get_posts( $args );
foreach ( $badges as $badge ) {
	$badge_array[ $badge->ID ] = get_the_title( $badge->ID );
}

if ( isset( $sitepress ) ) {
	$sitepress->switch_lang( $current_language );
}


// get shipping classes
$shipping_classes = get_terms( 'product_shipping_class', array(
		'orderby' => 'name',
		'hide_empty' => false
) );

$options = array(
	'shipping-class-badge-options' => array(
		'title' => __( 'Shipping Class Badges', 'yith-woocommerce-badges-management' ),
		'type' => 'title',
		'desc' => '',
		'id' => 'yith-wcbm-shipping-class-badge-options'
	)
);

foreach ($shipping_classes as $shipping_class) {
	$id 	= $shipping_class->term_id;
	$name 	= $shipping_class->name;

	$options['shipping-class-badge-' . $id ] = array(
		'name'              => $name,
		'type'              => 'select',
		'desc'              => sprintf( __( 'Select the Badge for all products of shipping class "%s"', 'yith-woocommerce-badges-management' ), $name) ,
		'id'                => 'yith-wcbm-shipping-class-badge-' . $id,
		'options'           => $badge_array,
		'custom_attributes' => $custom_attributes,
		'default'           => 'none'
	);
}

$options['shipping-class-badge-options-end'] = array(
	'type'      => 'sectionend',
	'id'        => 'yith-wcbm-shipping-class-badge-options'
);

$settings = array(
	'shipping-class'  => $options
);

return $settings;
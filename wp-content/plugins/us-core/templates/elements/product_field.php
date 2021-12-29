<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * WooCommerce Product data
 */

global $product;
if ( ! class_exists( 'woocommerce' ) OR ! $product OR $us_elm_context == 'grid_term' ) {
	return;
}

$classes = isset( $classes ) ? $classes : '';
$classes .= isset( $type ) ? ( ' ' . $type ) : '';
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) AND $us_elm_context == 'shortcode' ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

// Get product data value
$value = '';
$before_attr_value = '<span class="woocommerce-product-attributes-item__value">';
$after_attr_value = '</span>';

// Price
if ( $type == 'price' ) {
	$value .= $product->get_price_html();

	// SKU
} elseif ( $type == 'sku' AND $product->get_sku() ) {
	$classes .= ' product_meta';
	$value .= '<span class="w-post-elm-before">' . us_translate( 'SKU', 'woocommerce' ) . ': </span>';
	$value .= '<span class="sku">' . $product->get_sku() . '</span>';

	// Rating
} elseif ( $type == 'rating' AND get_option( 'woocommerce_enable_reviews', 'yes' ) === 'yes' ) {
	$value .= wc_get_rating_html( $product->get_average_rating() );

	// SALE badge
} elseif ( $type == 'sale_badge' AND $product->is_on_sale() ) {
	$classes .= ' onsale';
	$value .= strip_tags( $sale_text );

	// Weight
} elseif ( $type == 'weight' AND $product->has_weight() ) {
	$classes .= ' woocommerce-product-attributes-item--' . $type;
	$value .= '<span class="w-post-elm-before">' . us_translate( 'Weight', 'woocommerce' ) . ': </span>';
	$value .= $before_attr_value . esc_html( wc_format_weight( $product->get_weight() ) ) . $after_attr_value;

	// Dimensions
} elseif ( $type == 'dimensions' AND $product->has_dimensions() ) {
	$classes .= ' woocommerce-product-attributes-item--' . $type;
	$value .= '<span class="w-post-elm-before">' . us_translate( 'Dimensions', 'woocommerce' ) . ': </span>';
	$value .= $before_attr_value . esc_html( wc_format_dimensions( $product->get_dimensions( FALSE ) ) ) . $after_attr_value;

	// Custom Product Attributes (set on single product edit)
} elseif ( $type == 'custom_atts' ) {
	$custom_attributes = $product->get_attributes();
	foreach ( $custom_attributes as $key => $product_attr ) {
		$data_attr = $product_attr->get_data();
		if ( $data_attr['is_visible'] ) {
			$value .= '<span class="w-post-elm-before">' . $data_attr['name'] . ': </span>';
			$value .= $before_attr_value . esc_html( implode( ', ', $data_attr['options'] ) ) . $after_attr_value . '<br>';
		}
	}

	// Global Product Attributes
} elseif ( $product_attribute_values = wc_get_product_terms( $product->get_id(), $type, array( 'fields' => 'names' ) ) ) {
	$classes .= ' woocommerce-product-attributes-item--' . $type;
	$value .= '<span class="w-post-elm-before">' . wc_attribute_label( $type ) . ': </span>';
	$value .= $before_attr_value . implode( ', ', $product_attribute_values ) . $after_attr_value;

	// WooCommerce Default Actions for plugins compatibility
} elseif ( $type == 'default_actions' ) {
	if ( $us_elm_context == 'shortcode' ) {

		// Remove default actions because the will be added as separate elements
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3 );

		do_action( 'woocommerce_single_product_summary' );
	} else {
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

		do_action( 'woocommerce_after_shop_loop_item_title' );
		do_action( 'woocommerce_after_shop_loop_item' );
	}

	return;
}

// Prepare inline CSS for shortcode
$inline_css = '';
if ( $us_elm_context == 'shortcode' ) {
	$inline_css .= us_prepare_inline_css(
		array(
			'font-family' => $font,
			'font-weight' => $font_weight,
			'text-transform' => $text_transform,
			'font-style' => $font_style,
			'font-size' => $font_size,
			'line-height' => $line_height,
		), TRUE
	);
}

// Output the element
$output = '<div class="w-post-elm product_field' . $classes . '"';
$output .= $el_id . $inline_css;
$output .= '>';
$output .= $value;
$output .= '</div>';

if ( $value != '' ) {
	echo $output;
}

<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output search element
 *
 * @var $text           string Placeholder Text
 * @var $layout         string Layout: 'simple' / 'modern' / 'fulwidth' / 'fullscreen'
 * @var $width          int Field width
 * @var $design_options array
 * @var $product_search bool Whether to search for WooCommerce products only
 * @var $classes        string
 * @var $id             string
 */

$classes = isset( $classes ) ? $classes : '';
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$classes .= ' layout_' . $layout;
$classes .= ( us_get_option( 'ripple_effect', FALSE ) ) ? ' with_ripple' : '';

$output = '<div class="w-search' . $classes . '">';
if ( $layout == 'fullscreen' ) {
	$output .= '<div class="w-search-background"></div>';
}
$output .= '<a class="w-search-open" href="javascript:void(0);" aria-label="' . us_translate( 'Search' ) . '">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon );
}
$output .= '</a>';
$output .= '<div class="w-search-form">';
$output .= '<form class="w-form-row for_text" autocomplete="off" action="' . esc_attr( home_url( '/' ) ) . '" method="get">';
$output .= '<div class="w-form-row-field">';
$output .= '<input type="text" name="s" id="us_form_search_s" placeholder="' . esc_attr( $text ) . '" aria-label="' . esc_attr( $text ) . '"/>';
if ( ! empty( $product_search ) AND $product_search ) {
	$output .= '<input type="hidden" name="post_type" value="product" />';
}
$output .= '</div>';
if ( $layout == 'simple' ) {
	$output .= '<button class="w-search-form-btn" type="submit" aria-label="' . us_translate( 'Search' ) . '">';
	if ( ! empty( $icon ) ) {
		$output .= us_prepare_icon_tag( $icon );
	}
	$output .= '</button>';
}
// Language code
if ( defined( 'ICL_LANGUAGE_CODE' ) AND ICL_LANGUAGE_CODE != '' ) {
	$output .= '<input type="hidden" name="lang" value="' . esc_attr( ICL_LANGUAGE_CODE ) . '" />';
}
$output .= '<a class="w-search-close" href="javascript:void(0);" aria-label="' . us_translate( 'Close' ) . '"></a>';
$output .= '</form></div></div>';

echo $output;

<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output text element
 *
 * @var $text           string
 * @var $size           int Text size
 * @var $size_tablets   int Text size for tablets
 * @var $size_mobiles   int Text size for mobiles
 * @var $link           string Link
 * @var $icon           string FontAwesome or Material icon
 * @var $font           string Font Source
 * @var $color          string Custom text color
 * @var $design_options array
 * @var $classes        string
 * @var $id             string
 */

$classes = isset( $classes ) ? $classes : '';
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';

$output = '<div class="w-text' . $classes . '">';

// Link
if ( $link_type === 'none' ) {
	$link_atts = '';
} elseif ( $link_type === 'post' ) {

	// Terms of selected taxonomy in Grid
	if ( $us_elm_context == 'grid_term' ) {
		global $us_grid_term;
		$link_atts = ' href="' . get_term_link( $us_grid_term ) . '"';
	} else {
		$link_atts = ' href="' . apply_filters( 'the_permalink', get_permalink() ) . '"';
	}

} elseif ( $link_type === 'elm_value' AND ! empty( $text ) ) {
	if ( filter_var( $text, FILTER_VALIDATE_EMAIL ) ) {
		$link_atts = ' href="mailto:' . $text . '"';
	} else {
		$link_atts = ' href="' . esc_url( $text ) . '"';
	}
} elseif ( $link_type === 'custom' ) {
	$link_atts = us_generate_link_atts( $link );
} else {
	$link_atts = us_generate_link_atts( 'url:{{' . $link_type . '}}|||' );
}
if ( ! empty( $link_atts ) ) {
	$output .= '<a class="w-text-h"' . $link_atts . '>';
} else {
	$output .= '<div class="w-text-h">';
}

if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon );
}
$output .= '<span class="w-text-value">' . strip_tags( $text, '<br>' ) . '</span>';

if ( ! empty( $link_atts ) ) {
	$output .= '</a>';
} else {
	$output .= '</div>';
}
$output .= '</div>';

echo $output;

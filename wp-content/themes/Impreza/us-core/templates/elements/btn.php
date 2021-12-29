<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Button element
 */

// Default variables
$output = $wrapper_classes = $wrapper_inline_css = $btn_inline_css = $responsive_css = '';

// Check existence of Button Style, if not, set the default
$btn_styles = us_get_btn_styles();
if ( ! array_key_exists( $style, $btn_styles ) ) {
	$style = '1';
}

// Button classes & inline styles
$btn_classes = 'w-btn us-btn-style_' . $style;
$btn_classes .= isset( $classes ) ? $classes : '';
$btn_classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';

$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

if ( $us_elm_context == 'shortcode' ) {

	$wrapper_classes .= ' width_' . $width_type;
	if ( $width_type != 'full' ) {
		$wrapper_classes .= ' align_' . $align;
	}
	if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$wrapper_classes .= ' ' . vc_shortcode_custom_css_class( $css );
	}

	$wrapper_inline_css = us_prepare_inline_css(
		array(
			'width' => ( $width_type == 'custom' AND $align != 'center' ) ? $custom_width : NULL,
			'max-width' => ( $width_type == 'max' AND $align != 'center' ) ? $custom_width : NULL,
		)
	);

	$btn_inline_css = us_prepare_inline_css(
		array(
			'font-size' => $font_size,
			'width' => ( $width_type == 'custom' AND $align == 'center' ) ? $custom_width : NULL,
			'max-width' => ( $width_type == 'max' AND $align == 'center' ) ? $custom_width : NULL,
		)
	);
	if ( ! empty( $font_size_mobiles ) ) {
		global $us_btn_index;
		$us_btn_index = isset( $us_btn_index ) ? ( $us_btn_index + 1 ) : 1;
		$btn_classes .= ' us_btn_' . $us_btn_index;
		$responsive_css = '<style>@media(max-width:600px){.us_btn_' . $us_btn_index . '{font-size:' . $font_size_mobiles . '!important}}</style>';
	}
}

// Icon
$icon_html = '';
if ( ! empty( $icon ) ) {
	$icon_html = us_prepare_icon_tag( $icon );
	$btn_classes .= ' icon_at' . $iconpos;
}
if ( is_rtl() ) { // swap icon position for RTL
	$iconpos = ( $iconpos == 'left' ) ? 'right' : 'left';
}

// Text
$text = trim( strip_tags( $label, '<br>' ) );
if ( $text == '' ) {
	$btn_classes .= ' text_none';
}

// Link
if ( $link_type === 'none' ) {
	$link_atts = ' href="javascript:void(0)"';
} elseif ( $link_type === 'post' ) {

	// Terms of selected taxonomy in Grid
	if ( $us_elm_context == 'grid_term' ) {
		global $us_grid_term;
		$link_atts = ' href="' . get_term_link( $us_grid_term ) . '"';
	} else {
		$link_atts = ' href="' . apply_filters( 'the_permalink', get_permalink() ) . '"';
		// Force opening in a new tab for "Link" post format
		if ( get_post_format() == 'link' ) {
			$link_atts .= ' target="_blank" rel="noopener"';
		}
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

// Don't show the button, if it doesn't have a link
if ( empty( $link_atts ) ) {
	return;
}

// Output the element
if ( $us_elm_context == 'shortcode' ) {
	$output .= '<div class="w-btn-wrapper' . $wrapper_classes . '"' . $wrapper_inline_css . '>';
	$output .= $responsive_css;
}
$output .= '<a class="' . $btn_classes . '"';
$output .= $link_atts . $btn_inline_css . $el_id;
$output .= '>';

if ( $iconpos == 'left' ) {
	$output .= $icon_html;
}
if ( $text != '' ) {
	$output .= '<span class="w-btn-label">' . $text . '</span>';
}
if ( $iconpos == 'right' ) {
	$output .= $icon_html;
}
$output .= '</a>';
if ( $us_elm_context == 'shortcode' ) {
	$output .= '</div>';
}

echo $output;

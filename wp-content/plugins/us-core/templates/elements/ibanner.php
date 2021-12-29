<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Interactive Banner
 *
 * @var $image string Image ID
 * @var $size string Image size
 * @var $title string
 * @var $desc string description field
 * @var $link string
 * @var $font string
 * @var $tag string Title HTML tag
 * @var $align string
 * @var $animation string
 * @var $easing string
 * @var $ratio  string Aspect ratio: '2x1' / '3x2' / '4x3' / '1x1' / '3x4' / '2x3' / '1x2'
 * @var $desc_size string
 */

$classes = ' animation_' . $animation;
$classes .= ' align_' . $align;
$classes .= ' ratio_' . $ratio;
$classes .= ' easing_' . $easing;

if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

// Generate common inline styles
$inline_css = us_prepare_inline_css(
	array(
		'font-size' => $desc_font_size,
		'line-height' => $desc_line_height,
		'background' => $bgcolor,
		'color' => $textcolor,
	)
);

$title_inline_css = us_prepare_inline_css(
	array(
		'font-size' => $title_size,
	)
);

$image_url = wp_get_attachment_image_url( $image, $size );
if ( empty( $image_url ) ) {
	$image_url = us_get_img_placeholder( $size, TRUE );
}

// Output the element
$output = '<div class="w-ibanner' . $classes . '"';
$output .= $el_id . $inline_css;
$output .= '>';
$output .= '<div class="w-ibanner-h">';

// Banner Image
$output .= '<div class="w-ibanner-image" style="background-image: url(' . esc_url( $image_url ) . ')"></div>';

$output .= '<div class="w-ibanner-content"><div class="w-ibanner-content-h">';

// Banner Title
$output .= '<' . $title_tag . ' class="w-ibanner-title"' . $title_inline_css . '>';
$output .= strip_tags( $title, '<strong><br>' );
$output .= '</' . $title_tag . '>';

// Banner Description
$output .= '<div class="w-ibanner-desc">' . wpautop( $desc ) . '</div>';

$output .= '</div></div></div>';

// Banner link
if ( $link_atts = us_generate_link_atts( $link ) ) {
	$output .= '<a' . $link_atts . '></a>';
}

$output .= '</div>';

echo $output;

<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_separator
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 *
 * @param $title         string Progress Bar title
 * @param $count         int Progress Bar length in percents: '0' - '100'
 * @param $style         string Style: '1' / '2' / '3' / '4' / '5'
 * @param $size             string Height
 * @param $color         string Color style: 'primary' / 'secondary' / 'heading' / 'text' / 'custom'
 * @param $bar_color     string
 * @param $hide_count     bool Hide progress value counter?
 */

$classes = '';

$classes .= ' style_' . $style;
$classes .= ' color_' . $color;
if ( $hide_count ) {
	$classes .= ' hide_count';
}
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

if ( $title != '' ) {
	$title_string = '<span class="w-progbar-title-text">' . $title . '</span>';
} else {
	$title_string = '';
	$classes .= ' title_none';
}

$count = max( 0, min( 100, $count ) );

$bar_inline_css = us_prepare_inline_css(
	array(
		'height' => $size,
		'width' => $count . '%',
		'background' => $bar_color,
	)
);

$title_inline_css = us_prepare_inline_css(
	array(
		'font-size' => $title_size,
	)
);

// Output the element
$output = '<div class="w-progbar' . $classes . ' initial"' . $el_id . ' data-count="' . $count . '">';
$output .= '<' . $title_tag . ' class="w-progbar-title"' . $title_inline_css . '>';
$output .= $title_string;
$output .= '<span class="w-progbar-title-count">' . $count . '%</span>';
$output .= '</' . $title_tag . '>';
$output .= '<div class="w-progbar-bar"><div class="w-progbar-bar-h"' . $bar_inline_css . '>';
$output .= '<span class="w-progbar-bar-count">' . $count . '%</span>';
$output .= '</div></div></div>';

// If we are in front end editor mode, apply JS to logos
if ( function_exists( 'vc_is_page_editable' ) AND vc_is_page_editable() ) {
	$output .= '<script>
	jQuery(function($){
		if (typeof $.fn.wProgbar === "function") {
			jQuery(".w-progbar").wProgbar();
		}
	});
	</script>';
}

echo $output;

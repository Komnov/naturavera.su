<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Prev/Next navigation
 */

// Cases when the element shouldn't be shown
if ( $us_elm_context == 'grid_term' ) {
	return;
} elseif ( $us_elm_context == 'shortcode' AND is_archive() ) {
	return;
}

$prevnext = us_get_post_prevnext( $invert, $in_same_term, $taxonomy );

if ( empty( $prevnext ) ) {
	return;
}

$classes = ' layout_' . $layout;
$classes .= ( $invert ) ? ' inv_true' : ' inv_false';
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) AND $us_elm_context == 'shortcode' ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

if ( ! isset( $size ) OR trim( $size ) == us_get_option( 'body_fontsize', '16px' ) ) {
	$size = '';
}
$inline_css = us_prepare_inline_css(
	array(
		'font-size' => $size,
	)
);

// Output the element
$output = '<div class="w-post-elm post_navigation' . $classes . '"' . $el_id . $inline_css . '>';

$item_order = 'first';

foreach ( $prevnext as $key => $item ) {

	if ( ! empty( $prevnext[ $key ] ) ) {
		$tnail_id = get_post_thumbnail_id( $item['id'] );
		if ( $tnail_id ) {
			$image = wp_get_attachment_image( $tnail_id, 'thumbnail' );
		}
		if ( ! $tnail_id OR empty( $image ) ) {
			$image = us_get_img_placeholder( 'thumbnail' );
		}

		$output .= '<a class="post_navigation-item order_' . $item_order . ' to_' . $key . '"';
		$output .= ' href="' . esc_url( $item['link'] ) . '" title="' . esc_attr( $item['title'] ) . '">';
		if ( $layout == 'sided' ) {
			$output .= '<div class="post_navigation-item-img">' . $image . '</div>';
		}
		$output .= '<div class="post_navigation-item-arrow"></div>';
		if ( $layout == 'simple' ) {
			$output .= '<div class="post_navigation-item-meta">' . $item['meta'] . '</div>';
		}
		$output .= '<div class="post_navigation-item-title"><span>' . $item['title'] . '</span></div>';
		$output .= '</a>';
	} else {
		$output .= '<div class="post_navigation-item order_' . $item_order . ' to_' . $key . '"></div>';
	}

	$item_order = 'second';
}

$output .= '</div>';

echo $output;

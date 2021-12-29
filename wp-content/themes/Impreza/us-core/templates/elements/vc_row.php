<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: vc_row
 *
 * Overloaded by UpSolution custom implementation to allow creating fullwidth sections and provide lots of additional
 * features.
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var $shortcode               string Current shortcode name
 * @var $shortcode_base          string The original called shortcode name (differs if called an alias)
 * @var $content                 string Shortcode's inner content
 * @var $content_placement       string Columns Content Position: 'top' / 'middle' / 'bottom'
 * @var $gap                     string gap class for columns
 * @var $height                  string Height type. Possible values: 'default' / 'small' / 'medium' / 'large' / 'huge' / 'auto' /  'full'
 * @var $valign                  string Vertical align for full-height sections: '' / 'center'
 * @var $width                   string Section width: '' / 'full'
 * @var $color_scheme            string Color scheme: '' / 'alternate' / 'primary' / 'secondary' / 'custom'
 * @var $us_bg_color             string
 * @var $us_text_color           string
 * @var $us_bg_image_source      string Background image source: 'none' / 'media' / 'featured' / 'custom'
 * @var $us_bg_image             int Background image ID (from WordPress media)
 * @var $us_bg_size              string Background size: 'cover' / 'contain' / 'initial'
 * @var $us_bg_repeat            string Background size: 'repeat' / 'repeat-x' / 'repeat-y' / 'no-repeat'
 * @var $us_bg_pos               string Background position: 'top left' / 'top center' / 'top right' / 'center left' / 'center center' / 'center right' /  'bottom left' / 'bottom center' / 'bottom right'
 * @var $us_bg_parallax          string Parallax type: '' / 'vertical' / 'horizontal' / 'still'
 * @var $us_bg_parallax_width    string Parallax background width: '110' / '120' / '130' / '140' / '150'
 * @var $us_bg_parallax_reverse  bool Reverse vertival parllax effect?
 * @var $us_bg_video             string Link to video file
 * @var $us_bg_overlay_color     string
 * @var $sticky                  bool Fix this row at the top of a page during scroll
 * @var $sticky_disable_width    int When screen width is less than this value, sticky row becomes not sticky
 * @var $el_id                   string
 * @var $el_class                string
 * @var $disable_element         string
 * @var $css                     string
 * @var $us_shape                string Shape Divider type: 'curve' / 'triangle'
 * @var $us_shape_position       string Shape Divider position: 'top' / 'bottom'
 * @var $us_shape_color          string Shape Divider color
 * @var $us_shape_height         string Shape Divider height in pixels
 * @var $us_shape_flip           string Sape Divider invert layout
 *
 * @var $us_shape_bring_to_front string Bring to front element
 */

$atts = us_shortcode_atts( $atts, $shortcode_base );

// .l-section container additional classes and inner CSS-styles
$classes = $inline_css = $inner_inline_css = '';

if ( $disable_element === 'yes' ) {
	if ( function_exists( 'vc_is_page_editable' ) AND vc_is_page_editable() ) {
		$classes .= ' vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( $height == 'default' ) {
	$classes .= ' height_' . us_get_option( 'row_height', 'medium' );
} else {
	$classes .= ' height_' . $height;
}
if ( $height == 'full' AND ! empty( $valign ) ) {
	$classes .= ' valign_' . $valign;
}
if ( $width == 'full' ) {
	$classes .= ' width_full';
}
if ( $color_scheme != '' ) {
	$classes .= ' color_' . $color_scheme;
	if ( $color_scheme == 'custom' ) {
		// Custom colors
		if ( $us_bg_color != '' ) {
			$inline_css .= 'background: ' . $us_bg_color . ';';
		}
		if ( $us_text_color != '' ) {
			$inline_css .= ' color: ' . $us_text_color . ';';
		}
	}
}
if ( $sticky == 1 ) {
	$classes .= ' type_sticky';
}
if ( ! empty( $el_class ) ) {
	$classes .= ' ' . $el_class;
}

// Background Image
$bg_image_html = $bg_image_url = $bg_img_atts = '';

if ( $us_bg_image_source == 'media' ) {
	if ( is_numeric( $us_bg_image ) ) {
		if ( $image_src = wp_get_attachment_image_src( $us_bg_image, 'full' ) ) {
			$bg_image_url = $image_src[0];
			$bg_img_atts .= ' data-img-width="' . esc_attr( $image_src[1] ) . '" data-img-height="' . esc_attr( $image_src[2] ) . '"';
		}
	} else {
		$bg_image_url = $us_bg_image;
	}

	// Use placeholder, if the specified image doesn't exist
	if ( ! empty( $us_bg_image ) AND empty( $bg_image_url ) ) {
		$bg_image_url = us_get_img_placeholder( 'full', TRUE );
	}
}

if ( $us_bg_image_source == 'featured' AND ( isset( $GLOBALS['post'] ) OR is_404() OR is_search() OR is_archive() OR ( is_home() AND ! have_posts() ) ) ) {
	$us_layout = US_Layout::instance();
	if ( ! empty( $us_layout->post_id ) ) {
		$image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $us_layout->post_id ), 'full' );

		// Get WooCommerce Product Category term image
	} elseif ( class_exists( 'woocommerce' ) AND is_product_category() ) {

		if ( $term_thumbnail_id = get_term_meta( get_queried_object_id(), 'thumbnail_id', TRUE ) ) {
			$image_src = wp_get_attachment_image_src( $term_thumbnail_id, 'full' );
		}
	}
	if ( isset( $image_src ) AND $image_src ) {
		$bg_image_url = $image_src[0];
		$bg_img_atts .= ' data-img-width="' . esc_attr( $image_src[1] ) . '" data-img-height="' . esc_attr( $image_src[2] ) . '"';
	}
}

if ( ! empty( $bg_image_url ) ) {
	$classes .= ' with_img';
	$bg_image_inline_css = 'background-image: url(' . esc_url( $bg_image_url ) . ');';
	if ( $us_bg_pos != 'center center' ) {
		$bg_image_inline_css .= 'background-position: ' . $us_bg_pos . ';';
	}
	if ( $us_bg_repeat != 'repeat' ) {
		$bg_image_inline_css .= 'background-repeat: ' . $us_bg_repeat . ';';
	}
	if ( $us_bg_size == 'initial' ) {
		$bg_image_inline_css .= 'background-size: auto;'; // fix for IE11, which doesn't support "background-size: initial"
	} elseif ( $us_bg_size != 'cover' ) {
		$bg_image_inline_css .= 'background-size: ' . $us_bg_size . ';';
	}
	$bg_image_additional_class = ( function_exists( 'vc_is_page_editable' ) AND vc_is_page_editable() ) ? ' loaded' : '';
	$bg_image_html = '<div class="l-section-img' . $bg_image_additional_class . '" style="' . $bg_image_inline_css . '"' . $bg_img_atts . '></div>';
}

// Background Video
$bg_video_html = '';
if ( $us_bg_video != '' ) {
	$classes .= ' with_video';
	$provider_matched = FALSE;
	$bg_video_html = '<div class="l-section-video">';
	foreach ( us_config( 'embeds' ) as $provider => $embed ) {
		if ( $embed['type'] != 'video' OR ! preg_match( $embed['regex'], $us_bg_video, $matches ) ) {
			continue;
		}
		$provider_matched = TRUE;
		$video_id = $matches[ $embed['match_index'] ];
		if ( $provider == 'youtube' ) {
			$classes .= ' with_youtube';
			$video_title = '?autoplay=1&loop=1&playlist=' . $video_id . '&controls=0&mute=1&iv_load_policy=3&disablekb=1&wmode=transparent';
		} elseif ( $provider == 'vimeo' ) {
			$classes .= ' with_vimeo';
			$video_title = '&autoplay=1&loop=1&muted=1&title=0&byline=0&background=1';
		}
		$embed_html = str_replace( '<id>', $matches[ $embed['match_index'] ], $embed['html'] );
		$embed_html = str_replace( '<video-title>', $video_title, $embed_html );
		break;
	}
	if ( $provider_matched ) {
		$bg_video_html .= $embed_html;
	} else {
		$bg_video_html .= '<video muted loop autoplay preload="auto">';
		$video_ext = 'mp4'; //use mp4 as default extension
		$file_path_info = pathinfo( $us_bg_video );
		if ( isset( $file_path_info['extension'] ) ) {
			if ( in_array( $file_path_info['extension'], array( 'ogg', 'ogv' ) ) ) {
				$video_ext = 'ogg';
			} elseif ( $file_path_info['extension'] == 'webm' ) {
				$video_ext = 'webm';
			}
		}
		$bg_video_html .= '<source type="video/' . $video_ext . '" src="' . $us_bg_video . '" />';
		$bg_video_html .= '</video>';
	}
	$bg_video_html .= '</div>';
} else {
	if ( $us_bg_parallax == 'vertical' ) {
		$classes .= ' parallax_ver';
		if ( $us_bg_parallax_reverse ) {
			$classes .= ' parallaxdir_reversed';
		}
		if ( in_array( $us_bg_pos, array( 'top right', 'center right', 'bottom right' ) ) ) {
			$classes .= ' parallax_xpos_right';
		} elseif ( in_array( $us_bg_pos, array( 'top left', 'center left', 'bottom left' ) ) ) {
			$classes .= ' parallax_xpos_left';
		}
	} elseif ( $us_bg_parallax == 'fixed' OR $us_bg_parallax == 'still' ) {
		$classes .= ' parallax_fixed';
	} elseif ( $us_bg_parallax == 'horizontal' ) {
		$classes .= ' parallax_hor';
		$classes .= ' bgwidth_' . $us_bg_parallax_width;
	}
}

// Background Slider
$bg_slider_html = '';
if ( class_exists( 'RevSlider' ) AND $us_bg_slider != '' ) {
	$classes .= ' with_slider';
	$bg_slider_html = '<div class="l-section-slider">' . do_shortcode( '[rev_slider ' . $us_bg_slider . ']' ) . '</div>';
}

// Background Overlay
$bg_overlay_html = '';
if ( ! empty( $us_bg_overlay_color ) ) {
	$classes .= ' with_overlay';
	$bg_overlay_html = '<div class="l-section-overlay" style="background: ' . $us_bg_overlay_color . '"></div>';
}

// We cannot use VC's method directly for rows: as it uses !important values, so we're moving the defined css
// that don't duplicate the theme's features to inline style attribute.
if ( ! empty( $css ) AND preg_match( '~\{([^\}]+?)\;?\}~', $css, $matches ) ) {
	$vc_css_rules = array_map( 'trim', explode( ';', $matches[1] ) );
	$overloaded_params = array(
		'background',
		'background-position',
		'background-repeat',
		'background-size',
		'padding-top',
		'padding-bottom',
	);
	$inner_params = array(
		'padding-top',
		'padding-bottom',
	);
	foreach ( $vc_css_rules as $vc_css_rule ) {
		$vc_css_rule = explode( ':', $vc_css_rule );
		// Generate inline styles for "l-section"
		if ( count( $vc_css_rule ) == 2 AND ! in_array( $vc_css_rule[0], $overloaded_params ) ) {
			$inline_css .= $vc_css_rule[0] . ':' . $vc_css_rule[1] . ';';
		}
		// Generate inline styles for "l-section-h"
		if ( count( $vc_css_rule ) == 2 AND in_array( $vc_css_rule[0], $inner_params ) ) {
			$inner_inline_css .= $vc_css_rule[0] . ':' . $vc_css_rule[1] . ';';
		}
	}
}

$classes = apply_filters( 'vc_shortcodes_css_class', $classes, $shortcode_base, $atts );
if ( function_exists( 'vc_is_page_editable' ) AND vc_is_page_editable() ) {
	$classes .= ' vc_row';
}

// Shape Divider
$bg_shape_html = '';
if ( $us_shape !== 'none' ) {
	$classes .= ' with_shape';
	$svg = '';

	// Load SVG file
	$svg_filepath = sprintf( '%s/assets/shapes/%s.svg', US_CORE_DIR, $us_shape );
	if ( $svg_filepath = realpath( $svg_filepath ) ) {
		ob_start();
		include( $svg_filepath );
		$svg = ob_get_clean();
	}

	// CSS Classes
	$shape_classes = array(
		'l-section-shape',
		'type_' . esc_attr( $us_shape ),
		'pos_' . esc_attr( $us_shape_position ),
	);
	if ( $us_shape_overlap ) {
		$shape_classes[] = 'on_front';
	}
	if ( $us_shape_flip ) {
		$shape_classes[] = 'hor_flip';
	}

	// Height and color
	$defaults_atts = us_config( 'shortcodes.modified.vc_row.atts', array() );
	$svg_inline_css = us_prepare_inline_css(
		array(
			'height' => ( $us_shape_height === $defaults_atts['us_shape_height'] ) ? '' : $us_shape_height,
			'color' => ( $us_shape_color === $defaults_atts['us_shape_color'] ) ? '' : $us_shape_color,
		)
	);

	$bg_shape_html .= '<div class="' . implode( ' ', $shape_classes ) . '"';
	$bg_shape_html .= $svg_inline_css;
	$bg_shape_html .= '>';
	$bg_shape_html .= $svg;
	$bg_shape_html .= '</div>';
}

// Output the element
$output = '<section class="l-section wpb_row' . $classes . '"';
if ( ! empty( $el_id ) ) {
	$output .= ' id="' . $el_id . '"';
}
if ( ! empty( $inline_css ) ) {
	$output .= ' style="' . $inline_css . '"';
}
if ( $sticky == 1 AND ! empty( $sticky_disable_width ) ) {
	$output .= ' data-sticky-disable-width="' . intval( $sticky_disable_width ) . '"';
}
$output .= '>';

$output .= $bg_image_html;
$output .= $bg_video_html;
$output .= $bg_slider_html;
$output .= $bg_overlay_html;
$output .= $bg_shape_html;

$output .= '<div class="l-section-h i-cf"';
if ( ! empty( $inner_inline_css ) ) {
	$output .= ' style="' . $inner_inline_css . '"';
}
$output .= '>';

$inner_output = do_shortcode( $content );

// If the row has no inner rows, preparing wrapper for inner columns
if ( substr( $inner_output, 0, 18 ) != '<div class="g-cols' ) {

	$cols_gap_styles = '';
	$cols_class_name = ( $columns_type ) ? ' type_boxes' : ' type_default';

	if ( ! empty( $content_placement ) ) {
		$cols_class_name .= ' valign_' . $content_placement;
	}
	if ( ! empty( $columns_reverse ) ) {
		$cols_class_name .= ' reversed';
	}

	// Prepare extra styles for columns gap
	$gap = trim( $gap );
	if ( ! empty( $gap ) ) {
		$gap = trim( strip_tags( $gap ) );
		$gap_class = 'gap-' . str_replace( array( '.', ',', ' ' ), '-', $gap );
		$cols_class_name .= ' ' . $gap_class;

		$cols_gap_styles = '<style>';
		if ( $columns_type ) {
			$cols_gap_styles .= '.g-cols.' . $gap_class . '{margin:0 -' . $gap . '}';
		} else {
			$cols_gap_styles .= '.g-cols.' . $gap_class . '{margin:0 calc(-1.5rem - ' . $gap . ')}';
		}
		$cols_gap_styles .= '.' . $gap_class . ' > .vc_column_container {padding:' . $gap . '}';
		$cols_gap_styles .= '</style>';
	}

	$output .= '<div class="g-cols vc_row' . $cols_class_name . '">';
	$output .= $cols_gap_styles . $inner_output;
	$output .= '</div>';
} else {
	$output .= $inner_output;
}

$output .= '</div>';

$output .= '</section>';
if ( $sticky == 1 ) {
	$output .= '<div class="l-section-gap"></div>';
}

echo $output;

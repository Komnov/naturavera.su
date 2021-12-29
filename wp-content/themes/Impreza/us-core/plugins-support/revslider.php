<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Revolution Slider Support
 *
 * @link http://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380?ref=UpSolution
 */

if ( ! class_exists( 'RevSliderFront' ) ) {
	return;
}

if ( function_exists( 'set_revslider_as_theme' ) ) {
	if ( ! defined( 'REV_SLIDER_AS_THEME' ) ) {
		define( 'REV_SLIDER_AS_THEME', TRUE );
	}
	set_revslider_as_theme();
}

// Actually the revslider's code above doesn't work as expected, so turning off the notifications manually
if ( get_option( 'revslider-valid-notice', 'true' ) != 'false' ) {
	update_option( 'revslider-valid-notice', 'false' );
}
if ( get_option( 'revslider-notices', array() ) != array() ) {
	update_option( 'revslider-notices', array() );
}

// Remove notices on "Plugins" admin page
global $pagenow;
if ( $pagenow == 'plugins.php' ) {
	remove_action( 'admin_notices', array( 'RevSliderAdmin', 'add_plugins_page_notices' ) );
}

// Move js for Admin Bar lower so it is not echoed before jquery core in footer
add_action( 'wp_enqueue_scripts', 'us_move_revslider_js_footer' );
function us_move_revslider_js_footer() {
	remove_action( 'wp_footer', array( 'RevSliderFront', 'putAdminBarMenus' ) );
	add_action( 'wp_footer', array( 'RevSliderFront', 'putAdminBarMenus' ), 99 );
}


add_action( 'wp_enqueue_scripts', 'us_include_revslider_js_for_row_bg', 5 );
function us_include_revslider_js_for_row_bg() {
	$isPutIn = FALSE;
	if ( class_exists( 'UniteFunctionsRev' ) ) {
		// Object to access RevSlider functions
		$uniteFunctionsRev = new UniteFunctionsRev;
		if ( method_exists( $uniteFunctionsRev, 'get_global_settings' )
			AND method_exists( $uniteFunctionsRev, 'get_val' )
			AND method_exists( $uniteFunctionsRev, 'check_add_to' ) ) {
			// Get all global settings RevSlider
			$arrValues = (array) $uniteFunctionsRev->get_global_settings();

			/**
			 * Check if RevSlider is enabled globally, then we do nothing
			 * @var string $arrValues ['include']
			 */
			if ( $uniteFunctionsRev->get_val( $arrValues, "include", 'false' ) === 'true' ) {
				return;
			}

			/**
			 * Getting a list of post IDs where RevSlider connects
			 * @var string $arrValues ['includeids']
			 */
			$strPutIn = $uniteFunctionsRev->get_val( $arrValues, "includeids", '' );
			// Check it has the current post element RevSlider
			$revSliderOutput = new RevSliderOutput;
			$isPutIn = $revSliderOutput->check_add_to( $strPutIn, TRUE );
		}
	}
	

	// Search shortcode in content
	if ( $isPutIn === FALSE ) {
		$post_content = '';
		$page_blocks_content = us_get_current_page_block_content();

		if ( is_singular() ) {
			$post = get_post( get_the_ID() );
			$post_content = $post->post_content;
		}

		$has_slider_post_content = ( ! empty( $post_content ) AND stripos( $post_content, 'us_bg_slider=' ) !== FALSE );
		$has_slider_page_blocks_content = ( ! empty( $page_blocks_content )
			AND ( stripos( $page_blocks_content, 'us_bg_slider=' ) !== FALSE
				OR stripos( $page_blocks_content, '[rev_slider' ) !== FALSE ) );

		// If we managed to find rev_slider, then we will connect the libraries
		if ( $has_slider_post_content OR $has_slider_page_blocks_content ) {
			add_filter( 'revslider_include_libraries', '__return_true' );
		}
	}
}

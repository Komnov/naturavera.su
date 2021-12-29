<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Outputs Sidebar HTML
 *
 * @filter Template variables: 'us_template_vars:templates/content'
 */

$sidebar_id = us_get_page_area_id( 'sidebar' );
if ( ! isset( $place ) OR $sidebar_id == '' ) {
	return;
}

// Get Sidebar position for the current page (based on "us_get_page_area_id" function)
$public_cpt = array_keys( us_get_public_cpt() ); // Get public custom post types
$public_taxonomies = array_keys( us_get_taxonomies( TRUE, FALSE, 'woocommerce_exclude' ) ); // Get public taxonomies EXCEPT Products
$product_taxonomies = array_keys( us_get_taxonomies( TRUE, FALSE, 'woocommerce_only' ) ); // Get Products taxonomies ONLY

// Default from Theme Options
$position = $pages_position = us_get_option( 'sidebar_pos', 'right' );

// Portfolio Pages
if ( is_singular( array( 'us_portfolio' ) ) AND us_get_option( 'sidebar_portfolio_id', '__defaults__' ) !== '__defaults__' ) {
	$position = us_get_option( 'sidebar_portfolio_pos', $pages_position );

	// Posts
} elseif ( is_singular( array( 'post', 'attachment' ) ) AND us_get_option('sidebar_post_id', '__defaults__') !== '__defaults__' ) {
	$position = us_get_option( 'sidebar_post_pos', $pages_position );

	// WooCommerce Products
} elseif ( function_exists( 'is_product' ) AND is_product() AND us_get_option( 'sidebar_product_id', '__defaults__' ) !== '__defaults__' ) {
	$position = us_get_option( 'sidebar_product_pos', $pages_position );

	// WooCommerce Shop Page
} elseif ( function_exists( 'is_shop' ) AND is_shop() AND us_get_option( 'sidebar_shop_id', '__defaults__' ) !== '__defaults__' ) {
	$position = us_get_option( 'sidebar_shop_pos', $pages_position );

	// WooCommerce Products Search
} elseif ( class_exists( 'woocommerce' ) AND is_post_type_archive( 'product' ) AND is_search() AND us_get_option( 'sidebar_shop_id', '__defaults__' ) !== '__defaults__' ) {
	$position = us_get_option( 'sidebar_shop_pos', $pages_position );

	// WooCommerce Products Taxonomies
} elseif ( class_exists( 'woocommerce' ) AND is_tax( $product_taxonomies ) ) {
	if ( us_get_option( 'sidebar_shop_id', '__defaults__' ) !== '__defaults__' ) {
		$position = us_get_option( 'sidebar_shop_pos', $pages_position );
	}

	$current_tax = get_query_var( 'taxonomy' );

	if ( us_get_option( 'sidebar_tax_' . $current_tax . '_id', '__defaults__' ) !== '__defaults__' ) {
		$position = us_get_option( 'sidebar_tax_' . $current_tax . '_pos', $pages_position );
	}

	// Custom Post Types
} elseif ( ! empty( $public_cpt ) AND is_singular( $public_cpt ) ) {
	if ( is_singular( array( 'tribe_events' ) ) ) {
		$post_type = 'tribe_events'; // Events Calendar fix
	} else {
		$post_type = get_post_type();
	}
	if ( us_get_option( 'sidebar_' . $post_type . '_id', '__defaults__' ) !== '__defaults__' ) {
		$position = us_get_option( 'sidebar_' . $post_type . '_pos', $pages_position );
	}

	// Archives
} elseif ( is_archive() OR is_search() OR is_tax( $public_taxonomies ) ) {
	$position = $archives_position = us_get_option( 'sidebar_archive_pos', $pages_position );

	if ( is_category() ) {
		$current_tax = 'category';
	} elseif ( is_tag() ) {
		$current_tax = 'post_tag';
	} elseif ( is_tax() ) {
		$current_tax = get_query_var( 'taxonomy' );
	}

	if ( ! empty( $current_tax ) AND us_get_option( 'sidebar_tax_' . $current_tax . '_id', '__defaults__' ) !== '__defaults__' ) {
		$position = us_get_option( 'sidebar_tax_' . $current_tax . '_pos', $archives_position );
	}

	// Author Pages
} elseif ( is_author() AND us_get_option( 'sidebar_author_id', '__defaults__' ) !== '__defaults__' ) {
	$position = us_get_option( 'sidebar_author_pos', $archives_position );
}

// Forums archive page
if ( is_post_type_archive( 'forum' ) AND us_get_option( 'sidebar_forum_pos', '__defaults__' ) !== '__defaults__' ) {
	$position = us_get_option( 'sidebar_forum_pos', $archives_position );
}

// Events calendar archive page
if ( is_post_type_archive( 'tribe_events' ) AND us_get_option( 'sidebar_tax_tribe_events_cat_id', '__defaults__' ) !== '__defaults__' ) {
	$position = us_get_option( 'sidebar_tax_tribe_events_cat_pos', $archives_position );
}

// Search Results page
if ( is_search() AND ! is_post_type_archive( 'product' ) AND $postID = us_get_option( 'search_page', 'default' ) AND $postID !== 'default' ) {
	$position = usof_meta( 'us_sidebar_pos', $postID );
}

// Posts page
if ( is_home() AND $postID = us_get_option( 'posts_page', 'default' ) AND $postID !== 'default' ) {
	$position = usof_meta( 'us_sidebar_pos', $postID );
}

// 404 page
if ( is_404() AND $postID = us_get_option( 'page_404', 'default' ) AND $postID !== 'default' ) {
	$position = usof_meta( 'us_sidebar_pos', $postID );
}

// Specific page
if ( is_singular() ) {
	$postID = get_the_ID();
	if ( $postID AND metadata_exists( 'post', $postID, 'us_sidebar_pos' ) AND usof_meta( 'us_sidebar_id', $postID ) !== '__defaults__' ) {
		$position = usof_meta( 'us_sidebar_pos', $postID );
	}
}

// Generate column for Content area
$content_column_start = '<div class="vc_col-sm-9 vc_column_container l-content">';
$content_column_start .= '<div class="vc_column-inner"><div class="wpb_wrapper">';

// Generate column for Sidebar
$sidebar_column_start = '<div class="vc_col-sm-3 vc_column_container l-sidebar">';
$sidebar_column_start .= '<div class="vc_column-inner"><div class="wpb_wrapper">';

// Outputs HTML regarding place value
if ( $place == 'before' ) {

	echo '<section class="l-section height_auto for_sidebar at_' . $position . '"><div class="l-section-h">';
	echo '<div class="g-cols type_default valign_top">';

	// Content column
	echo $content_column_start;

} elseif ( $place == 'after' ) {

	echo '</div></div></div>';

	// Sidebar column
	echo $sidebar_column_start;

	dynamic_sidebar( $sidebar_id );

	echo '</div></div></div>';
	echo '</div></div></section>';
}

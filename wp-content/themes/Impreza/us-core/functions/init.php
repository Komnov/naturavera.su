<?php

// Upsolution helper functions
require US_CORE_DIR . 'functions/helpers.php';

add_action( 'after_setup_theme', 'uscore_after_setup_theme', 8 );
function uscore_after_setup_theme() {
	if ( ! defined( 'US_THEMENAME' ) ) {
		return;
	}

	// UpSolution Header definitions
	require US_CORE_DIR . 'functions/header.php';

	// Post formats
	require US_CORE_DIR . 'functions/post.php';

	// Theme Options
	require US_CORE_DIR . 'functions/theme-options.php';

	// UpSolution Layout definitions
	require US_CORE_DIR . 'functions/layout.php';

	// Breadcrumbs function
	require US_CORE_DIR . 'functions/breadcrumbs.php';

	// Custom Post types
	require US_CORE_DIR . 'functions/post-types.php';

	// Page Meta Tags
	require US_CORE_DIR . 'functions/meta-tags.php';

	// Sidebars init
	require US_CORE_DIR . 'functions/widget_areas.php';

	// Header builder
	require US_CORE_DIR . 'admin/functions/header-builder.php';

	// Media Categories
	if ( us_get_option( 'media_category' ) ) {
		require US_CORE_DIR . 'functions/media.php';
	}

	// Load shortcodes
	require US_CORE_DIR . 'functions/shortcodes.php';

	// Performing fallback compatibility and migrations when needed
	require US_CORE_DIR . 'functions/migration.php';

	// Widgets
	require US_CORE_DIR . 'functions/widgets.php';

	if ( is_admin() ) {

		// Grid Builder
		require US_CORE_DIR . 'admin/functions/grid-builder.php';

		// Modified Menu edit screen
		require US_CORE_DIR . 'admin/functions/nav-menu-edit.php';

		// Demo Import
		require US_CORE_DIR . 'admin/functions/demo-import.php';

		// Theme Updater
		require US_CORE_DIR . 'admin/functions/theme-updater.php';

		// Customize TinyMCE and Gutenberg editors
		require US_CORE_DIR . 'admin/functions/customize-editors.php';

	} else {

		// Remove protocols from URLs for better compatibility with caching plugins and services if enabled
		global $us_template_directory_uri, $us_stylesheet_directory_uri;
		if ( us_get_option( 'remove_protocol', 0 ) ) {
			$us_template_directory_uri = str_replace( array( 'http:', 'https:' ), '', get_template_directory_uri() );
			$us_stylesheet_directory_uri = str_replace( array( 'http:', 'https:' ), '', get_stylesheet_directory_uri() );
		}

		// Frontent CSS and JS enqueue
		require US_CORE_DIR . 'functions/enqueue.php';
	}

	// AJAX related functions
	if ( defined( 'DOING_AJAX' ) AND DOING_AJAX ) {
		require US_CORE_DIR . 'functions/ajax/header_builder.php';
		require US_CORE_DIR . 'functions/ajax/grid_builder.php';
		require US_CORE_DIR . 'functions/ajax/us_login.php';
		require US_CORE_DIR . 'functions/ajax/grid.php';
		require US_CORE_DIR . 'functions/ajax/cform.php';
		require US_CORE_DIR . 'functions/ajax/cart.php';
	}

	// Enable Text WP widget show shortcodes
	add_filter( 'widget_text', 'do_shortcode' );

}

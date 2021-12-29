<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Assets configuration (JS and CSS components)
 *
 * @filter us_config_assets
 */

return array(

	// Base Components
	'lazy-load' => array(
		'title' => '',
		'js' => '/common/js/vendor/lazyloadxt.js',
		'hidden' => TRUE, // component not visible in UI
		'order' => 'top', // component will be added to the top of generated JS file
	),
	'general' => array(
		'title' => '',
		'css' => '/common/css/base/general.css',
		'js' => '/common/js/base/general.js',
		'hidden' => TRUE, // component not visible in UI
		'order' => 'top', // component will be added to the top of generated JS file
	),
	'animation' => array(
		'title' => __( 'Animation', 'us' ),
		'css' => '/common/css/base/animation.css',
		'js' => '/common/js/base/animation.js',
		'group' => __( 'Base Components', 'us' ),
	),
	'carousel' => array(
		'title' => __( 'Carousel', 'us' ),
		'css' => '/common/css/base/carousel.css',
	),
	'columns' => array(
		'title' => us_translate( 'Columns' ),
		'css' => '/common/css/base/columns.css',
	),
	'comments' => array(
		'title' => us_translate( 'Comments' ),
		'css' => '/common/css/base/comments.css',
		'js' => '/common/js/base/comments.js',
	),
	'filters' => array(
		'title' => us_translate( 'Filter' ),
		'css' => '/common/css/base/filters.css',
	),
	'forms' => array(
		'title' => __( 'Forms', 'us' ),
		'css' => '/common/css/base/forms.css',
		'js' => '/common/js/base/forms.js',
	),
	'header' => array(
		'title' => _x( 'Header', 'site top area', 'us' ),
		'css' => '/common/css/base/header.css',
		'js' => '/common/js/base/header.js',
	),
	'parallax-hor' => array(
		'title' => __( 'Horizontal Parallax', 'us' ),
		'js' => '/common/js/base/parallax-hor.js',
	),
	'pagination' => array(
		'title' => us_translate( 'Pagination' ),
		'css' => '/common/css/base/pagination.css',
	),
	'preloader' => array(
		'title' => __( 'Preloader', 'us' ),
		'css' => '/common/css/base/preloader.css',
		'js' => '/common/js/base/preloader.js',
	),
	'print' => array(
		'title' => __( 'Print styles', 'us' ),
		'css' => '/common/css/base/print.css',
	),
	'popups' => array(
		'title' => __( 'Popups', 'us' ),
		'css' => '/common/css/base/popups.css',
	),
	'scroll' => array(
		'title' => __( 'Scroll events', 'us' ),
		'js' => '/common/js/base/scroll.js',
		'order' => 'top',
	),
	'parallax-ver' => array(
		'title' => __( 'Vertical Parallax', 'us' ),
		'js' => '/common/js/base/parallax-ver.js',
	),
	'font-awesome' => array(
		'title' => sprintf( __( '"%s" icons', 'us' ), 'Font Awesome' ),
		'css' => '/common/css/base/fontawesome.css',
	),
	'font-awesome-duotone' => array(
		'title' => sprintf( __( '"%s" icons', 'us' ), 'Font Awesome Duotone' ),
		'css' => '/common/css/base/fontawesome-duotone.css',
	),

	// Content Elements
	'actionbox' => array(
		'title' => __( 'ActionBox', 'us' ),
		'css' => '/common/css/elements/actionbox.css',
		'group' => __( 'Content Elements', 'us' ),
	),
	'buttons' => array(
		'title' => __( 'Button', 'us' ),
		'css' => '/common/css/elements/buttons.css',
	),
	'charts' => array(
		'title' => __( 'Charts', 'us' ),
		'css' => '/common/css/elements/charts.css',
	),
	'contacts' => array(
		'title' => us_translate( 'Contact Info' ),
		'css' => '/common/css/elements/contacts.css',
	),
	'counter' => array(
		'title' => __( 'Counter', 'us' ),
		'css' => '/common/css/elements/counter.css',
		'js' => '/common/js/elements/counter.js',
	),
	'dropdown' => array(
		'title' => __( 'Dropdown', 'us' ),
		'css' => '/common/css/elements/dropdown.css',
		'js' => '/common/js/elements/dropdown.js',
	),
	'flipbox' => array(
		'title' => __( 'FlipBox', 'us' ),
		'css' => '/common/css/elements/flipbox.css',
		'js' => '/common/js/elements/flipbox.js',
	),
	'gmaps' => array(
		'title' => sprintf( __( '%s Maps', 'us' ), 'Google' ),
		'css' => '/common/css/elements/gmaps.css',
		'js' => '/common/js/elements/gmaps.js',
	),
	'lmaps' => array(
		'title' => sprintf( __( '%s Maps', 'us' ), 'OpenStreetMap' ),
		'css' => '/common/css/vendor/leaflet.css',
		'js' => '/common/js/elements/lmaps.js',
	),
	'grid' => array(
		'title' => __( 'Grid', 'us' ),
		'css' => '/common/css/elements/grid.css',
		'js' => '/common/js/elements/grid.js',
	),
	'gallery' => array(
		'title' => __( 'Image Gallery', 'us' ),
		'css' => '/common/css/elements/gallery.css',
		'js' => '/common/js/elements/gallery.js',
	),
	'slider' => array(
		'title' => __( 'Image Slider', 'us' ),
		'css' => '/common/css/elements/slider.css',
		'js' => '/common/js/elements/slider.js',
	),
	'iconbox' => array(
		'title' => __( 'IconBox', 'us' ),
		'css' => '/common/css/elements/iconbox.css',
	),
	'image' => array(
		'title' => us_translate( 'Image' ),
		'css' => '/common/css/elements/image.css',
	),
	'ibanner' => array(
		'title' => us_translate( 'Interactive Banner' ),
		'css' => '/common/css/elements/ibanner.css',
	),
	'itext' => array(
		'title' => __( 'Interactive Text', 'us' ),
		'css' => '/common/css/elements/itext.css',
		'js' => '/common/js/elements/itext.js',
	),
	'menu' => array(
		'title' => us_translate( 'Menu' ),
		'css' => '/common/css/elements/menu.css',
		'js' => '/common/js/elements/menu.js',
	),
	'message' => array(
		'title' => __( 'Message Box', 'us' ),
		'css' => '/common/css/elements/message.css',
		'js' => '/common/js/elements/message.js',
	),
	'scroller' => array(
		'title' => __( 'Page Scroller', 'us' ),
		'css' => '/common/css/elements/page-scroller.css',
		'js' => '/common/js/elements/page-scroller.js',
	),
	'person' => array(
		'title' => __( 'Person', 'us' ),
		'css' => '/common/css/elements/person.css',
	),
	'popup' => array(
		'title' => __( 'Popup', 'us' ),
		'css' => '/common/css/elements/popup.css',
		'js' => '/common/js/elements/popup.js',
	),
	'pricing' => array(
		'title' => __( 'Pricing Table', 'us' ),
		'css' => '/common/css/elements/pricing.css',
	),
	'progbar' => array(
		'title' => __( 'Progress Bar', 'us' ),
		'css' => '/common/css/elements/progbar.css',
		'js' => '/common/js/elements/progbar.js',
	),
	'search' => array(
		'title' => us_translate( 'Search' ),
		'css' => '/common/css/elements/search.css',
		'js' => '/common/js/elements/search.js',
	),
	'separator' => array(
		'title' => __( 'Separator', 'us' ),
		'css' => '/common/css/elements/separator.css',
	),
	'sharing' => array(
		'title' => __( 'Sharing Buttons', 'us' ),
		'css' => '/common/css/elements/sharing.css',
		'js' => '/common/js/elements/sharing.js',
	),
	'socials' => array(
		'title' => __( 'Social Links', 'us' ),
		'css' => '/common/css/elements/socials.css',
	),
	'tabs' => array(
		'title' => us_translate( 'Tabs', 'js_composer' ) . ', ' . us_translate( 'Tour', 'js_composer' ) . ', ' . us_translate( 'Accordion', 'js_composer' ),
		'css' => '/common/css/elements/tabs.css',
		'js' => '/common/js/elements/tabs.js',
	),
	'video' => array(
		'title' => us_translate( 'Video Player', 'js_composer' ),
		'css' => '/common/css/elements/video.css',
	),

	// Plugins
	'gravityforms' => array(
		'title' => 'Gravity Forms',
		'css' => '/common/css/plugins/gravityforms.css',
		'minify_separately' => TRUE, // component will be minified into a separate file via "US Minify" plugin
		'apply_if' => class_exists( 'GFForms' ),
		'group' => us_translate( 'Plugins' ),
	),
	'tribe-events' => array(
		'title' => 'The Events Calendar',
		'css' => '/common/css/plugins/tribe-events.css',
		'minify_separately' => TRUE,
		'apply_if' => class_exists( 'Tribe__Events__Main' ),
	),
	'ultimate-addons' => array(
		'title' => 'Ultimate Addons',
		'css' => '/common/css/plugins/ultimate-addons.css',
		'js' => '/common/js/plugins/ultimate-addons.js',
		'apply_if' => class_exists( 'Ultimate_VC_Addons' ),
	),
	'bbpress' => array(
		'title' => '',
		'css' => '/common/css/plugins/bbpress.css',
		'minify_separately' => TRUE,
		'hidden' => TRUE,
		'apply_if' => class_exists( 'bbPress' ),
	),
	'slider-revolution' => array(
		'title' => '',
		'css' => '/common/css/plugins/slider-revolution.css',
		'hidden' => TRUE,
		'apply_if' => class_exists( 'RevSliderFront' ),
	),
	'tablepress' => array(
		'title' => '',
		'css' => '/common/css/plugins/tablepress.css',
		'hidden' => TRUE,
		'apply_if' => class_exists( 'TablePress' ),
	),
	'woocommerce' => array(
		'title' => '',
		'css' => '/common/css/plugins/woocommerce.css',
		'js' => '/common/js/plugins/woocommerce.js',
		'minify_separately' => TRUE,
		'hidden' => TRUE,
		'apply_if' => class_exists( 'woocommerce' ),
	),
	'wpml' => array(
		'title' => '',
		'css' => '/common/css/plugins/wpml.css',
		'hidden' => TRUE,
		'apply_if' => class_exists( 'SitePress' ),
	),

	// Theme Customs
	'theme' => array(
		'title' => '',
		'css' => '/css/custom.css',
		'hidden' => TRUE,
	),
);

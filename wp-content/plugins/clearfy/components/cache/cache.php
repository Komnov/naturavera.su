<?php
/**
 * Plugin Name: Webcraftic Clearfy Cache
 * Plugin URI: https://webcraftic.com
 * Description: Webcraftic Clearfy Cache
 * Author: Webcraftic <wordpress.webraftic@gmail.com>
 * Version: 1.0.0
 * Text Domain: clearfy_cache
 * Domain Path: /languages/
 * Author URI: https://webcraftic.com
 * Framework Version: FACTORY_457_VERSION
 */

// Exit if accessed directly
if( !defined('ABSPATH') ) {
	exit;
}

/**
 * Developers who contributions in the development plugin:
 *
 * Alexander Kovalev
 * ---------------------------------------------------------------------------------
 * Full plugin development.
 *
 * Email:         alex.kovalevv@gmail.com
 * Personal card: https://alexkovalevv.github.io
 * Personal repo: https://github.com/alexkovalevv
 * ---------------------------------------------------------------------------------
 */

/**
 * -----------------------------------------------------------------------------
 * CHECK REQUIREMENTS
 * Check compatibility with php and wp version of the user's site. As well as checking
 * compatibility with other plugins from Webcraftic.
 * -----------------------------------------------------------------------------
 */

require_once(dirname(__FILE__) . '/libs/factory/core/includes/class-factory-requirements.php');

// @formatter:off
$wcache_plugin_info = [
	'prefix' => 'wcache_',
	'plugin_name' => 'wcache',
	'plugin_title' => __('Webcraftic Clearfy Cache', 'clearfy_cache'),

	// PLUGIN SUPPORT
	'support_details' => [
		'url' => 'https://webcraftic.com',
		'pages_map' => [
			'support' => 'support',        // {site}/support
			'docs' => 'docs'               // {site}/docs
		]
	],

	// PLUGIN SUBSCRIBE FORM
	'subscribe_widget' => true,
	'subscribe_settings' => ['group_id' => '105408892'],

	// PLUGIN ADVERTS
	'render_adverts' => true,
	'adverts_settings' => [
		'dashboard_widget' => true, // show dashboard widget (default: false)
		'right_sidebar' => true, // show adverts sidebar (default: false)
		'notice' => true, // show notice message (default: false)
	],

	// FRAMEWORK MODULES
	'load_factory_modules' => [
		['libs/factory/bootstrap', 'factory_bootstrap_458', 'admin'],
		['libs/factory/forms', 'factory_forms_454', 'admin'],
		['libs/factory/pages', 'factory_pages_456', 'admin'],
		['libs/factory/clearfy', 'factory_templates_109', 'all'],
		['libs/factory/adverts', 'factory_adverts_134', 'admin']
	]
];

$wcache_compatibility = new Wbcr_Factory457_Requirements(__FILE__, array_merge($wcache_plugin_info, [
	'plugin_already_activate' => defined('WCACHE_ACTIVE'),
	'required_php_version' => '5.4',
	'required_wp_version' => '4.2.0',
	'required_clearfy_check_component' => false
]));

/**
 * If the plugin is compatible, then it will continue its work, otherwise it will be stopped,
 * and the user will throw a warning.
 */
if( !$wcache_compatibility->check() ) {
	return;
}

/**
 * -----------------------------------------------------------------------------
 * CONSTANTS
 * Install frequently used constants and constants for debugging, which will be
 * removed after compiling the plugin.
 * -----------------------------------------------------------------------------
 */

// This plugin is activated
define('WCACHE_PLUGIN_ACTIVE', true);
define('WCACHE_PLUGIN_VERSION', $wcache_compatibility->get_plugin_version());
define('WCACHE_PLUGIN_DIR', dirname(__FILE__));
define('WCACHE_PLUGIN_BASE', plugin_basename(__FILE__));
define('WCACHE_PLUGIN_URL', plugins_url(null, __FILE__));



/**
 * -----------------------------------------------------------------------------
 * PLUGIN INIT
 * -----------------------------------------------------------------------------
 */

require_once(WCACHE_PLUGIN_DIR . '/libs/factory/core/boot.php');
require_once(WCACHE_PLUGIN_DIR . '/includes/class-plugin.php');

try {
	new WCTR_Plugin(__FILE__, array_merge($wcache_plugin_info, [
		'plugin_version' => WCACHE_PLUGIN_VERSION,
		'plugin_text_domain' => $wcache_compatibility->get_text_domain(),
	]));
} catch( Exception $e ) {
	// Plugin wasn't initialized due to an error
	define('WCACHE_THROW_ERROR', true);

	$wcache_plugin_error_func = function () use ($e) {
		$error = sprintf("The %s plugin has stopped. <b>Error:</b> %s Code: %s", 'Webcraftic Clearfy Cache', $e->getMessage(), $e->getCode());
		echo '<div class="notice notice-error"><p>' . $error . '</p></div>';
	};

	add_action('admin_notices', $wcache_plugin_error_func);
	add_action('network_admin_notices', $wcache_plugin_error_func);
}
// @formatter:on
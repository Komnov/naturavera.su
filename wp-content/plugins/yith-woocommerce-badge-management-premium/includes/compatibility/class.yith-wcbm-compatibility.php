<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Compatibility Class
 *
 * @class   YITH_WCBM_Compatibility
 * @package Yithemes
 * @since   1.2.8
 * @author  Yithemes
 *
 */
class YITH_WCBM_Compatibility {

    /**
     * Single instance of the class
     *
     * @var YITH_WCBM_Compatibility
     */
    protected static $_instance;

    /**
     * @type array
     */
    private $_plugins;

    /**
     * Returns single instance of the class
     *
     * @return YITH_WCBM_Compatibility
     */
    public static function get_instance() {
        return !is_null( self::$_instance ) ? self::$_instance : self::$_instance = new self();
    }

    /**
     * Constructor
     *
     * @access public
     * @since  1.0.0
     */
    public function __construct() {
        $this->_plugins = array(
            'dynamic-pricing' => 'Dynamic_Pricing',
            'auctions'        => 'Auctions',
            'themes'          => 'Themes'
        );
        $this->_load();
    }

    private function _load() {
        foreach ( $this->_plugins as $slug => $class_slug ) {
            $filename  = YITH_WCBM_COMPATIBILITY_PATH . '/class.yith-wcbm-' . $slug . '-compatibility.php';
            $classname = 'YITH_WCBM_' . $class_slug . '_Compatibility';

            $var = str_replace( '-', '_', $slug );
            if ( $this->has_plugin_or_theme( $slug ) && file_exists( $filename ) ) {
                require_once( $filename );
                if ( class_exists( $classname ) && method_exists( $classname, 'get_instance' ) ) {
                    $this->$var = $classname::get_instance();
                }
            }
        }
    }

    /**
     * Check if user has a plugin
     *
     * @param string $slug
     *
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     * @return bool
     */
    public function has_plugin_or_theme( $slug ) {
        switch ( $slug ) {
            case 'dynamic-pricing':
                return defined( 'YITH_YWDPD_PREMIUM' ) && YITH_YWDPD_PREMIUM && defined( 'YITH_YWDPD_VERSION' ) && version_compare( YITH_YWDPD_VERSION, '1.1.0', '>=' );
                break;

            case 'auctions':
                return defined( 'YITH_WCACT_INIT' ) && YITH_WCACT_INIT && defined( 'YITH_WCACT_VERSION' ) && version_compare( YITH_WCACT_VERSION, '1.0.10', '>=' );
                break;

           case 'themes':
                return true;
            

        }

        return false;
    }

    /**
     * Check if user has a theme active
     *
     *
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     *
     * @param     $name
     * @param int $min_version
     *
     * @return bool
     */
    public function has_theme( $name, $min_version = 0 ) {
        $current_theme = wp_get_theme();
        if ( $current_theme ) {
            if ( $current_theme->parent() ) {
                $current_theme = $current_theme->parent();
            }
            $theme_name    = $current_theme->get( 'Name' );
            $theme_version = $current_theme->get( 'Version' );

            return $name === $theme_name && version_compare( $theme_version, $min_version, '>=' );
        }

        return false;
    }
}

/**
 * Unique access to instance of YITH_WCBM_Compatibility class
 *
 * @return YITH_WCBM_Compatibility
 * @since 1.2.8
 */
function YITH_WCBM_Compatibility() {
    return YITH_WCBM_Compatibility::get_instance();
}
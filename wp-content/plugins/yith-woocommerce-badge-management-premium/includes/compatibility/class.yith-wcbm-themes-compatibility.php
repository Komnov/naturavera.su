<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Themes Compatibility Class
 *
 * @class   YITH_WCBM_Themes_Compatibility
 * @package Yithemes
 * @since   1.2.31
 * @author  Yithemes
 */
class YITH_WCBM_Themes_Compatibility {
    /**
     * Single instance of the class
     *
     * @var YITH_WCBM_Themes_Compatibility
     */
    private static $_instance;

    /**
     * The parent theme info
     *
     * @var object
     */
    public $theme_info;

    /**
     * Themes
     *
     * @var array
     */
    public $themes;


    /**
     * Returns single instance of the class
     *
     * @return YITH_WCBM_Themes_Compatibility
     * @since 1.0.0
     */
    public static function get_instance() {
        return !is_null( self::$_instance ) ? self::$_instance : self::$_instance = new self();
    }

    private function __construct() {
        $this->_load_theme_info();

        $this->themes = apply_filters( 'yith_wcbm_themes_compatibility_themes', include 'themes/theme-list.php' );
        if ( isset( $this->themes[ $this->theme_info->slug ] )
             &&
             ( !isset( $this->themes[ $this->theme_info->slug ][ 'min_version' ] )
               ||
               version_compare( $this->theme_info->version, $this->themes[ $this->theme_info->slug ][ 'min_version' ], '>=' )
             )
        ) {
            $current_theme_slug = $this->theme_info->slug;
            $theme              = $this->themes[ $current_theme_slug ];

            if ( !empty( $theme[ 'start' ] ) ) {
                $theme_start = is_array( current( $theme[ 'start' ] ) ) ? $theme[ 'start' ] : array( $theme[ 'start' ] );
                foreach ( $theme_start as $current_theme_start ) {
                    $start_priority = !empty( $current_theme_start[ 'priority' ] ) ? $current_theme_start[ 'priority' ] : 10;
                    add_action( $current_theme_start[ 'hook' ], array( $this, 'badge_container_start' ), $start_priority );
                }
            }

            if ( !empty( $theme[ 'end' ] ) ) {
                $theme_end = is_array( current( $theme[ 'end' ] ) ) ? $theme[ 'end' ] : array( $theme[ 'end' ] );
                foreach ( $theme_end as $current_theme_end ) {
                    $end_priority = !empty( $current_theme_end[ 'priority' ] ) ? $current_theme_end[ 'priority' ] : 10;
                    add_action( $current_theme_end[ 'hook' ], array( $this, 'badge_container_end' ), $end_priority );

                }
            }

            $compatibility_file_path = YITH_WCBM_COMPATIBILITY_PATH . "/themes/class.yith-wcbm-$current_theme_slug-theme-compatibility.php";
            if ( !empty( $theme[ 'compatibility_class' ] ) && file_exists( $compatibility_file_path ) ) {
                require_once( $compatibility_file_path );

                $compatibility_class       = $theme[ 'compatibility_class' ];
                $this->$current_theme_slug = class_exists( $compatibility_class ) ? $compatibility_class::get_instance() : false;
            }
        }

        add_filter( 'body_class', array( $this, 'add_theme_class_to_body' ) );
    }

    /**
     * Add theme class in body
     *
     * @param $classes
     * @return array
     */
    public function add_theme_class_to_body( $classes ) {
        return array_merge( $classes, array( 'yith-wcbm-theme-' . $this->theme_info->slug ) );
    }

    /**
     * print the start of badge container
     */
    public function badge_container_start() {
        do_action( 'yith_wcbm_theme_badge_container_start' );
    }

    /**
     * print the end of badge container
     */
    public function badge_container_end() {
        do_action( 'yith_wcbm_theme_badge_container_end' );
    }


    /**
     * Check if user has a theme active
     *
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     * @param     $name
     * @param int $min_version
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

    private function _load_theme_info() {
        $this->theme_info          = new stdClass();
        $this->theme_info->name    = '';
        $this->theme_info->slug    = '';
        $this->theme_info->version = '';

        $current_theme = wp_get_theme();
        if ( $current_theme ) {
            if ( $current_theme->parent() ) {
                $current_theme = $current_theme->parent();
            }
            $this->theme_info->name    = $current_theme->get( 'Name' );
            $this->theme_info->slug    = sanitize_title( strtolower( $this->theme_info->name ) );
            $this->theme_info->version = $current_theme->get( 'Version' );
        }
    }
}
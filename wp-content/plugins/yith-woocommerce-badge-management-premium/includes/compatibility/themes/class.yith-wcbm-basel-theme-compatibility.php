<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Basel Theme Compatibility Class
 *
 * @class   YITH_WCBM_Basel_Theme_Compatibility
 * @package Yithemes
 * @since   1.2.23
 * @author  Yithemes
 *
 */
class YITH_WCBM_Basel_Theme_Compatibility {

    /**
     * Single instance of the class
     *
     * @var YITH_WCBM_Basel_Theme_Compatibility
     */
    protected static $_instance;


    private $_basel_options = array();

    /**
     * Returns single instance of the class
     *
     * @return YITH_WCBM_Basel_Theme_Compatibility
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
    private function __construct() {
        /* do not add the container in shortcode if product_hover in loop is different from global to prevent issue */
        add_filter( 'yith_wcbm_theme_badge_container_start_check', array( $this, 'container_check' ) );
        add_filter( 'yith_wcbm_theme_badge_container_end_check', array( $this, 'container_check' ) );

        add_action( 'init', array( $this, 'init' ) );
    }

    public function init() {
        $hover_option = $this->get_basel_option( 'products_hover' );
        switch ( $hover_option ) {
            case 'excerpt':
            case 'info':
            case 'quick':
                // not allowed
                break;
            case 'base':
            case 'alt':
            case 'button':
            case 'link':
            case 'standard':
            default:
                add_action( 'woocommerce_before_shop_loop_item', array( $this, 'badge_container_start' ), 10 );
                add_action( 'woocommerce_shop_loop_item_title', array( $this, 'badge_container_end' ), 9 );
                break;

        }
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
     * check if print the badge container
     *
     * @param $check
     *
     * @return bool
     */
    public function container_check( $check ) {
        global $woocommerce_loop;
        if ( $woocommerce_loop && isset( $woocommerce_loop[ 'product_hover' ] ) && $this->get_basel_option( 'product_hover' ) !== $woocommerce_loop[ 'product_hover' ] )
            $check = false;

        return $check;
    }

    /**
     * get the basel option based on key
     *
     * @param $key
     *
     * @return bool|mixed
     */
    public function get_basel_option( $key ) {
        if ( !empty( $this->_basel_options[ $key ] ) )
            return $this->_basel_options[ $key ];

        $option = false;

        if ( function_exists( 'basel_get_opt' ) ) {
            $option                       = basel_get_opt( $key );
            $this->_basel_options[ $key ] = $option;
        }

        return $option;
    }
}
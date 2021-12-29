<?php
!defined( 'ABSPATH' ) && exit; // Exit if accessed directly

/**
 * Shopkeeper Theme Compatibility Class
 *
 * @class   YITH_WCBM_Shopkeeper_Compatibility
 * @package Yithemes
 * @since   1.3.7
 * @author  Yithemes
 *
 */
class YITH_WCBM_Shopkeeper_Compatibility {
    /** @var YITH_WCBM_Shopkeeper_Compatibility */
    protected static $_instance;

    /**@return YITH_WCBM_Shopkeeper_Compatibility */
    public static function get_instance() {
        return !is_null( self::$_instance ) ? self::$_instance : self::$_instance = new self();
    }

    private function __construct() {
        if ( !is_admin() ) {
            remove_filter( 'woocommerce_single_product_image_thumbnail_html', array( YITH_WCBM_Frontend(), 'show_badge_on_product_thumbnail' ), 99 );
        }
    }
}
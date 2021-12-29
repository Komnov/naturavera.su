<?php
!defined( 'ABSPATH' ) && exit; // Exit if accessed directly

/**
 * YITH Booking Theme Compatibility Class
 *
 * @class   YITH_WCBM_YITH_Booking_Theme_Compatibility
 * @package Yithemes
 * @since   1.3.20
 * @author  Yithemes
 */
class YITH_WCBM_YITH_Booking_Theme_Compatibility {
    /** @var YITH_WCBM_YITH_Booking_Theme_Compatibility */
    protected static $_instance;

    protected $in_header = false;

    /**@return YITH_WCBM_YITH_Booking_Theme_Compatibility */
    public static function get_instance() {
        return !is_null( self::$_instance ) ? self::$_instance : self::$_instance = new self();
    }

    private function __construct() {
        add_filter( 'yith_wcbm_is_allowed_badge_showing', array( $this, 'check_badge_allowed_in_product_pages' ), 10, 1 );

        add_action( 'yith_booking_content_header', array( $this, 'set_in_header' ), 0 );
        add_action( 'yith_booking_content_header', array( $this, 'unset_in_header' ), 9999 );
    }

    public function set_in_header() {
        $this->in_header = true;
    }

    public function unset_in_header() {
        $this->in_header = false;
    }

    public function check_badge_allowed_in_product_pages( $allowed ) {
        if ( $allowed && $this->in_header ) {
            $hide_on_single = get_option( 'yith-wcbm-hide-on-single-product', 'no' ) === 'yes';
            if ( $hide_on_single && function_exists( 'is_product' ) && is_product() ) {
                $allowed = false;
            }
        }
        return $allowed;
    }
}
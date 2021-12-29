<?php

return array(
    'basel'   => array(
        'min_version'         => '2.10.0',
        'compatibility_class' => 'YITH_WCBM_Basel_Theme_Compatibility'
    ),

    /** @since 1.3.20 */
    'yith-booking'   => array(
        'min_version'         => '1.0.0',
        'compatibility_class' => 'YITH_WCBM_YITH_Booking_Theme_Compatibility'
    ),

    /** @since 1.3.6 */
    'electro' => array(
        'min_version'         => '1.2.9',
        'compatibility_class' => 'YITH_WCBM_Electro_Theme_Compatibility'
    ),

    /** @since 1.3.7 */
    'estore'      => array(
        'start' => array( 'hook' => 'woocommerce_before_shop_loop_item_title', 'priority' => 9 ),
        'end'   => array( 'hook' => 'woocommerce_before_shop_loop_item_title', 'priority' => 20 ),
    ),

    'flatsome'   => array(
        'start'       => array( 'hook' => 'flatsome_before_product_images' ),
        'end'         => array( 'hook' => 'flatsome_after_product_images' ),
        'min_version' => '3.2.2'
    ),

    /** @since 1.3.7 */
    'shopkeeper' => array(
        'min_version'         => '2.3',
        'compatibility_class' => 'YITH_WCBM_Shopkeeper_Compatibility',
        'start'               => array( 'hook' => 'woocommerce_before_single_product_summary_product_images', 'priority' => 9 ),
        'end'                 => array( 'hook' => 'woocommerce_before_single_product_summary_product_images', 'priority' => 21 ),
    ),

    /** @since 1.3.7 */
    'total'      => array(
        'start' => array( 'hook' => 'woocommerce_before_shop_loop_item_title', 'priority' => 9 ),
        'end'   => array( 'hook' => 'woocommerce_before_shop_loop_item_title', 'priority' => 11 ),
    )
);
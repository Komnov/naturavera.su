<?php

//активация темы
update_option( 'us_license_activated', 1 );

add_filter('woocommerce_get_image_size_gallery_thumbnail','add_gallery_thumbnail_size',1,10);
function add_gallery_thumbnail_size($size){

    $size['width'] = 230;
    $size['height'] = 150;
    $size['crop']   = 1;
    return $size;
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
// function theme_name_scripts() {
//     wp_enqueue_style( 'jquery.fsscroll.css', '/wp-content/themes/Impreza-child/assets/css/jquery.fsscroll.css' );
//     wp_enqueue_script( 'jquery.fsscroll.js', '/wp-content/themes/Impreza-child/assets/js/jquery.fsscroll.js', array(), '1.0.0', true );
    wp_enqueue_script( 'main.js', '/wp-content/themes/Impreza-child/assets/js/main.js', array(), '1.0.0', true );
// }


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
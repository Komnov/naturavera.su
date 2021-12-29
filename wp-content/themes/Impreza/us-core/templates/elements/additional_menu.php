<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output links menu element
 */

if ( empty( $source ) OR ! is_nav_menu( $source ) ) {
	return;
}

$classes = isset( $classes ) ? $classes : '';
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';

wp_nav_menu(
	array(
		'container' => 'div',
		'container_class' => 'w-menu ' . $classes,
		'menu' => $source,
		'depth' => 1,
	)
);

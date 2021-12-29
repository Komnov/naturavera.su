<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => us_translate( 'Text' ),
	'icon' => 'fas fa-font',
	'params' => array_merge( array(
		'text' => array(
			'title' => us_translate( 'Text' ),
			'type' => 'text',
			'std' => 'Some text',
		),
		'wrap' => array(
			'type' => 'switch',
			'switch_text' => __( 'Allow move content to the next line', 'us' ),
			'std' => FALSE,
			'classes' => 'for_above',
		),
		'link_type' => array(
			'title' => us_translate( 'Link' ),
			'type' => 'select',
			'options' => array_merge(
				array(
					'none' => us_translate( 'None' ),
					'elm_value' => __( 'Use the element value as link', 'us' ),
				),
				us_get_elm_link_options(),
				array( 'custom' => __( 'Custom', 'us' ) )
			),
			'std' => 'custom',
		),
		'link' => array(
			'placeholder' => us_translate( 'Enter the URL' ),
			'description' => $misc['desc_grid_custom_link'],
			'type' => 'link',
			'std' => array(),
			'shortcode_std' => '',
			'classes' => 'for_above desc_3',
			'show_if' => array( 'link_type', '=', 'custom' ),
		),
		'icon' => array(
			'title' => __( 'Icon', 'us' ),
			'type' => 'icon',
			'std' => '',
		),
		'color' => array(
			'title' => us_translate( 'Custom color' ),
			'type' => 'color',
			'std' => '',
		),
	), $typography_options, $design_options ),
);

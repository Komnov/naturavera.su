<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Progress Bar', 'us' ),
	'icon' => 'icon-wpb-graph',
	'params' => array_merge( array(

		'title' => array(
			'title' => us_translate( 'Title' ),
			'type' => 'text',
			'std' => 'This is Progress Bar',
			'holder' => 'div',
		),
		'title_size' => array(
			'title' => __( 'Title Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '1rem',
			'cols' => 2,
			'show_if' => array( 'title', '!=', '' ),
		),
		'title_tag' => array(
			'title' => __( 'Title HTML tag', 'us' ),
			'type' => 'select',
			'options' => $misc['html_tag_values'],
			'std' => 'h6',
			'cols' => 2,
			'show_if' => array( 'title', '!=', '' ),
		),
		'count' => array(
			'title' => __( 'Progress Value', 'us' ),
			'type' => 'text',
			'std' => '50',
			'holder' => 'span',
		),
		'hide_count' => array(
			'type' => 'switch',
			'switch_text' => __( 'Hide progress value counter', 'us' ),
			'std' => FALSE,
			'classes' => 'for_above',
		),
		'style' => array(
			'title' => us_translate( 'Style' ),
			'type' => 'select',
			'options' => array(
				'1' => us_translate( 'Style' ) . ' 1',
				'2' => us_translate( 'Style' ) . ' 2',
				'3' => us_translate( 'Style' ) . ' 3',
				'4' => us_translate( 'Style' ) . ' 4',
				'5' => us_translate( 'Style' ) . ' 5',
			),
			'std' => '1',
			'admin_label' => TRUE,
		),
		'color' => array(
			'title' => __( 'Progress Bar Color', 'us' ),
			'type' => 'select',
			'options' => array(
				'primary' => __( 'Primary (theme color)', 'us' ),
				'secondary' => __( 'Secondary (theme color)', 'us' ),
				'heading' => __( 'Heading (theme color)', 'us' ),
				'text' => __( 'Text (theme color)', 'us' ),
				'custom' => us_translate( 'Custom color' ),
			),
			'std' => 'primary',
			'cols' => 2,
		),
		'size' => array(
			'title' => __( 'Progress Bar Height', 'us' ),
			'description' => $misc['desc_font_size'], // don't change to the "desc_height"
			'type' => 'text',
			'std' => '10px',
			'cols' => 2,
		),
		'bar_color' => array(
			'type' => 'color',
			'std' => '',
			'show_if' => array( 'color', '=', 'custom' ),
		),

	), $design_options ),
);

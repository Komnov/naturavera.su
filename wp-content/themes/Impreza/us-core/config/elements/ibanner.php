<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Interactive Banner', 'us' ),
	'description' => __( 'Image and text with hover effect', 'us' ),
	'icon' => 'fas fa-image',
	'params' => array_merge(
		array(

			// General
			'image' => array(
				'title' => us_translate( 'Image' ),
				'type' => 'upload',
				'extension' => 'png,jpg,jpeg,gif,svg',
				'cols' => 2,
			),
			'size' => array(
				'title' => __( 'Image Size', 'us' ),
				'description' => $misc['desc_img_sizes'],
				'type' => 'select',
				'options' => us_image_sizes_select_values(),
				'std' => 'large',
				'cols' => 2,
			),
			'title' => array(
				'title' => us_translate( 'Title' ),
				'type' => 'text',
				'std' => us_translate( 'Title' ),
				'admin_label' => TRUE,
			),
			'title_size' => array(
				'title' => __( 'Title Size', 'us' ),
				'description' => $misc['desc_font_size'],
				'type' => 'text',
				'std' => '',
				'cols' => 2,
				'show_if' => array( 'title', '!=', '' ),
			),
			'title_tag' => array(
				'title' => __( 'Title HTML tag', 'us' ),
				'type' => 'select',
				'options' => $misc['html_tag_values'],
				'std' => 'h2',
				'cols' => 2,
				'show_if' => array( 'title', '!=', '' ),
			),
			'desc' => array(
				'title' => us_translate( 'Description' ),
				'type' => 'textarea',
				'std' => '',
			),
			'desc_font_size' => array(
				'title' => __( 'Font Size', 'us' ),
				'description' => $misc['desc_font_size'],
				'type' => 'text',
				'std' => '',
				'cols' => 2,
				'show_if' => array( 'desc', '!=', '' ),
			),
			'desc_line_height' => array(
				'title' => __( 'Line height', 'us' ),
				'description' => $misc['desc_line_height'],
				'type' => 'text',
				'std' => '',
				'cols' => 2,
				'show_if' => array( 'desc', '!=', '' ),
			),
			'link' => array(
				'title' => us_translate( 'Link' ),
				'type' => 'link',
				'std' => '',
			),

			// Appearance
			'align' => array(
				'title' => us_translate( 'Alignment' ),
				'type' => 'select',
				'options' => array(
					'left' => us_translate( 'Left' ),
					'center' => us_translate( 'Center' ),
					'right' => us_translate( 'Right' ),
				),
				'std' => 'left',
				'cols' => 2,
				'group' => us_translate( 'Appearance' ),
			),
			'ratio' => array(
				'title' => __( 'Aspect Ratio', 'us' ),
				'type' => 'select',
				'options' => array(
					'1x1' => '1x1 ' . __( 'square', 'us' ),
					'2x1' => '2x1 ' . __( 'landscape', 'us' ),
					'3x2' => '3x2 ' . __( 'landscape', 'us' ),
					'4x3' => '4x3 ' . __( 'landscape', 'us' ),
					'3x4' => '3x4 ' . __( 'portrait', 'us' ),
					'2x3' => '2x3 ' . __( 'portrait', 'us' ),
					'1x2' => '1x2 ' . __( 'portrait', 'us' ),
				),
				'std' => '1x1',
				'cols' => 2,
				'group' => us_translate( 'Appearance' ),
			),
			'animation' => array(
				'title' => __( 'Animation Type', 'us' ),
				'type' => 'select',
				'options' => array(
					'melete' => 'Melete',
					'soter' => 'Soter',
					'phorcys' => 'Phorcys',
					'aidos' => 'Aidos',
					'caeros' => 'Caeros',
					'hebe' => 'Hebe',
					'aphelia' => 'Aphelia',
					'nike' => 'Nike',
				),
				'std' => 'melete',
				'cols' => 2,
				'group' => us_translate( 'Appearance' ),
			),
			'easing' => array(
				'title' => __( 'Animation Easing', 'us' ),
				'type' => 'select',
				'options' => array(
					'ease' => 'ease',
					'easeInOutExpo' => 'easeInOutExpo',
					'easeInOutCirc' => 'easeInOutCirc',
				),
				'std' => 'ease',
				'cols' => 2,
				'group' => us_translate( 'Appearance' ),
			),
			'bgcolor' => array(
				'title' => __( 'Background Color', 'us' ),
				'type' => 'color',
				'cols' => 2,
				'group' => us_translate( 'Appearance' ),
			),
			'textcolor' => array(
				'title' => __( 'Text Color', 'us' ),
				'type' => 'color',
				'cols' => 2,
				'group' => us_translate( 'Appearance' ),
			),

		), $design_options
	),
);

<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme's demo-import settings
 *
 * @filter us_config_demo-import
 */
return array(

	'main' => array(
		'title' => 'Multi-Purpose',
		'preview_url' => 'http://impreza.us-themes.com/',
		'front_page' => 'home',
		'content' => array(
			'pages',
			'posts',
			'portfolio_items',
			'testimonials',
			'headers',
			'grid_layouts',
			'page_blocks',
			'products',
		),
	),

	'interior-design-portfolio' => array(
		'title' => 'Interior Design Portfolio',
		'preview_url' => 'http://impreza16.us-themes.com/',
		'front_page' => 'home',
		'content' => array(
			'pages',
			'posts',
			'portfolio_items',
			'headers',
			'page_blocks',
		),
	),

	'personal-blog' => array(
		'title' => 'Personal Blog',
		'preview_url' => 'http://impreza13.us-themes.com/',
		'front_page' => 'blog-home',
		'content' => array(
			'pages',
			'posts',
			'headers',
			'grid_layouts',
			'page_blocks',
		),
	),

	'beauty-shop' => array(
		'title' => 'Beauty Shop',
		'preview_url' => 'http://impreza14.us-themes.com/',
		'front_page' => 'Beauty Shop Home',
		'content' => array(
			'pages',
			'posts',
			'testimonials',
			'headers',
			'grid_layouts',
			'page_blocks',
			'products',
		),
	),

	'blog-2' => array(
		'title' => 'Life News',
		'preview_url' => 'http://impreza11.us-themes.com/',
		'front_page' => 'news-home',
		'content' => array(
			'pages',
			'posts',
			'headers',
			'grid_layouts',
			'page_blocks',
		),
	),

	'blog-3' => array(
		'title' => 'Tech Magazine',
		'preview_url' => 'http://impreza12.us-themes.com/',
		'front_page' => 'latest',
		'content' => array(
			'pages',
			'posts',
			'headers',
			'grid_layouts',
			'page_blocks',
		),
	),

	'portfolio-2' => array(
		'title' => 'Digital Artist Portfolio',
		'preview_url' => 'http://impreza10.us-themes.com/',
		'front_page' => 'portfolio',
		'content' => array(
			'pages',
			'posts',
			'headers',
			'grid_layouts',
			'page_blocks',
		),
		/*
		 * Forcing theme to change some Theme Options before import of content
		 * This is required to allow import of post types and taxonomies that may be switched off by Theme Options
		 */
		'force_theme_options' => array(
			'media_category' => 1,
		),
	),

	'onepage' => array(
		'title' => 'One Page',
		'preview_url' => 'http://impreza2.us-themes.com/',
		'front_page' => 'home',
		'content' => array(
			'pages',
			'posts',
			'portfolio_items',
			'testimonials',
			'headers',
			'grid_layouts',
			'page_blocks',
		),
	),

	'creative' => array(
		'title' => 'Creative Agency',
		'preview_url' => 'http://impreza3.us-themes.com/',
		'front_page' => 'home',
		'content' => array(
			'pages',
			'posts',
			'portfolio_items',
			'testimonials',
			'headers',
			'grid_layouts',
			'page_blocks',
		),
	),

	'portfolio' => array(
		'title' => 'Portfolio',
		'preview_url' => 'http://impreza4.us-themes.com/',
		'front_page' => 'portfolio',
		'content' => array(
			'pages',
			'posts',
			'portfolio_items',
			'headers',
			'grid_layouts',
			'page_blocks',
		),
	),

	'blog' => array(
		'title' => 'Blog / Magazine',
		'preview_url' => 'http://impreza5.us-themes.com/',
		'front_page' => 'home-1-titles-over-images',
		'content' => array(
			'pages',
			'posts',
			'headers',
			'grid_layouts',
			'page_blocks',
		),
	),

	'restaurant' => array(
		'title' => 'Restaurant',
		'preview_url' => 'http://impreza6.us-themes.com/',
		'front_page' => 'home',
		'content' => array(
			'pages',
			'posts',
			'testimonials',
			'headers',
			'grid_layouts',
			'page_blocks',
		),
	),

	'photography' => array(
		'title' => 'Photography',
		'preview_url' => 'http://impreza7.us-themes.com/',
		'front_page' => 'portrait-series',
		'content' => array(
			'pages',
			'portfolio_items',
			'headers',
			'grid_layouts',
			'page_blocks',
		),
	),

	'mobile-app' => array(
		'title' => 'Mobile App',
		'preview_url' => 'http://impreza8.us-themes.com/',
		'front_page' => 'variant-1',
		'content' => array(
			'pages',
			'testimonials',
			'headers',
			'page_blocks',
		),
	),

	'mini-shop' => array(
		'title' => 'Simple Shop',
		'preview_url' => 'http://impreza9.us-themes.com/',
		'front_page' => 'shop-1',
		'content' => array(
			'pages',
			'headers',
			'grid_layouts',
			'page_blocks',
			'products',
		),
	),

);

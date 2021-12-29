<?php
 /**
 * Plugin Name: Regenerate thumbnails and delete unused
 * Plugin URI: https://way2enjoy.com/regenerate-thumbnails
 * Description: Regenerate thumbnails and delete unused 
 * Version: 1.0.6
 * Author: wpregenerate

 * Author URI: https://way2enjoy.com/regenerate-thumbnails/
 * Text Domain: regenerate-thumbnails-and-delete-unused
 */
 
set_time_limit(1900);


if ( !class_exists( 'wpregenerate_wp' ) ) {


	define( 'wpregenerate_DEV_MODE', false );
	class wpregenerate_wp {

		private $id;

		private $regenerate_settings = array();

		private $thumbs_data = array();

		private $optimization_type = 'lossy';

		public static $regenerate_plugin_version = '1.0.6';

		function __construct() {
			$plugin_dir_path = dirname( __FILE__ );
			require_once( $plugin_dir_path . '/lib/Regenerate_wpregenerate.php' );
			require_once( $plugin_dir_path . '/lib/class-wp-regenerate-async.php' );
			$this->regenerate_settings = get_site_option( '_regenerate_options' );
			$this->optimization_type = $this->regenerate_settings['api_lossy'];
			add_action( 'admin_enqueue_scripts', array( &$this, 'my_enqueue' ) );
			add_action( 'wp_ajax_regenerate_reset', array( &$this, 'regenerate_media_library_reset' ) );
			add_action( 'wp_ajax_regenerate_optimize', array( &$this, 'regenerate_optimize' ) );
			add_action( 'wp_ajax_regenerate_request', array( &$this, 'regenerate_media_library_ajax_callback' ) );
			add_action( 'wp_ajax_regenerate_reset_all', array( &$this, 'regenerate_media_library_reset_all' ) );
			add_action( 'wp_ajax_regenerate_requestd', array( $this, 'regenerate_media_library_ajax_callback77' ) );
			add_action( 'manage_media_custom_column', array( &$this, 'fill_media_columns_r_wpregenerate' ), 10, 2 );
			add_filter( 'manage_media_columns', array( &$this, 'add_media_columns_r_wpregenerate') );
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( &$this, 'add_settings_link' ) );
			add_action( 'activated_plugin', array( &$this, 'cyb_activation_redirect_regenerate' ) );
			add_action( 'wp_ajax_dismiss_welcome_notice', array( &$this, 'dismiss_welcome_notice' ) );
			add_action( 'wp_ajax_dismiss_buy_notice_r_wpregenerate', array( &$this, 'dismiss_buy_notice_r_wpregenerate' ) );
			add_action( 'wp_ajax_dismiss_rate_notice_r_wpregenerate', array( &$this, 'dismiss_rate_notice_r_wpregenerate' ) );

			add_action( 'bl_cron_hook2regen', array( $this, 'regenerate_media_library_ajax_callback_new_cron' ) );
			
		//	add_action( 'media_page_wp-regenerate-cloud', array( &$this, 'add_media_columns_regenerate_settings' ), 10, 2 );
			add_action( 'wp_ajax_update_img_regenstyle', array( &$this, 'update_img_regenstyle' ) );

// directory sleection starts here

			add_action( 'wp_ajax_regenerate_get_directory_list', 'regenerate_get_directory_list' );
			add_action( 'wp_ajax_image_list_r_wpregenerate', array( &$this, 'image_list_r_wpregenerate' ), 10, 2 );
			add_action( 'wp_ajax_regenerate_save_directory_list', 'regenerate_save_directory_list' );

// directory selection ends here
			add_action( 'plugins_loaded', array( &$this, 'regenerate_i18n_r_wpregenerate' ), 12 );

// ajax call starts here
			add_action( 'plugins_loaded', 'load_libs_r_wpregenerate');

	
// nextgen starts  here

//	add_action( 'wp_ajax_way2_request-nextgen', array( &$this, 'regenerate_media_library_nextgen_ajax_callback' ), 9, 2 );
	
	add_action( 'init', function() {
    if ( ! isset( $_REQUEST[ 'photocrati_ajax' ] ) || $_REQUEST['action'] !== 'upload_image' ) {
        # not an upload image request so
        return;
    }
	$controller = C_Ajax_Controller::get_instance();
    $controller->index_action();


		
//$image_id = (int) $_POST['id'];

//$image_id = '99';

$type = false;

				$this->optimization_type = 'loosy';
		//	}

			$this->id = $image_id;

//			if ( wp_attachment_is_image( $image_id ) ) {

				$settings = $this->regenerate_settings;
			
	$upload_dir = wp_upload_dir() ;

$directorynext=dirname($upload_dir['basedir']);

$directorynext2= explode("/",$directorynext);  
$onlyname= end($directorynext2);


if(!empty($_SERVER["HTTPS"]))
  if($_SERVER["HTTPS"]!=="off")
    $httpssy='https://'; 
  else
   $httpssy='http://'; 
else
$httpssy='http://'; 

$nggdata = get_site_option( 'ngg_options' ) ;
$gallerypath=$nggdata['gallerypath'] ;
$galleryna= explode("/",$gallerypath,-1);  
$gallerynameuu= end($galleryna);
	

//			
				$image_path = esc_url($directorynext.'/'.$gallerynameuu.'/'.$_REQUEST['gallery_name'].'/'.$_REQUEST['name']);


				global $finalurluu;
				$finalurluu = esc_url($httpssy.$_SERVER['HTTP_HOST'].'/'.$gallerypath.$_REQUEST['gallery_name'].'/'.$_REQUEST['name']);
				global $finalurlthumb;
				$img_thumbpath = esc_url($directorynext.'/'.$gallerynameuu.'/'.$_REQUEST['gallery_name'].'/thumbs/thumbs_'.$_REQUEST['name']);
				$finalurlthumb = esc_url($httpssy.$_SERVER['HTTP_HOST'].'/'.$gallerypath.$_REQUEST['gallery_name'].'/thumbs/thumbs_'.$_REQUEST['name']);

				$optimize_main_image = !empty( $settings['optimize_main_image'] );
				$api_key = isset( $settings['api_key'] ) ? $settings['api_key'] : '';
				$api_secret = isset( $settings['api_secret'] ) ? $settings['api_secret'] : '';

	
				if ( $optimize_main_image ) {

					// check if thumbs already optimized
					$thumbs_optimized = false;
								$api_result1 = $this->optimize_thumbnails_nextgen( $img_thumbpath, $type, $resize );
						$this->replace_image( $img_thumbpath, $api_result1['compressed_url'] ) ;

					$resize = true;

	$api_result = $this->optimize_image_nextgen( $image_path, $type, $resize );

						
				$this->replace_image( $image_path, $api_result['compressed_url'] );	
					
				}
				
//			$kksdd=$_GET['gallery_id'];

// call test		
//$upload_dir = print_r(wp_upload_dir(),true) ;
$pajgfttht=''.$directorynext.'/93091.txt';
file_put_contents("$pajgfttht","$controller");

// call test ends here	
				
				
		//	}
			wp_die();
	 //  exit;
    # process upload request here
}, 9 );
	
	// nextgen ends  here

	//	if ( ( !empty( $this->regenerate_settings ) && !empty( $this->regenerate_settings['auto_optimize'] ) ) || !isset( $this->regenerate_settings['auto_optimize'] ) ) {
	if ( !empty( $this->regenerate_settings ) &&  ($this->regenerate_settings['auto_optimize'] !='0') ) {
	
		
//	add_action( 'add_attachment', array( &$this, 'regenerate_media_uploader_callback' ) );			
//				add_filter( 'wp_generate_attachment_metadata', array( &$this, 'optimize_thumbnails' ) );
				
			add_action( 'wp_async_wp_generate_attachment_metadata', array( &$this, 'regenerate_media_library_ajax_callback' ), 10, 2  );
		
		
			
			}

			// If settings were not resaved after update
			if ( !isset( $this->regenerate_settings["optimize_main_image"] ) ) {
				$this->regenerate_settings["optimize_main_image"] = 0;
			}

			// If settings were not resaved after update
			if ( !isset( $this->regenerate_settings["chroma"] ) ) {
				$this->regenerate_settings["chroma"] = '4:2:0';
			}

		    add_action( 'admin_menu', array( &$this, 'regenerate_menu' ) );
			add_action( 'admin_menu', array( &$this, 'screen' ) );
	
		}

	function regenerate_i18n_r_wpregenerate() {
			$path = path_join( dirname( plugin_basename( __FILE__ ) ), 'languages' );
			load_plugin_textdomain( 'regenerate-thumbnails-and-delete-unused', false, $path );
		}



		function preg_array_key_exists( $pattern, $array ) {
		    $keys = array_keys( $array );    
		    return (int) preg_grep( $pattern,$keys );
		}

		function isApiActive() {
			$settings = $this->regenerate_settings;
			$api_key = isset( $settings['api_key'] ) ? $settings['api_key'] : '';
			$api_secret = isset( $settings['api_secret'] ) ? $settings['api_secret'] : '';
			if ( empty( $api_key ) || empty( $api_secret) ) {
				return false;
			}
			return true;			
		}



		function regenerate_menu() {
			$setting_txt = __( 'Regenerate Thumbnails Settings', 'regenerate-thumbnails-and-delete-unused' );

			add_options_page( $setting_txt, 'Regenerate', 'manage_options', 'wp-regenerate-cloud', array( &$this, 'regenerate_settings_page' ) );
		}
function cyb_activation_redirect_regenerate( $plugin ) {
	
	if ( ( get_site_option( 'wp-regenerate-hide_regenerate_welcome' )!=1 || get_option( 'wp-regenerate-hide_regenerate_welcome' ) !=1 )) {
			//	$welcome_msg_regen='#popup11';
				$welcome_msg_regen='';

				}
				  if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'options-general.php?page=wp-regenerate-cloud'.$welcome_msg_regen.'' ) ) );
    }
}

		function add_settings_link ( $links ) {
			$setting_txt80 = __( 'Settings', 'regenerate-thumbnails-and-delete-unused' );	
					$setting_txt185 = __( "Support Forum", "regenerate-thumbnails-and-delete-unused" );		


			$mylinks[]	='<a href="' . admin_url( 'options-general.php?page=wp-regenerate-cloud' ) . '">'.$setting_txt80.'</a>';
			$mylinks[]	='<a href="https://wordpress.org/support/plugin/regenerate-thumbnails-and-delete-unused" target="_blank">'.$setting_txt185.'</a>';
			$mylinks[]	='<a href="https://way2enjoy.com/regenerate-thumbnails?pluginemail='.get_bloginfo('admin_email').'" target="_blank">Live Chat</a>';
			return array_merge( $links, $mylinks );
		}
		
		
		
		
		
		function regenerate_settings_page() {
			if ( !empty( $_POST ) ) {
				$options = $_POST['_regenerate_options'];
				$result = $this->validate_options( $options );
				update_site_option( '_regenerate_options', $result['valid'] );
			}

			$settings = get_site_option( '_regenerate_options' );
			$lossy = isset( $settings['api_lossy'] ) ? $settings['api_lossy'] : 'lossy';
			$auto_optimize = isset( $settings['auto_optimize'] ) ? $settings['auto_optimize'] : 0;
			$optimize_main_image = isset( $settings['optimize_main_image'] ) ? $settings['optimize_main_image'] : 0;
			$api_key = isset( $settings['api_key'] ) ? $settings['api_key'] : '';
			$api_secret = isset( $settings['api_secret'] ) ? $settings['api_secret'] : '';
			$show_reset = isset( $settings['show_reset'] ) ? $settings['show_reset'] : 0;
			$bulk_async_limit = isset( $settings['bulk_async_limit'] ) ? $settings['bulk_async_limit'] : 4;
			$preserve_meta_date = isset( $settings['preserve_meta_date'] ) ? $settings['preserve_meta_date'] : 0;
			$preserve_meta_copyright = isset( $settings['preserve_meta_copyright'] ) ? $settings['preserve_meta_copyright'] : 0;
			$preserve_meta_geotag = isset( $settings['preserve_meta_geotag'] ) ? $settings['preserve_meta_geotag'] : 0;
			$preserve_meta_orientation = isset( $settings['preserve_meta_orientation'] ) ? $settings['preserve_meta_orientation'] : 0;
			$preserve_meta_profile = isset( $settings['preserve_meta_profile'] ) ? $settings['preserve_meta_profile'] : 0;
			$auto_orient = isset( $settings['auto_orient'] ) ? $settings['auto_orient'] : 1;
			$resize_width = isset( $settings['resize_width'] ) ? $settings['resize_width'] : 3000;
			$resize_height = isset( $settings['resize_height'] ) ? $settings['resize_height'] : 3000;
			$jpeg_quality = isset( $settings['jpeg_quality'] ) ? $settings['jpeg_quality'] : 0;
			$chroma_subsampling = isset( $settings['chroma'] ) ? $settings['chroma'] : '4:2:0';
			$mp3_bit = isset( $settings['mp3_bit'] ) ? $settings['mp3_bit'] : 96;
			$old_img_compression = isset( $settings['old_img'] ) ? $settings['old_img'] : 0;
			$notice_secn = isset( $settings['notice_s'] ) ? $settings['notice_s'] : 500;
			$total_thumbs = isset( $settings['total_thumb'] ) ? $settings['total_thumb'] : '6';
			$png_quality = isset( $settings['png_quality'] ) ? $settings['png_quality'] : 1;
			$gif_quality = isset( $settings['gif_quality'] ) ? $settings['gif_quality'] : 1;
			$pdf_quality = isset( $settings['pdf_quality'] ) ? $settings['pdf_quality'] : 100;
			$webp_yes = isset( $settings['webp_yes'] ) ? $settings['webp_yes'] : 0;
			$google = isset( $settings['google'] ) ? $settings['google'] : 0;
			$svgenable = isset( $settings['svgenable'] ) ? $settings['svgenable'] : 0;
			$video_quality = isset( $settings['video_quality'] ) ? $settings['video_quality'] : 75;
			$resize_video = isset( $settings['resize_video'] ) ? $settings['resize_video'] : 0;
			$intelligentcrop = isset( $settings['intelligentcrop'] ) ? $settings['intelligentcrop'] : 1;
			$artificial_intelligence = isset( $settings['artificial_intelligence'] ) ? $settings['artificial_intelligence'] : 0;
			$enable_optimiz_regen = isset( $settings['enable_optimiz_regen'] ) ? $settings['enable_optimiz_regen'] : 1;
			$old_img_delete = isset( $settings['old_img_delete'] ) ? $settings['old_img_delete'] : 0;
			$regen_qty = isset( $settings['regen_qty'] ) ? $settings['regen_qty'] : 80;
			$auto_regen_bulk = isset( $settings['auto_regen_bulk'] ) ? $settings['auto_regen_bulk'] : 0;
			$auto_save_regen = isset( $settings['auto_save_regen'] ) ? $settings['auto_save_regen'] : 15;
			$force_scan_way2regen = isset( $settings['force_scan_way2regen'] ) ? $settings['force_scan_way2regen'] : 0;

			$old_img_delete_only = isset( $settings['old_img_delete_only'] ) ? $settings['old_img_delete_only'] : 0;
			$machine_way2regen = isset( $settings['machine_way2regen'] ) ? $settings['machine_way2regen'] : 0;

			 if($machine_way2regen!='1')
			 {
			$scheduled_opt_way2regen = isset( $settings['scheduled_opt_way2regen'] ) ? $settings['scheduled_opt_way2regen'] : 0;
			 }
			 else
			 {
			$scheduled_opt_way2regen = '1';
			 }
	//		$scheduled_opt_way2regen = isset( $settings['scheduled_opt_way2regen'] ) ? $settings['scheduled_opt_way2regen'] : 0;
			if($scheduled_opt_way2regen!='1')
			{
			$scheduled_opt_way2regen_sec = '999999';
			}
			else
			{
			 if($machine_way2regen!='1')
			 {	
				
			$scheduled_opt_way2regen_sec = isset( $settings['scheduled_opt_way2regen_sec'] ) ? $settings['scheduled_opt_way2regen_sec'] : 999999;
			 }
			 else
			 {
			$scheduled_opt_way2regen_sec ='60';
	 
			 }
			
			}

			
			$sizes = array_keys($this->get_image_sizes());
			foreach ($sizes as $size) {
				$valid['include_size_' . $size] = isset( $settings['include_size_' . $size]) ? $settings['include_size_' . $size] : 1;
			}

					$status = $this->get_api_status( $api_key, $api_secret );


			$setting_txt = __( 'Regenerate Thumbnails Settings', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt1 = __( 'Automatic Optimization disabled', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt2 = __( 'STATS', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt3 = __( 'PLAN', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt4 = __( 'Balance', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt5 = __( 'Regenerated', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt6 = __( 'IMAGES REGENERATED', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt7 = __( 'TOTAL SAVINGS', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt8 = __( 'TOTAL QUOTA', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt9 = __( 'NEXT CREDIT', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt10 = __( 'Bulk Regenerate/Offers', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt11 = __( 'discount in One time plan & similar discount in yearly & Monthly plans.Discounts decreases daily so hurry & buy some plans', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt12 = __( '1% REDUCES TOMORROW', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt13 = __( 'ONE TIME PLAN', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt14 = __( '500000 Images', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt15 = __( '% Off', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt16 = __( 'YEARLY PLAN', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt17 = __( 'MONTHLY PLAN', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt18 = __( 'Settings saved', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt19 = __( 'Upgrade', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt20 = __( 'Chat', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt21 = __( 'Your basic details will be shared with us', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt22 = __( 'Optimization mode', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt23 = __( 'regenerate Lossy', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt24 = __( 'Lossless', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt25 = __( 'Automatically optimize thumbs', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt26 = __( 'Images uploaded through the Media Uploader will be optimized on-the-fly', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt27 = __( 'Disable this setting if you wish to compress images later', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt28 = __( 'Optimize main image', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt29 = __( 'Image uploaded by the user will be optimized, as well as all size images generated by WordPress', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt30 = __( 'Disabling this option results in faster uploading, since the main image is not sent to our system for optimization', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt31 = __( 'Disable if you never use the main image upload in your posts, or speed of image uploading is an issue', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt32 = __( 'Resize main image', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt33 = __( 'Max Width (px)', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt34 = __( 'Max Height (px)', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt35 = __( 'Restrict the maximum dimensions of image uploads by width and/or height', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt36 = __( 'Useful if you wish to prevent large photos with extremely high resolutions from being uploaded', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt37 = __( 'you can restrict the dimensions by width, height, or both. A value of zero disables this features', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt38 = __( 'Advanced Settings', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt39 = __( 'We recommend to use default values', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt40 = __( 'Image Sizes to Compress', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt41 = __( 'Automatically Orient Images', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt42 = __( 'This setting will rotate the JPEG image according to its <strong>Orientation</strong> EXIF metadata such that it will always be correctly displayed in Web Browsers', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt43 = __( 'Enable this setting if many of your image uploads come from smart phones or digital cameras which set the orientation based on how they are held at the time of shooting', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt44 = __( 'Show metadata reset per image', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt45 = __( 'Reset All Images', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt46 = __( 'It will add a Reset button in the "Show Details" popup in the Regenerate Stats column for already Regenerated images', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt47 = __( 'Resetting an image will remove the way2enjoy.com metadata associated with it, effectively making your website forget that it had been Regenerated in past, allowing once again regeneration of thumbs', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt48 = __( 'If in doubt, please contact support@way2enjoy.com', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt49 = __( 'Bulk Regeneration', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt50 = __( 'This settings defines how many images can be processed at the same time using the bulk optimizer. The default value is 4', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt51 = __( 'For blogs on shared hosting plans a lower number is advisable to avoid hitting request limits', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt52 = __( 'Save', 'regenerate-thumbnails-and-delete-unused' );
		
			$setting_txt71 = __( 'Saved', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt72 = __( 'Reset', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt81 = __( 'Rate Us', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt88 = __( 'HTML, CSS, JS COMPRESSION', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt89 = __( 'Saving on Your Homepage. All other pages are also compressed', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt90 = __( 'Done', 'regenerate-thumbnails-and-delete-unused' );	

			$setting_txt91 = __( "Images will be optimized by regenerate Image compressor", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt92 = __( "Callback was already called", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt93 = __( "Failed! Hover here", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt94 = __( "Image optimized", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt95 = __( "Retry request", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt96 = __( "This image can not be optimized any further", "regenerate-thumbnails-and-delete-unused" );		
			$setting_txt97 = __( "Enable Now!", "regenerate-thumbnails-and-delete-unused" );		
		
			$setting_txt98 = __( "5000 Images", "regenerate-thumbnails-and-delete-unused" );		
			$setting_txt100 = __( "GOOGLE PAGESPEED", "regenerate-thumbnails-and-delete-unused" );		
			$setting_txt101 = __( "Mobile", "regenerate-thumbnails-and-delete-unused" );		
			$setting_txt102 = __( "Desktop", "regenerate-thumbnails-and-delete-unused" );		
			$setting_txt103 = __( "Refresh", "regenerate-thumbnails-and-delete-unused" );		
			$setting_txt104 = __( "Minutes", "regenerate-thumbnails-and-delete-unused" );		
			$setting_txt105 = __( "Seconds", "regenerate-thumbnails-and-delete-unused" );		
			$setting_txt99 = __( "Buy", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt106 = __( "Translate", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt107 = __( "YOUR SERVER IS", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt108 = __( "Slow", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt109 = __( "Fast", "regenerate-thumbnails-and-delete-unused" );	

			$setting_txt110 = __( "PDF", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt111 = __( "Web Version", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt112 = __( "MP3 Cutter", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt113 = __( "MP3 Compression(Bit)", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt114 = __( "Higher bitrate - Higher quality & bigger size,Low bitrate - Lower quality & smaller size", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt115 = __( "Regenerate Old Images", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt116 = __( "No of previously uploaded images you want to regenerate thumbs. 0 means all. give input any number.", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt121 = __( "Image quality setting", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt122 = __( "Dont Change. Default is Intelligent & best for images", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt123 = __( "Optimize database tables", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt124 = __( "Refer", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt125 = __( "Website", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt126 = __( "Submit", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt127 = __( "Report Issue", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt128 = __( "Issue", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt129 = __( "Report Bug", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt132 = __( "Control Notices, Alerts, Warnings", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt133 = __( "No of seconds after which all warnings,alerts,notices for all plugins will be hidden.Prefer 5-10 seconds to avoid important notice", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt134 = __( "LEVERAGE BROWSER CACHING", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt137 = __( "Backup", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt138 = __( "Cloud Backup for one hour.Only for Premium Customers", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt139 = __( "Webp Image generation", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt140 = __( "Google Love", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt141 = __( "Quality", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt143 = __( "SVG Upload", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt144 = __( "Video", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt145 = __( "Free", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt146 = __( "Pro", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt147 = __( "Intelligent Crop", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt149 = __( "Artificial Intelligence", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt150 = __( "3 Credits/image will be used. Be careful it will do lot of analysis. You may not get more savings in each image but in total you can expect 5-10% more savings. Only for Premium Users", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt152 = __( "Never", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt155 = __( "Optimization", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt156 = __( "Optimized thumbnails this month", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt157 = __( "Total Balance Quota for thumbnails optimization. Dont confused with image thumbnail regeneration. That is 100% free. Its for image compression. You can buy additional credit if you want to optimize images along with thumbnail regenration", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt158 = __( "Regenerate", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt159 = __( "Unlimited", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt160 = __( "Enable it if you want to delete all unused thumbs while generating thumbs for new theme. This will save huge amount of disk space. Please test it with 1-2 images and then decide in few cases it may break some images but we have noticed that in most cases people dont clear their cache and cache try to serve images of old theme which this option deletes (if those images are not required by new theme). Please clear the cache and then decide whether it is creating any issue like deleting thumbs of new theme. if you find any such cases please report bug. we will be thankful", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt161 = __( "Delete Unused Thumbs while Regenerating", "regenerate-thumbnails-and-delete-unused" );	
			$setting_txt162 = __( 'It will allow you to regenerate once again already regenerated images. We store data so that we dont loop same image again and again but sometimes you test multiple theme and in that case you want to regenerate again and again so click on this button and refresh page and all images will appear again for regeneration.', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt163 = __( 'Regenerate Quality', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt167 = __( 'We dont Create auto post for regenerated thumbs which will simply fill database', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt169 = __( 'Only Delete Unused, No Regeneration', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt170 = __( 'If you just want to delete unused thubnails and dont want to regenerate images then enable this', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt171 = __( 'Schedule Regeneration', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt172 = __( 'Disable if not required as it will keep on running and may create some server load. Useful if you want to Regenerate(+optimize optional) all images in background. Just enable and relax. Whether you login to your site or not, your all images will be Regenearatedted (+optimized) round the clock. Give seconds in multiple of 60. for one minute give 60 for 5 minute give 300 seconds etc. Ensure that you have sufficient credit left.', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt173 = __( 'Machine Learning', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt174 = __( 'No need of manual cropping. Pixel Perfect thumbnails regeneration using machine learning. Area of interest is always covered. Stop using dumb thumbnails. Be smart and increase sales. Remember: It requires 1 hour minimum for each image and 10 credits per image. Have patience.Dont disable once activated for atleast one day. Not available for free users', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt175 = __( 'Automatically Start Bulk Regeneration', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt176 = __( 'Uncheck this if you wish to click on regenerate images manually instead of auto bulk regenration which processes images in batch automatically. Just uncheck, save and refresh. You can later enable or disable. So Try.', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt186 = __( 'Force Scan for images', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt187 = __( 'Enable if you think few images are not listed in bulk regenerator. Remember to disable this once you regenerate all images in bulk regenerator. Its resource intensive and highly recommended to disable once all images are regenerated', 'regenerate-thumbnails-and-delete-unused' );	
			
			$onoroff_optimization="";
		if($enable_optimiz_regen !='1')
		{
			$onoroff_optimization= $setting_txt1;
		}
			
			


//global $planname;
$resizefirst='';
$titlespacing='';
$displayornot='';
$htmlpopup='';
$stylered='';
$onemorebuy='';
$lbcenable="";
$lbcpopup="";
//$status['y_plan']="";
$status['expiry']="";
$plannameoriginal=@$status['plan_name'];
//	if ( ( get_site_option( 'wp-regenerate-hide_regenerate_welcome' )!=1 || get_option( 'wp-regenerate-hide_regenerate_welcome' ) !=1 )) {
//	$activimg='<img src="/wp-content/plugins/regenerate-thumbnails-and-delete-unused/css/dist/loading-before-activation.svg" width="81" height="81"/>';
		if ( ( get_site_option( 'wp-regenerate-dir_update_time' )=='')) {

	//get_site_option('wp-regenerate-dir_update_time')
	
	
	create_table_r_wpregenerate();
	
		}
	
	$adminemail=get_bloginfo('admin_email');

		
		if ( ( get_site_option( 'wp-regenerate-hide_regenerate_welcome' )!=1 || get_option( 'wp-regenerate-hide_regenerate_welcome' ) !=1 )) {
	$totalqu="10000";
	$useedd="1";
	$remainnn="10000";
//		$margintop='520';		
		$margintop='170';			
	$iconslist='class="active"';
	$optinmsg=$setting_txt21.'(Email,Website name)';
		
					
echo '<div class="wpmud"><div id="wpbody1"><div class="block float-l regenerate-welcome-wrapper">';
my_update_notice_r_wpregenerate();
$this->welcome_screen_r_wpregenerate();

echo '</div></div></div>
<!--<iframe width="560" height="315" src="https://www.youtube.com/embed/5QNPVzNV-W0" frameborder="0" allowfullscreen></iframe>-->
';
	
//	$emailladd = get_bloginfo('admin_email')  !='' ? get_bloginfo('admin_email') : "'.rand(99999,99999999999).'@tezt.com";
$randemail=rand(999999,99999999999).'@test.com';
	$emailladd = get_bloginfo('admin_email')  !='' ? get_bloginfo('admin_email') : "$randemail";
if(get_bloginfo('admin_email')=='')
{
			update_site_option( 'admin_email', $randemail );		
	
}
else
{
	
}
	
				$dataopttt['api_lossy'] = 'lossy';
				$dataopttt['auto_optimize'] = '0';
				$dataopttt['optimize_main_image'] = '0';
				$dataopttt['auto_orient'] = '1';
				$dataopttt['bulk_async_limit'] = '4';
				$dataopttt['resize_width'] = '3000';
				$dataopttt['resize_height'] = '3000';
				$dataopttt['mp3_bit'] = '96';
				$dataopttt['old_img'] = '150';
				$dataopttt['notice_s'] = '500';
				$dataopttt['jpeg_quality'] = '0';
				$dataopttt['chroma_subsampling'] = '4:2:0';
				$dataopttt['total_thumb'] = '6';
				//$dataopttt['include_size_thumbnail'] = '1';
//				$dataopttt['include_size_medium'] = '1';
//				$dataopttt['include_size_medium'] = '1';
//				$dataopttt['include_size_medium_large'] = '1';
//				$dataopttt['include_size_large'] = '1';
//				$dataopttt['include_size_post-thumbnail'] = '1';
//				$dataopttt['include_size_related'] = '1';
//				$dataopttt['include_size_home_img'] = '1';
			//	$dataopttt['api_key'] = get_bloginfo('admin_email');
				$dataopttt['api_key'] = $emailladd;
				$dataopttt['api_secret'] = get_bloginfo('siteurl');
  				$dataopttt['webp_yes'] = '0';
  				$dataopttt['google'] = '0';
				$dataopttt['pdf_quality'] = '100';
  				$dataopttt['svgenable'] = '0';
				$dataopttt['video_quality'] = '75';
				$dataopttt['resize_video'] = '0';
  				$dataopttt['intelligentcrop'] = '1';
  				$dataopttt['artificial_intelligence'] = '0';
  				$dataopttt['enable_optimiz_regen'] = '1';
  				$dataopttt['old_img_delete'] = '0';
				$dataopttt['regen_qty'] = '80';
  				$dataopttt['old_img_delete_only'] = '0';
				$dataopttt['scheduled_opt_way2regen'] = '0';
  				$dataopttt['scheduled_opt_way2regen_sec'] = '999999';
				$dataopttt['machine_way2regen'] = '0';
				$dataopttt['auto_regen_bulk'] = '0';
				$dataopttt['auto_save_regen'] = '15';
				$dataopttt['force_scan_way2regen'] = '0';

				update_site_option( '_regenerate_options', $dataopttt );	
			
				update_site_option( 'wp-regenerate-hide_regenerate_welcome', 5 );	
				update_site_option( 'hide_regenerate_buy', 0 );	
				$timenowww=time()+500000;
				update_site_option( 'rate_regenerate', $timenowww );	

				$regenerate_savingdata['size_before'] = '2';	
				$regenerate_savingdata['size_after'] = '1';	
				$regenerate_savingdata['total_images'] = '1';

				$regenerate_savingdata['quota_remaining']='10000';
				$regenerate_savingdata['pro_not']='0';

				update_site_option( 'regenerate_global_stats', $regenerate_savingdata );
				update_site_option( 'regenerate_cron_id', 5 );

		//$htmlorigi=$status['htmlo_size']  > 0 ? $status['htmlo_size'] : "1";
//$htmlcompress= $status['htmlc_size'] >0 ? $status['htmlc_size']:"1";
$htmlorigi='92510' ;
	$htmlcompress= '27890';
echo '<div id="updateyes"></div><script type="text/javascript">var admin_email_way2 = "'.$adminemail.'";</script>';
	
$pagespeedm='<p id="timer"></p>' ;
$pagespeedd= '';
	$seperator2='';
	$statusactive='true';	
	$slowfast=$setting_txt108.'/'.$setting_txt109;
	$strikethrough='style ="text-decoration: line-through;"';
	
				
		}
		
	else
	{
	$statusactive=$status['active'];
	echo '<div class="wpmud"><div id="wpbody2"><div class="block float-l regenerate-welcome-wrapper">';
$this->welcome_screen_r_wpregenerate();
//echo HelloWorldShortcode();
//echo plugin_install_count_shortcode();

echo '</div></div></div>

<!--<iframe width="560" height="315" src="https://www.youtube.com/embed/5QNPVzNV-W0" frameborder="0" allowfullscreen></iframe>-->
';	
	$optinmsg=$setting_txt167;				
	$totalqu=$status['quota_total'] ;
	$useedd=$status['quota_used'] ;
	$remainnn=$status['quota_remaining'] ;
		$margintop='170';			
	$iconslist='';

if($remainnn <='700')
{
$displayornot='';	
$titlespacing='';
}
else
{
//$displayornot='style="display:none"';	
$titlespacing='style="margin: -30px 0px 79px 0px"';
$displayornot='';	

// remove this one in next update added only for existing customers who has installed plugin but not saved in their database once they update these things should be saved in their database and should be removed. for all new customers these things will be saved once they install the plugin so this is simply not required for anyone just for old customers so that they can dismiss the notice
			update_site_option( 'hide_regenerate_buy', 0 );	
// must be removed above update in version 2.1.0.17 by 25042018

}
$htmlorigi="";
$htmlcompress="";
$slwfst="";
$status['htmlo_size']= '';
$status['htmlc_size']= '';
//$status['slow_fast']= '';
//$htmlorigi= $status['htmlo_size'];
$htmlorigi= $status['htmlo_size'];

if(!empty($htmlorigi)){
$htmlcompress=$status['htmlc_size'];
}
else{
$htmlcompress='10';
}
//$htmlorigi= !empty($status['htmlo_size']);
//$htmlcompress=!empty($status['htmlc_size']);
//$slwfst=!empty($status['slow_fast']);
$slwfst=@$status['slow_fast'];

$strikethrough='';
$pagespeedm=$setting_txt101.': '.@$status['pspeed_m'] ;
$pagespeedd= $setting_txt102.': '.@$status['pspeed_d'];
		$seperator2='/';
		$stylered='';
		$onemorebuy='';
if($remainnn<='0')
{
$buybtnshow=' '.$setting_txt99;	

$stylered='style=""';

$onemorebuy='<a href="https://way2enjoy.com/regenerate-thumbnails?pluginemail='.get_bloginfo('admin_email').'" target="_blank"> '.$buybtnshow.'&nbsp;&nbsp;</a>';
}


if($slwfst=='0')
{
		$slowfast=$setting_txt108;			

	}
else

{
		$slowfast=$setting_txt109;			
	
}




	}
	
//	$totalqu=$status['quota_total']  > 0 ? $status['quota_total'] : "500";
//	$useedd=$status['quota_used']  > 0 ? $status['quota_used'] : "1";
//	$remainnn=$status['quota_remaining']  > 0 ? $status['quota_remaining'] : "500";
	$yplan=@$status['y_plan']  > 0 ? @$status['y_plan'] : "35";
	$onetimeplan=round($yplan*0.12,2);
	$mplan=round($yplan*0.10,2);


	
$wisiiss=round($useedd/$totalqu*100);
$hhggg=round($remainnn/$totalqu*100);	

$circlepercentage=round($useedd/$totalqu*125,1);
	
			$setting_txt78 = __( 'Your credentials are valid', 'regenerate-thumbnails-and-delete-unused' );	

	
	
	$saveingstat = get_site_option( 'regenerate_global_stats' ) ;
	$saveingstat0 = get_site_option( 'regenerate_global_stats0' ) ;
	$saveingstat1 = get_site_option( 'regenerate_global_stats1' ) ;
	$saveingstat2 = get_site_option( 'regenerate_global_stats2' ) ;
	$saveingstat3 = get_site_option( 'regenerate_global_stats3' ) ;

	
	
	$saveingstat['size_before']="";
	$saveingstat['size_after']="";
	$saveingstat['total_images']="";

	
	//$sizebefor9=$saveingstat['size_before']  > 0 ? $saveingstat['size_before'] : "1";
//	$sizeaftr9=$saveingstat['size_after']  > 0 ? $saveingstat['size_after'] : "1";
//		$sizetotal9=$saveingstat['total_images']  > 0 ? $saveingstat['total_images'] : "0";
//$sizebefor=$sizebefor9+$saveingstat['size_before0']+$saveingstat['size_before1']+$saveingstat['size_before2']+$saveingstat['size_before3'];
//	$sizeaftr=$sizeaftr9+$saveingstat['size_after0']+$saveingstat['size_after1']+$saveingstat['size_after2']+$saveingstat['size_after3'];
//		$sizetotal=$sizetotal9+$saveingstat['total_images0']+$saveingstat['total_images1']+$saveingstat['total_images2']+$saveingstat['total_images3'];	
		
	$sizebefor9=$saveingstat['size_before']  > 0 ? $saveingstat['size_before'] : "1";
	$sizeaftr9=$saveingstat['size_after']  > 0 ? $saveingstat['size_after'] : "1";
		$sizetotal9=$saveingstat['total_images']  > 0 ? $saveingstat['total_images'] : "0";
//$sizebefor=$sizebefor9+$saveingstat0['size_before0']+$saveingstat1['size_before1']+$saveingstat2['size_before2']+$saveingstat3['size_before3'];
//	$sizeaftr=$sizeaftr9+$saveingstat0['size_after0']+$saveingstat1['size_after1']+$saveingstat2['size_after2']+$saveingstat3['size_after3'];
//	$sizetotal=$sizetotal9+$saveingstat0['total_images0']+$saveingstat1['total_images1']+$saveingstat2['total_images2']+$saveingstat3['total_images3'];	
	$sizebefor=$saveingstat0['size_before0']+$saveingstat1['size_before1']+$saveingstat2['size_before2']+$saveingstat3['size_before3']+1;
	$sizeaftr=$saveingstat0['size_after0']+$saveingstat1['size_after1']+$saveingstat2['size_after2']+$saveingstat3['size_after3']+1;
	$sizetotal=$saveingstat0['total_images0']+$saveingstat1['total_images1']+$saveingstat2['total_images2']+$saveingstat3['total_images3']+1;	

		
//$total_saving=$sizebefor-$sizeaftr;
	$total_saving2=$sizebefor-$sizeaftr;
	$total_saving=$total_saving2  > 0 ? $total_saving2 : "1000";
	
	
	
	
	// added on 21042018 for updating the quota if the users quota is expired
$statusuu = $this->get_api_status( get_bloginfo('admin_email'), get_bloginfo('siteurl') );
$regenerate_savingdata['size_before']=$sizebefor;

$regenerate_savingdata['size_after']=$sizeaftr;
$regenerate_savingdata['total_images']=$sizetotal;
$regenerate_savingdata['quota_remaining']=$statusuu['quota_remaining'];
$regenerate_savingdata['pro_not']=$statusuu['plan_name'];
update_site_option( 'regenerate_global_stats', $regenerate_savingdata );		

	// ends here 
	
	
//$original_sizeinkb = self::formatBytes( $saveingstat['size_before'] );
//$original_sizeinkb = self::formatBytes( $sizebefor );
$original_sizeinkb = self::formatBytes( $total_saving );


if($regenerate_savingdata['pro_not']!='1')
{
$expirydatet= $status['expiry'] >0 ? $status['expiry']:time()+86400*30;
$expirey_date=date('d-M-Y', $expirydatet);
}
else
{
$expirey_date=$setting_txt152.' <a href="#popup8" id="kuchbhi8"> <i class="material-icons">compare_arrows</i></a>';	
}

//$htmlorigi=$htmlorigi  > 0 ? $htmlorigi : "1";
//$htmlcompress= $htmlcompress >0 ? $htmlcompress:"";
//$htmlorigi=$status['htmlo_size'] ;
//$htmlcompress= $status['htmlc_size'];

//$htmloriginal= self::formatBytes( $htmlorigi );
//$htmlcompressed= self::formatBytes( $htmlcompress );

//var_dump($status['htmlo_size'] ,true);
//var_dump($htmlorigi ,true);

if(is_numeric($htmlorigi)){
$htmloriginal= self::formatBytes( $htmlorigi );
$htmlcompressed= self::formatBytes( $htmlcompress );	
$savingperchtml=round(($htmlorigi-$htmlcompress)/$htmlorigi*100,1);
$seperator='/';
$percentage='%';
}
else
{
		$setting_txt97 = __( "Enable Now!", "regenerate-thumbnails-and-delete-unused" );		

		if ( get_site_option( 'way2-lbc-enabled' )!=1){ 
				$lbc_text=$setting_txt97;		
		}
		else
		{
		$yes_url= admin_url() . 'images/yes.png';	
		$lbc_text='<span class="apiValid2" style="background:url('.$yes_url.') no-repeat 0 0"></span>';
			
		}

$headergzipuu='';
	if($status['plan_name']=='')
	{
	$headergzipuu='<h3>Upload few images & check this page again.</h3><br>
			<h3>You can see saving in place of that button</h3><br><br><h3>Read Below instructions if you cant</h3>';
			
				
	}
	
	
	
$htmloriginal= '<span class="boxuu"><a href="#popup1" id="kuchbhi">'.$setting_txt97.'</a></span>' ;
$htmlcompressed= $htmlcompress ;	
$savingperchtml='';	
$seperator='';
$percentage='';

$lbcenable= '<span class="boxuu"><a href="#popup7" id="kuchbhi7">'.$lbc_text.'</a></span>' ;
$common_rows='<tr class="regenerate-bulk-header"><td>Points to Check.</td><td style="width:120px">Importance</td></tr>

<tr class="regenerate-item-row"><td class="regenerate-bulk-filename">Do only if You are getting this message in GTmetrix</td><td class="regenerate-originalsize">High</td></tr>

<tr class="regenerate-item-row"><td class="regenerate-bulk-filename">Take .htaccess file backup ( under public_html folder )</td><td class="regenerate-originalsize">High</td></tr>

<tr class="regenerate-item-row"><td class="regenerate-bulk-filename">99% chances are that everything will work in 1 click</td><td class="regenerate-originalsize"></td></tr>
<tr class="regenerate-item-row"><td class="regenerate-bulk-filename">But 1% chance of server 500 error is there.</td><td class="regenerate-originalsize">High</td></tr>
<tr class="regenerate-item-row"><td class="regenerate-bulk-filename">If you get server error,replace with backup .htaccess file </td><td class="regenerate-originalsize">High</td></tr>
<tr class="regenerate-item-row"><td class="regenerate-bulk-filename">Site will be online without any issue</td><td class="regenerate-originalsize"></td></tr>
<tr class="regenerate-item-row"><td class="regenerate-bulk-filename">Dont be panic,We are here to make your site super fast</td><td class="regenerate-originalsize">High</td></tr>
<tr class="regenerate-item-row"><td class="regenerate-bulk-filename">Feel free to take help if in any doubt. Its free</td><td class="regenerate-originalsize"></td></tr>';
$another_head='<h2>Important Points. Must read</h2><a class="close" href="#">×</a><div class="content">';
$htmlpopup='<div id="popup1" class="overlay">
	<div class="popup">
	
			'.$headergzipuu.$another_head.'
		

<table id="regenerate-html">'.$common_rows.'<tr class="regenerate-item-row"><td class="regenerate-bulk-filename">HTML,CSS,JS,SVG etc compression will make site super fast</td><td class="regenerate-originalsize"></td></tr></table><input type="hidden" id="gzip" name="enable-gzip" value="0" /><button class="regenerate_req_html" id="gzipcomp">Enable HTML,CSS,JS Compression</button></div></div></div>';


$lbcpopup='<div id="popup7" class="overlay"><div class="popup">'.$another_head.'<table>'.$common_rows.'</table><input type="hidden" id="lbc1" name="enable-lbc" value="0" /><button class="regenerate_req_lbc" id="lbcenbl">Enable '.$setting_txt134.' </button></div></div></div>  ';



// scheduled to be removed below codes its of no use
	//	if(strtolower($_SERVER['SERVER_SOFTWARE']) == 'apache') {$funname='regenerate_addHtaccessContent';}
	//	else{$funname='regenerate_other_gzip';}
$auto_regen_bulk99 = $auto_regen_bulk >0 ? $auto_save_regen : 100000;

echo '<script>
jQuery(document).ready(function($) {
$(\'.regenerate_req_html\').click(function(e){
        e.preventDefault();
        var $el = $(this).parents().eq(1);
        remove_element($el);
	    var data1 = {
        action: \'way_enable_gzip_r_wpregenerate\',
        };		
        $.post(ajaxurl, data1, function(response) {
		 alert(response);
		 $(\'#popup1\').toggleClass(\'overlay class2\');
        });	 			  });
		
 });
</script>';

echo '<script>
jQuery(document).ready(function($) {
$(\'.regenerate_req_lbc\').click(function(e){
        e.preventDefault();
        var $el = $(this).parents().eq(1);
        remove_element($el);
	    var data29 = {
        action: \'way_enable_lbc_r_wpregenerate\',
        };		
        $.post(ajaxurl, data29, function(response) {
		 alert(response);
		 $(\'#popup7\').toggleClass(\'overlay class2\');
        });	 			  });
		
 });
</script><script type="text/javascript">var admin_email_way2 = "'.$adminemail.'";</script>

<script>
var countDownDate = new Date().getTime() + '.$auto_regen_bulk99.'000;
var x = setInterval(function() {
    var now = new Date().getTime();
	var distance = countDownDate - now;
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    document.getElementById("timer").innerHTML =  + minutes + " '.$setting_txt104.' " + seconds + " '.$setting_txt105.' ";
       if (distance < 0) {
        clearInterval(x);
        document.getElementById("timer").innerHTML = "<a href=\''.$_SERVER['REQUEST_URI'].'\'>'.$setting_txt103.'</a>";
    }
}, 1000);

</script>

';

}

$savingpercentage=(($sizebefor-$sizeaftr)/$sizebefor*100);
$randdchartt=' <table style="background:#f1f1f1;margin-right:40px;"><tr class="regenerateError" title="'.$remainnn.' '.$setting_txt4.','.$useedd.' '.$setting_txt5.'"><td><td style="background:#b9b9b9; width:'.$wisiiss.'%;height:0.3px;"></td><td style="background:#28B576; width:'.$hhggg.'%;height:0.3px;"></td></tr></table>';

			$icon_url = admin_url() . 'images/';
			if ( $status !== false && isset( $status['active'] ) && $status['active'] === true ) {
				$icon_url .= 'yes.png';
	$status_html = '<p class="apiStatus">'.$setting_txt78.' <span class="apiValid" style="background:url(' . "'$icon_url') no-repeat 0 0" . '"></span></p>';
//		

			} else {
				$icon_url .= 'no.png';

$status_html = '<p class="apiStatus"></p>';



//$status_html = '<p class="apiStatus"><input name="pluginemail" type="hidden" id="pluginemail" value="'.get_bloginfo('admin_email').' " onchange="way2ejy.updateSignupEmail();"> <a type="button" id="request_key" style="font-size: 66px;line-height: 66px;height: 75px;font-family: monospace;" class="button button-primary button-hero" title="Request a new API key" href="http://way2enjoy.com/regenerate-thumbnails?pluginemail='.get_bloginfo('admin_email').'" onmouseenter="way2ejy.updateSignupEmail();" target="_blank">Get API Key</a><br><h3>Its free and 1 click away.No Signup required</h3> </p>';


//										
			}
			
		if($plannameoriginal=='0' || $plannameoriginal=='')
{
$backup_text= '<i class="material-icons">clear</i>';
$likesss='';	
	$planname=$setting_txt145.' : '.$setting_txt108;

}
else
{
$backup_text= '<i class="material-icons">cloud_done</i>';
$likesss='';	
	$planname='<span class="proclass"></span> '.$setting_txt146.' : '.$setting_txt109;

}
	


if ( $auto_regen_bulk=='1') {
	echo '<script>jQuery(document).ready(function($) {
if($("#the-list").length) {
setTimeout(function() {
$("#doaction").trigger(\'click\');}, '.$auto_save_regen.'000);
                        }
                        
	$("#doaction").click(function () {
    $("#the-list").css("display", "none");
    $("#doaction").css("display", "none");

});					
						
                        });
	</script>';

	}

	
	
			?>	
            
            
         <?php	 $activimg= '';	  echo $activimg ;
if( current_user_can('editor') || current_user_can('administrator') ) { 
$prefix_num='';
}
else
{
$prefix_num='99';
	
}

		 ?> 
            
              
     
            <h2 class="regenerate-admin-section-title" style="margin: 0em 0;"><?php echo $optinmsg ;?> 
            
 <span style="padding-left:20px;"><a style="text-decoration:none" href="https://wordpress.org/support/plugin/regenerate-thumbnails-and-delete-unused" target="_blank"><b><?php echo $setting_txt129;?> </b> </a>&nbsp;&nbsp;&nbsp;<a class="button button-primary" href="https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>" target="_blank"><b>  &nbsp;&nbsp;<?php echo $setting_txt20;?> </b></a>
<?php
$randomerating=rand(1,5);
	
echo '<a style="text-decoration:none;text-align:right;margin-left:30px;'.$likesss.'" href="https://wordpress.org/support/plugin/regenerate-thumbnails-and-delete-unused/reviews/?filter=5" target="_blank"><span class="inlove'.$randomerating.'"></span>'.$setting_txt81.'</a>&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;text-align:right;margin-left:90px;" href="http://paypal.me/way2enjoy/50" target="_blank">Donate Please</a>'; 
//}
//elseif($randomerating=='3')
//{
//echo '<a style="text-decoration:none;text-align:right;margin-left:30px;'.$likesss.'" href="https://translate.wordpress.org/projects/wp-plugins/regenerate-thumbnails-and-delete-unused" target="_blank"><i class="material-icons">translate</i></a>'; 	
//}
?> 
  </span>
    <span style="float:right;margin-right: 50px;" class="blink_me_way2regen"><?php echo $onoroff_optimization;?></span></h2><?php echo $randdchartt ;echo @$status['offer'] ;?>
            
            
            
            
<!--   <form id="regenerateSettings" method="post">
-->

<div class="wpmud"><div id="wpbody3">   
  <div class="row wp-regenerate-container-wrap">  
  <div class="wp-regenerateit-container-right col-half float-l" id="regenbloack1"><section class="dev-box regenerate-stats-wrapper wp-regenerate-container" id="wp-regenerate-stats-box1"><div class="wp-regenerate-container-header box-title" xmlns="http://www.w3.org/1999/xhtml">
			<h3 tabindex="0"><?php echo $setting_txt2 ;?></h3><div class="regenerate-container-subheading roboto-medium"><?php echo $planname; ?></div></div><div class="box-content">
			<div class="row regenerate-total-savings regenerate-total-reduction-percent">
<div class="wp-regenerate-current-progress">
				<div class="wp-regenerateed-progress">
					<div class="wp-regenerate-score inside">
						<div class="tooltip-box">
							<div class="wp-regenerate-optimisation-progress">
								<div class="wp-regenerate-progress-circle"><a class="regenerateError" title="<?php echo $remainnn. ' '.$setting_txt4.','.$useedd.' '. $setting_txt5; ?>">
									<svg class="wp-regenerate-svg" xmlns="http://www.w3.org/2000/svg" width="50" height="50">
										<circle class="wp-regenerate-svg-circle" r="20" cx="25" cy="25" fill="transparent" stroke-dasharray="0" stroke-dashoffset="0"></circle>
										<!-- Stroke Dasharray is 2 PI r -->
										<circle class="wp-regenerate-svg-circle wp-regenerate-svg-circle-progress" r="20" cx="25" cy="25" fill="transparent" stroke-dasharray="125" style="stroke-dashoffset:  <?php echo $circlepercentage  < 126 ? $circlepercentage : "125"; ?>px;"></circle>
									</svg></a>
								</div>
							</div>
						</div><!-- end tooltip-box -->
					</div>
				</div>
  
                        
<!--                    stats summary starts here
-->
 <div class="wp-regenerate-count-total">
					<div class="wp-regenerate-regenerate-stats-wrapper">
						<span class="wp-regenerate-total-optimised"><?php echo $sizetotal; ?></span>
					</div>
					<span class="total-stats-label"><strong><?php esc_html_e( $setting_txt6, "wp-regenerate" ); ?></strong></span>
				</div>
				</div>
			</div>
			<hr />
			<div class="row wp-regenerate-savings">
				<span class="float-l wp-regenerate-stats-label"><strong><?php esc_html_e($setting_txt7, "wp-regenerate");?></strong></span>
				<span class="float-r wp-regenerate-stats">
					<span class="wp-regenerate-stats-human">
						<?php echo $original_sizeinkb > 0 ? $original_sizeinkb : "0MB"; ?>
					</span>
					<span class="wp-regenerate-stats-sep">/</span>
					<span class="wp-regenerate-stats-percent"><?php echo $savingpercentage > 0 ? number_format_i18n( $savingpercentage, 1, '.', '' ) : 0; ?></span>%
				</span>
			</div>
 <hr>
			<div class="row wp-regenerate-savings">
				<span class="float-l wp-regenerate-stats-label"><strong><?php echo $setting_txt8.' - '.$setting_txt158;?></strong></span>
				<span class="float-r wp-regenerate-stats">
					<span class="wp-regenerate-stats-human" <?php 
 echo $stylered ; ?>>
                    
                    
                    
                    
					
					<span class="regenerateError" original-title="<?php echo $setting_txt159; ?>"><?php echo $setting_txt159; ?></span>
                    
                   
						
						
						
					
                    
                    </span>
						
				</span>
			</div>
			     
                 
                 
                 
                 
                 
                 
               <hr>
			<div class="row wp-regenerate-savings">
				<span class="float-l wp-regenerate-stats-label"><strong><?php  echo $setting_txt8.' - '.$setting_txt155;?></strong></span>
				<span class="float-r wp-regenerate-stats">
					<span class="wp-regenerate-stats-human" <?php 
 echo $stylered ; ?>>
                    
                    
                    
                    
						<?php 
 echo $onemorebuy ; ?>
			
	 <span class="counteruu regenerateError" original-title="<?php echo $setting_txt156; ?>" data-count="<?php echo $useedd > 0 ? $useedd : "0"; ?>">0</span>
						
						
						
					
                    
                    </span>
					<span class="wp-regenerate-stats-sep">/</span>					<span class="wp-regenerate-stats-percent regenerateError" original-title="<?php echo $setting_txt157; ?>" ><?php echo $totalqu > 0 ? $totalqu : 1500; ?><a href="#popup5" id="kuchbhi5">
                    <span class="transfer"></span>                    
                    </a></span>
				</span>
			</div>   
                 
                 
                 
                 
                 
                 
                        <hr>        
            <div class="row regenerate-dir-savings">
            <span class="float-l wp-regenerate-stats-label"><strong><?php echo $setting_txt9; ?></strong></span>
            <span class="float-r wp-regenerate-stats">
	            <span class="spinner" style="visibility: visible; display: none;" title="Updating Stats"></span>
				                    <span class="wp-regenerate-stats-human">
		             
<?php

echo $expirey_date; 
 
 ?>          

	                </span>
                    <span class="wp-regenerate-stats-sep hidden">/</span>
                    <span class="wp-regenerate-stats-percent"></span>
				                </span>
            </div>
            
          <hr>        
            <div class="row regenerate-dir-savings">
            <span class="float-l wp-regenerate-stats-label"><strong><span class="regenerateError" title="<?php echo $setting_txt138; ?>"><?php echo $setting_txt137; ?></span></strong></span>
            <span class="float-r wp-regenerate-stats">
	            <span class="spinner" style="visibility: visible; display: none;" title="Updating Stats"></span>
				                    <span class="wp-regenerate-stats-human">
		             
<?php

echo $backup_text; 
 
 ?>          

	                </span>
                    <span class="wp-regenerate-stats-sep hidden">/</span>
                    <span class="wp-regenerate-stats-percent"></span>
				                </span>
            </div>    
            
            
                <hr>        
            <div class="row regenerate-dir-savings">
            <span class="float-l wp-regenerate-stats-label"><strong><?php echo ''; ?></strong></span>
            <span class="float-r wp-regenerate-stats">
	            <span class="spinner" style="visibility: visible; display: none;" title="Updating Stats"></span>
				                    <span class="wp-regenerate-stats-human">
		               
<?php

echo ''; 
 
 ?>          

	                </span>
                    <span class="wp-regenerate-stats-sep hidden">/</span>
                    <span class="wp-regenerate-stats-percent"></span>
				                </span>
            </div>
            
            
            
            
            </div>				<!-- Make a hidden div if not stats found -->
				</section>				</div>    
                <div class="wp-regenerateit-container-right col-half float-l" id="regenbloack2"><section class="dev-box regenerate-stats-wrapper wp-regenerate-container" id="wp-regenerate-stats-box2">			<div class="wp-regenerate-container-header box-title" <?php 	echo $titlespacing; ?> xmlns="http://www.w3.org/1999/xhtml">
			<h3 tabindex="0"><?php echo $setting_txt10; ?></h3><div class="regenerate-container-subheading roboto-medium"><div class="regenerate-container-subheading roboto-medium">
        
   <!--       bulk compressor starts here    
-->           
<?php 	echo  $this->add_media_columns_regenerate_settings();	  ?>
         <!--       bulk compressor ends here    
-->     
   </div></div>			</div>			<div class="box-content" <?php 	echo $displayornot; ?>>
			
			<div class="row wp-regenerate-savings">
				<span class="float-l wp-regenerate-stats-label"><strong><?php esc_html_e($setting_txt13, "wp-regenerate");?></strong></span>
				<span class="float-r wp-regenerate-stats">
					<span class="wp-regenerate-stats-human">
						<?php echo $setting_txt98; ?>
					</span>
					<span class="wp-regenerate-stats-sep">/</span>
					<span class="wp-regenerate-stats-percent"><a href="https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>" target="_blank"><?php echo $onetimeplan; ?></a></span><a href="https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>" target="_blank"><?php $buybtnshow='';	
 echo '$ '.$buybtnshow; ?></a>
				</span>
			</div>   
      <hr>
		
            
            
            
            
               <div class="row regenerate-dir-savings">
            <span class="float-l wp-regenerate-stats-label"><strong><?php echo $setting_txt16; ?></strong></span>
            <span class="float-r wp-regenerate-stats">
	            <span class="spinner" style="visibility: visible; display: none;" title="Updating Stats"></span>
                
             
             <span class="wp-regenerate-stats-human">
						<?php echo $setting_txt98; ?>
					</span>
               
                <span class="wp-regenerate-stats-sep">/</span>
				                    <span class="wp-regenerate-stats-human">
		                <a href="https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>" target="_blank">
<?php echo $yplan.'$ '.$buybtnshow; ?>          
              </a>
	                </span>
                    <span class="wp-regenerate-stats-sep hidden"></span>
                    <span class="wp-regenerate-stats-percent"></span>
				                </span>
            </div>
            
           
            
			            <hr>           
            <div class="row regenerate-dir-savings">
            <span class="float-l wp-regenerate-stats-label"><strong><?php echo $setting_txt17; ?></strong></span>
            <span class="float-r wp-regenerate-stats">
	            <span class="spinner" style="visibility: visible; display: none;" title="Updating Stats"></span>
                
             
             <span class="wp-regenerate-stats-human">
						<?php echo $setting_txt98; ?>
					</span>
               
                <span class="wp-regenerate-stats-sep">/</span>
				                    <span class="wp-regenerate-stats-human">
		                <a href="https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>" target="_blank">
<?php echo $mplan.'$ '.$buybtnshow; ?>          
              </a>
	                </span>
                    <span class="wp-regenerate-stats-sep hidden"></span>
                    <span class="wp-regenerate-stats-percent"></span>
				                </span>
            </div>
            
            
            
            
            
            	            <hr>           
            <div class="row regenerate-dir-savings">
            <span class="float-l wp-regenerate-stats-label"><strong><?php echo $setting_txt124; ?></strong></span>
            <span class="float-r wp-regenerate-stats">
	            <span class="spinner" style="visibility: visible; display: none;" title="Updating Stats"></span>
                
             
             <span class="wp-regenerate-stats-human">
						<?php echo $setting_txt98; ?>
					</span>
                 <span class="wp-regenerate-stats-sep">/</span>
				                    <span class="wp-regenerate-stats-human">
		                <a href="#popup2" id="kuchbhi2"><?php echo $setting_txt125; ?></a>
	                </span>
                    <span class="wp-regenerate-stats-sep hidden"></span>
                    <span class="wp-regenerate-stats-percent"></span>
				                </span>
            </div>
            
            
                   <hr>           
            <div class="row regenerate-dir-savings">
            <span class="float-l wp-regenerate-stats-label"><strong><?php echo $setting_txt127; ?></strong></span>
            <span class="float-r wp-regenerate-stats">
	            <span class="spinner" style="visibility: visible; display: none;" title="Updating Stats"></span>
                
             
             <span class="wp-regenerate-stats-human">
						<?php echo $setting_txt98; ?>
					</span>
                 <span class="wp-regenerate-stats-sep">/</span>
				                    <span class="wp-regenerate-stats-human">
		                <a href="https://wordpress.org/support/plugin/regenerate-thumbnails-and-delete-unused" target="_blank"><?php echo $setting_txt128; ?></a>
	                </span>
                    <span class="wp-regenerate-stats-sep hidden"></span>
                    <span class="wp-regenerate-stats-percent"></span>
				                </span>
            </div>
            
            
            
            
            
            </div>				<!-- Make a hidden div if not stats found -->
				</section>				</div>      
                <!--     check third options        
-->     
     
     
  <form id="regenerateSettings<?php $prefix_num; ?>" method="post">  
    <div class="wp-regenerateit-container-right col-half float-l" id="regenbloack3"><section class="dev-box regenerate-stats-wrapper wp-regenerate-container" id="wp-regenerate-stats-box3">			<div class="wp-regenerate-container-header box-title" xmlns="http://www.w3.org/1999/xhtml">
			<h3 tabindex="0"><i class="material-icons">build</i></h3></div><div class="box-content">
        
        	<div><div class="wp-regenerate-resize-settings-wrap"></div></div>
           


  	<div><div class="wp-regenerate-resize-settings-wrap"><?php echo '<label><span class="regenerateError" title="">'.$setting_txt163.'</span><input type="number" min="1" max="100" id="regenerate_quality" class="wp-regenerate-resize-input" value="'.esc_attr( $regen_qty ).'" placeholder="'.esc_attr( $regen_qty ).'" name="_regenerate_options[regen_qty]" tabindex="0">
						</label>
'; ?></div></div><hr />
  
  
   	<div><div class="wp-regenerate-resize-settings-wrap"><label class="delete_style"><span class="regenerateError" title="<?php echo $setting_txt160; ?>"><?php echo $setting_txt161; ?></span> <input type="checkbox" id="regenerate_delete" name="_regenerate_options[old_img_delete]" value="1" <?php checked( 1, $old_img_delete, true ); ?>/>
						</label>
</div></div><hr />
  
          

  
  
      <div><div class="wp-regenerate-resize-settings-wrap"><label  class="delete_style"><span class="regenerateError" title="<?php echo  $setting_txt170; ?>"><?php echo $setting_txt169; ?>&nbsp;&nbsp;&nbsp;</span>
	  
	  <input type="checkbox" id="old_img_delete_only" name="_regenerate_options[old_img_delete_only]" value="1" <?php checked( 1, $old_img_delete_only, true ); ?>/>		

				</label>
                        
                        	
                        
                        </div></div>     
         
                   
					
              <!--           server status starts here 
-->        
            <hr />
            
			      <div><div class="wp-regenerate-resize-settings-wrap"><label><span class="regenerateError" title="<?php echo  $setting_txt162; ?>"><span class="regenerate-reset-all enabled"><?php echo $setting_txt45;?></span></span>
	  
	 		

				</label>
                        
                        	
                        
                        </div></div>     
         
                        
              <!--           server status starts here 
-->        
            <hr />
			
			
            <div class="row regenerate-dir-savings">
            <span class="float-l wp-regenerate-stats-label"><strong><span class="regenerateError" title="<?php echo $setting_txt138; ?>"><?php echo $setting_txt137; ?></span></strong></span>
            <span class="float-r wp-regenerate-stats">
	            <span class="spinner" style="visibility: visible; display: none;" title="Updating Stats"></span>
				                    <span class="wp-regenerate-stats-human">
		             
<?php

echo $backup_text; 
 
 ?>          

	                </span>
                    <span class="wp-regenerate-stats-sep hidden">/</span>
                    <span class="wp-regenerate-stats-percent"></span>
				                </span>
            </div>    



                
                
                            </div>

				</section>		
                
                		</div>  
             
      <div id="popup11" class="overlay">
	<div class="popup">
    <a class="close" href="#">×</a>
		<h2>1 Minute Video for Self Onboarding</h2><p><b>Schedule Regeneration:If you have lot of images</b></p><p>Regenerate Only (Default)</p><p>Regenerate + Delete unused (change settings)</p><p>Only delete unused, No Regeneration (Change Settings)</p><p>Optimize all regenerated images</p>
		<p><a href="https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>" target="_blank">24x7 CHAT SUPPORT FOR EASY ONBOARDING </a></p>
		
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/yYjrDxK6Kwg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		
		
    <br />   <br />  
                     
</div></div>
  
<div id="popup2" class="overlay">
	<div class="popup">
		<h2>Refer friends get 2500+2500 credit instantly</h2>
		<a class="close" href="#">×</a>
        <div id="referlo-btnm"></div>
		<div class="content" id="refsubmit">
<label>Friends site name where Plugin is installed & both of you will get 2500 Credit.<br /> <input type="text" id="sitename" name="sitename" value="" placeholder="https://example.com" /></label>

<input type="hidden" id="referral" name="referal" value="<?php echo get_bloginfo('admin_email'); ?>" />

<button class="regenerate_req_referal" id="referralsub"><?php echo $setting_txt126 ;?></button>
</div><div class="hiddenspinner" alt="spinner" id="imgref"></div> 
	
    <br />  <br /> <br /> <code>Hey,

I’ve been using regenerate image optimizer plugin for WordPress and it made my website blazing fast.

Just download regenerate image optimizer from wordpress and install. Click on Refer and enter <?php echo get_bloginfo('siteurl'); ?>  & both will get 2500 credit instantly.Like me, You can also post these things in your site and forget about credit at all. Whenever someone will read your post and install this plugin and when he claims refer credit you will get 2500 always.
       
                     </code> <br /> <br /> <br /><a href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwordpress.org%2Fplugins%2Fregenerate-thumbnails-and-delete-unused%2F" target="_blank"><span class="fbsvg"></span></a><a href="https://twitter.com/intent/tweet?url=https%3A%2F%2Fwordpress.org%2Fplugins%2Fregenerate-thumbnails-and-delete-unused%2F" target="_blank"><span class="twtsvg"></span></a><a href="https://reddit.com/submit?url=https%3A%2F%2Fwordpress.org%2Fplugins%2Fregenerate-thumbnails-and-delete-unused%2F" target="_blank"><span class="rdtsvg"></span></a><br /> 
                    Paste in FB/Twitter/Whatsapp etc & get 2000 Credit/Share <a style="text-decoration:none;text-align:right;margin-left:30px;" href="https://wordpress.org/support/view/plugin-reviews/regenerate-thumbnails-and-delete-unused?rate=5#postform" target="_blank"><h2 style="display:inline-block"><?php echo $setting_txt81 ;?></h2></a> 	<br />
                     <code>Hey Try this image optimizer & speedup your site. Its free. https://wordpress.org/plugins/regenerate-thumbnails-and-delete-unused/</code>
    
    </div>
    
  
    
</div> 


<div id="popup5" class="overlay">
	<div class="popup">
		<h2>Transfer Credit instantly</h2>
		<a class="close" href="#">×</a>
        <div id="trnsfr-btnm"></div>
		<div class="content" id="trsfrsubmit">
<label>Website Name/Admin Email(Preferred) where you want to transfer<br /> <input type="text" id="tsitename" name="tsitename" value="" placeholder="https://example.com or something@someemail.com" /></label>
<label>Credit(for example:1500)<br /> <input type="text" id="tcredite" name="tcredite" value="" placeholder="1500" /></label>
<button class="regenerate_req_referal" id="transfersub"><?php echo $setting_txt126 ;?></button>
</div><div class="hiddenspinner" alt="spinner" id="imgreftransfer"></div> 
	
    <br />  <br /> <br /> <code>Hey,

Its completely safe.Noone can steal your credit ever but you can easily transfer your credit to your friend sites :)
       
                     </code> <br /> <br /> <br /> <a style="font-size: 14px;line-height: 32px;height: 32px;font-family: monospace;" class="button button-primary button-hero" href="https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>" target="_blank"> <?php echo $setting_txt20;?></a>
                     
</div></div> 

<!--///popup 8
-->
<div id="popup8" class="overlay">
	<div class="popup">
		<h2>Switch to FREE account & get free Credit </h2><h5></h5>
		<a class="close" href="#">×</a>
        <div id="swtch-btnm"></div>
		<div class="content" id="swtchsubmit">
<label>FREE to PRO not Possible without Payment<br /> </label>
<button class="regenerate_req_referal" id="switchsub"><?php echo $setting_txt126 ;?></button>
</div><div class="hiddenspinner" alt="spinner" id="switchspinner"></div> 
	
    <br />  <br /> <br />  
                     
</div></div> 

<!--/// popup8 ends here
-->


<!--///popup 12
-->
<script>
jQuery(document).ready(function ($) {
$('a.regen-popup').click(function (event) 
{
var urlregenclick = $(this).attr('alt');
  $("img").attr("src", urlregenclick); 
		});
	});	
  </script>
<div id="popup12" class="overlay">
	<div class="popup">
		<h2>Image Focus selector</h2><h5></h5>
		<a class="close" href="#">×</a>
        <div></div>
		<div class="content">
<label>Decide where is the focus of image. By default all images are centrally focused(means full image is important) but few images main content may be at top( some celebrity with face at top) or may be at bottom. See this image here and decide right option.<br /> </label>
<img src="" style="height: 100%; width: 100%; object-fit: contain" /></div> 
	
    <br />  <br /> <br />  
                     
</div></div> 

<!--/// popup8 ends here
-->

<div id="popup3" class="overlay">

	<div class="popup">
     <?php 
				$setting_txt130 = __( "Check Regenerated Images", "regenerate-thumbnails-and-delete-unused" );
	
	echo	'<h2>'.$setting_txt130.'</h2>';
	?>
		<a class="close" href="#">×</a>
   
   <div id="filetree-basic"></div>     
    <br /><br /><br /><br />
    <?php 
	$setting_txt103 = __( "Refresh", "regenerate-thumbnails-and-delete-unused" );		
	$setting_txt126 = __( "Submit", "regenerate-thumbnails-and-delete-unused" );

   echo ' <div id="save_response"></div><button class="regenerate_req_folder" id="regenerate_savedirectory">'.$setting_txt126.'</button>';
   
   
   ?>     
    </div>
    

    
</div> 


<div id="popup6" class="overlay">
	<div class="popup">
		<h2>Update your email address</h2>
		<a class="close" href="#">×</a>
        <div id="updte-btnm"></div>
		<div class="content" id="updtesubmit">

<button class="regenerate_req_updemail" id="upetemlsub"><?php echo $setting_txt52 ;?></button>
</div><div class="hiddenspinner" alt="spinner" id="imgupdtemail"></div> 
	
    <br />  
                     
</div></div> 

<div id="popup9" class="overlay">
	<div class="popup">
		<h2>Report Bug get 10000 Free Image Optimization Credit</h2>
		<a class="close" href="#">×</a>
        <div id="updte-btnm"></div>
		<div class="content" id="updtesubmit"><code>REGENERATE OLD IMAGES setting is ZERO by default. It means it selects all images but if you want to optimize say last 300 images just enter 300 save it and refresh.</code>
     <br /><br /><br />   
<code>This Plugin generates thumbs of better quality than wordpress default, you can optimize in addition of regeneration(GET 10000 FREE CREDITS JUST REPORT BUGS/IDEAS/IMPROVEMENTS). We dont use your server resources as everything is done in cloud so this plugin will not cause any sort of load on your server.</code><br /><br /><br />

</div><div class="hiddenspinner" alt="spinner" id="imgupdtemail"></div> 
	
    <br />  
       <br /> <a style="font-size: 14px;line-height: 32px;height: 32px;font-family: monospace;" class="button button-primary button-hero" href="https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>" target="_blank"> <?php echo $setting_txt20;?></a>              
</div></div> 


     <?php echo $htmlpopup; echo $lbcpopup;
	 
	 if($enable_optimiz_regen!='1')
	 {
		$opacityno='opacity:0.5;'; 
	 }
	  
	 
	  ?>       
<!--       third options ends here              
-->                
                <!-- </div> </div>-->
<!--          	 stats summary ends here
-->       
	<?php if ( isset( $result['error'] ) ) { ?>
						<div class="regenerate error settings-error">
						<?php foreach( $result['error'] as $error ) { ?>
							<p><?php echo $error; ?></p>
						<?php } ?>
						</div>
					<?php } else if ( isset( $result['success'] ) ) { ?>
						<div class="regenerate updated settings-error">
							<p><?php echo $setting_txt18; ?>.</p>
						</div>
					<?php } ?>				
 <p style="font-size:18px">
   <!--            <a style="font-size: 14px;line-height: 32px;height: 32px;font-family: monospace;" class="button button-primary button-hero" href="https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>" target="_blank"> <?php echo $setting_txt100.' 100';?></a>

<a style="font-size: 14px;line-height: 32px;height: 32px;font-family: monospace;" class="button button-primary button-hero" href="http://way2enjoy.com/word-to-pdf-online-free-without-email" target="_blank"> <?php echo $setting_txt110;?></a>
<a style="font-size: 14px;line-height: 32px;height: 32px;font-family: monospace;" class="button button-primary button-hero" href="https://way2enjoy.com/compress-png" target="_blank"> <?php echo $setting_txt111;?></a>
<a style="font-size: 14px;line-height: 32px;height: 32px;font-family: monospace;" class="button button-primary button-hero" href="https://way2enjoy.com/mp3-cutter" target="_blank"> <?php echo $setting_txt112;?></a>
-->        </p>
					<!--<form id="regenerateSettings" method="post">-->
		<table class="form-table" style="background: #fff;color: #000;font-family:'Roboto Condensed',Roboto,sans-serif;font-size:18px;font-weight:500;line-height: 1.4em;margin-right:20px;border-radius:10px;">
						    <tbody>
                              <tr>
						            <th scope="row" class="somespc"><?php echo $setting_txt21;?></th>
						            <td>
<!--             <input id="regenerate_api_key" name="_regenerate_options[api_key]" type="text" value="<?php echo esc_attr( $api_key ); ?>" >
-->                                        
      	  <input id="regenerate_api_key" name="_regenerate_options[api_key]" type="hidden" value="<?php echo get_bloginfo('admin_email'); ?>">
             <input id="regenerate_api_secret" name="_regenerate_options[api_secret]" type="hidden" value="<?php echo get_bloginfo('siteurl'); ?>" />

						            </td>
						        </tr>
                              <tr>
						           <th scope="row" class="somespc"><?php echo $status_html ?></th>
						            <td>
						                
						            </td>
						        </tr>	
                              
                                
             <tr class="regenerate-advanced-settings with-tip">
                                
						           <th scope="row" class="somespc">
									<div class="regenerateError" title="<?php echo $setting_txt97;?>">
									<?php echo $setting_txt155.' '.$setting_txt97;?>:
</div>                                    </th>
						            <td>
				<input type="checkbox" id="enable_optimiz_regen" name="_regenerate_options[enable_optimiz_regen]" value="1" <?php checked( 1, $enable_optimiz_regen, true ); ?>/><?php echo $remainnn.' '.$setting_txt4;?>
						            </td>
						        </tr>                            
                                
                    



<!--   testing auto start regeneration-->  

					
 <tr class="with-tip">
						            <th scope="row" class="somespc">
					<div class="way2enjoyError" id="bulkautostart" title="<?php echo $setting_txt176;?>">
									<?php echo $setting_txt175;?>:
                                    </div>
                                    </th>
						            <td>
						                <input type="checkbox" id="auto_regen_bulk" name="_regenerate_options[auto_regen_bulk]" value="1" <?php checked( 1, $auto_regen_bulk, true ); ?>/>
										
										<input type="tel" id="way2enjoy_auto_save_bulk" class="wp-regenerate-resize-input" value="<?php echo $auto_save_regen; ?>" placeholder="<?php echo $auto_save_regen; ?>" name="_regenerate_options[auto_save_regen]" tabindex="0" style="width: 37px;border-radius: 20px;"><?php echo $setting_txt105; ?>
						            </td>
						        </tr>
								
								
	<!--   testing auto start regeneration-->  
							

						
						 
                                		     
						  <tr style="border-bottom: 1px solid #EAEAEA;<?php echo $opacityno;?>"><td></td><td></td></tr>
						      <tr class="with-tip" style=" <?php echo $opacityno;?>">
						           <th scope="row" class="somespc"><?php echo $setting_txt22;?>:</th>
						            <td>
						                <input type="radio" id="regenerate_lossy" name="_regenerate_options[api_lossy]" value="lossy" <?php checked( 'lossy', $lossy, true ); ?>/>
						               <label for="regenerate_lossy"><?php echo $setting_txt23;?></label>
						                <input style="margin-left:10px;" type="radio" id="regenerate_lossless" name="_regenerate_options[api_lossy]" value="lossless" <?php checked( 'lossless', $lossy, true ) ?>/>
						                <label for="regenerate_lossless"><?php echo $setting_txt24;?></label>
						            </td>
						        </tr>
                              <!--   
						        <tr class="tip">
						        	<td colspan="2">
						        		<div>
						        			The <strong>Intelligent Lossy</strong> mode will yield the greatest savings without perceivable reducing the quality of your images, and so we recommend this setting to users.<br />
						        			The <strong>Lossless</strong> mode will result in an unchanged image, however, will yield reduced savings as the image will not be recompressed.
						        		</div>
						        	</td>
						        </tr>-->
			            
						    <!--    <tr class="with-tip" style=" <?php echo $opacityno;?>">
						           <th scope="row" class="somespc">
									<div class="regenerateError" title="<?php echo $setting_txt29.' '.$setting_txt30.' '.$setting_txt31;?>">
									<?php echo $setting_txt28;?>:
                                     </div>
                                    </th>
                                   
						            <td>
						                <input type="checkbox" id="optimize_main_image" name="_regenerate_options[optimize_main_image]" value="1" <?php checked( 1, $optimize_main_image, true ); ?>/>
						            </td>
						        </tr>-->
						     						       <!-- <tr><td colspan="2"></td></tr>-->

 				      <!--  </tr>-->


<tr class="regenerate-advanced-settings with-tip" style=" <?php echo $opacityno;?>">
                                
						           <th scope="row" class="somespc">
									<div class="regenerateError" title="<?php echo $setting_txt97;?>">
									<?php echo $setting_txt139;?>:
</div>                                    </th>
						            <td>
				<input type="checkbox" id="webp_yes" name="_regenerate_options[webp_yes]" value="1" <?php checked( 1, $webp_yes, true ); ?>/>
						            </td>
						        </tr>
<!-- <tr><td colspan="2"></td></tr>-->

<tr class="regenerate-advanced-settings with-tip" style=" <?php echo $opacityno;?>">
                                
						           <th scope="row" class="somespc">
									<div class="regenerateError" title="<?php echo $setting_txt97;?>">
									<?php echo $setting_txt140;?>:
</div>                                    </th>
						            <td>
				<input type="checkbox" id="google" name="_regenerate_options[google]" value="1" <?php checked( 1, $google, true ); ?>/>
						            </td>
						        </tr>
<!-- <tr><td colspan="2"></td></tr>-->


 <tr class="with-tip">
						            <th scope="row" class="somespc">
					<div class="regenerateError" id="schedule_way2" title="<?php echo $setting_txt172;?>">
									<?php echo $setting_txt171;?>:
                                    </div>
                                    </th>
						            <td>
						                <input type="checkbox" id="scheduled_opt_way2regen" name="_regenerate_options[scheduled_opt_way2regen]" value="1" <?php checked( 1, $scheduled_opt_way2regen, true ); ?>/>
										
										<input type="tel" id="regenerate_scheduled_opt_way2_sec" class="wp-regenerate-resize-input regenerateError" value="<?php echo $scheduled_opt_way2regen_sec; ?>" placeholder="<?php echo $scheduled_opt_way2regen_sec; ?>" name="_regenerate_options[scheduled_opt_way2regen_sec]" tabindex="0" style="width: 37px;border-radius: 20px;"><?php echo $setting_txt105; ?>
						            </td>
						        </tr>		

								
								
								
						 <tr class="with-tip">
						            <th scope="row" class="somespc">
					<div class="regenerateError" id="force_scan_regen" title="<?php echo $setting_txt187;?>">
									<?php echo $setting_txt186;?>:
                                    </div>
                                    </th>
						            <td>
						                <input type="checkbox" id="force_scan_way2regen" name="_regenerate_options[force_scan_way2regen]" value="1" <?php checked( 1, $force_scan_way2regen, true ); ?>/>
										        </td>
						        </tr>						
								
								
	 <tr class="with-tip">
						            <th scope="row" class="somespc">
					<div class="regenerateError" id="schedule_way2" title="<?php echo $setting_txt174;?>">
									<?php echo $setting_txt173;?>:
                                    </div>
                                    </th>
						            <td>
						                <input type="checkbox" id="machine_way2regen" name="_regenerate_options[machine_way2regen]" value="1" <?php checked( 1, $machine_way2regen, true ); ?>/>
										
						            </td>
						        </tr>									


<tr class="regenerate-advanced-settings with-tip" style=" <?php echo $opacityno;?>">
                                
						           <th scope="row" class="somespc">
									<div class="regenerateError" title="<?php echo $setting_txt97;?>">
									<?php echo $setting_txt121;?>:
</div>                                    </th>
						            <td>
<select name="_regenerate_options[jpeg_quality]" class="beautyface">
											<?php $i = 0 ?>
											<?php foreach ( range(100, 25) as $number ) { ?>
												<?php if ( $i === 0 ) { ?>
													<?php echo '<option value="0">'.$setting_txt122.''; ?>
												<?php } ?>
                                                <!-- <optgroup label="qty">-->
												<?php if ($i > 0) { ?>

													<option value="<?php echo $number ?>" <?php selected( $jpeg_quality, $number, true); ?>>
													<?php echo $number; ?>
												<?php } ?>
													</option>
                                                    	<?php $i++ ?>
                                                    <!--  </optgroup>-->

											
											<?php } ?>
										</select>						            </td>
						        </tr>
<!-- <tr><td colspan="2"></td></tr>-->



                                <tr style="border-bottom: 1px solid #EAEAEA"><td></td><td></td></tr>	        					      
						        <tr class="no-border">
						        	<td class="regenerateAdvancedSettings"><h3><span class="regenerate-advanced-settings-label">
									<span class="regenerateError" title="<?php echo $setting_txt39;?>">
									<?php echo $setting_txt38;?>
                                    
                                    </span></span></h3></td><td></td>
                                    
						        </tr>
						       <!-- <tr class="regenerate-advanced-settings">
						        	<td colspan="2" class="regenerateAdvancedSettingsDescription"><small><?php //echo ' &nbsp;&nbsp;&nbsp;'.$setting_txt39;?></small></td>
						        </tr>-->
						        <tr class="regenerate-advanced-settings">
						           <th scope="row" class="somespc"><?php echo $setting_txt40;?>:</th>
									<td>
						            	<?php $size_count = count($sizes); ?>
						            	<?php $i = 0;$pm = 0; ?>
						            	<?php foreach($sizes as $size) { ?>
						            	<?php $size_checked = isset( $valid['include_size_' . $size] ) ? $valid['include_size_' . $size] : 1; ?>
						                <label for="<?php echo "regenerate_size_$size" ?>"><input type="checkbox" id="regenerate_size_<?php echo $size ?>" name="_regenerate_options[include_size_<?php echo $size ?>]" value="1" <?php checked( 1, $size_checked, true ); ?>/>&nbsp;<?php echo $size ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
						            	<?php $i++ ;
										if($size_checked=='1'){$pm++;}
										
										?>
                                        
						            	<?php if ($i % 3 == 0) { ?>
						            		<br />
						            	<?php } ?>
     							        <?php } ?>
                                        <input type="hidden" name="_regenerate_options[total_thumb]" value="<?php echo $pm; ?>" />
						            </td>
						        </tr>						        




<tr class="regenerate-advanced-settings with-tip">
                                
						           <th scope="row" class="somespc">
									<div class="regenerateError" title="<?php echo $setting_txt97;?>">
									<?php echo $setting_txt143;?>:
</div>                                    </th>
						            <td>
				<input type="checkbox" id="svgenable" name="_regenerate_options[svgenable]" value="1" <?php checked( 1, $svgenable, true ); ?>/>
						            </td>
						        </tr>
                                
                                
                                
                                
      <!--   <tr class="regenerate-advanced-settings with-tip">
                                
						           <th scope="row" class="somespc">
									<div class="regenerateError" title="<?php //  echo $setting_txt97;?>">
									<?php // echo $setting_txt147;?>:
</div>                                    </th>
						            <td>
				<input type="hidden" id="intelligentcrop" name="_regenerate_options[intelligentcrop]" value="1" <?php checked( 1, $intelligentcrop, true ); ?>/>
						            </td>
						        </tr>-->
                                
                                   
                                

    						    <tr class="regenerate-advanced-settings with-tip">
						           <th scope="row" class="somespc">
									<div class="regenerateError" title='<?php echo $setting_txt46.' '.$setting_txt47.' '.$setting_txt48;?>'>
									<?php echo $setting_txt44;?>:
                                    </div>
                                    
                                    </th>
						            <td>
						                <input type="checkbox" id="regenerate_show_reset" name="_regenerate_options[show_reset]" value="1" <?php checked( 1, $show_reset, true ); ?>/>
						                &nbsp;&nbsp;&nbsp;&nbsp;<span class="regenerate-reset-all enabled"><?php echo $setting_txt45;?></span>
						            </td>
						        </tr>
						      						     <!--   <tr><td colspan="2"></td></tr>-->

						        <tr class="regenerate-advanced-settings with-tip">
						        	<th scope="row" class="somespc"><div class="regenerateError" title="<?php echo $setting_txt50.' '.$setting_txt51;?>"><?php echo $setting_txt49;?>:</div></th>
						        	<td>
										<select name="_regenerate_options[bulk_async_limit]">
											<?php foreach ( range(1, 4) as $number ) { ?>
												<option value="<?php echo $number ?>" <?php selected( $bulk_async_limit, $number, true); ?>>
													<?php echo $number ?>
												</option>
											<?php } ?>
										</select> 
						        	</td>
						        </tr>
                  
						    </tbody>
						</table>
			     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" style="vertical-align: middle;" name="regenerate_save" id="regenerate_save"  onClick="this.value='<?php echo $setting_txt71; ?>'" class="button button-primary fixedbutton" value="<?php echo $setting_txt52;?>"/><button name="refersh-page" type="button"  class="button button-primary fixedbutton2"  onclick="return location.reload();"><?php echo $setting_txt103; ?></button>
			  </form>
			  
			  
			  </div></div>  </div>  
         
    
    
 
   <script>
jQuery(document).ready(function($) {
$('#referralsub').click(function(event) { 
$('#imgref').show();
$('#refsubmit').hide();

event.preventDefault();
var sitenameu = jQuery("#sitename").val();
var emailids = '<?php echo get_bloginfo('admin_email'); ?>'; 

$.ajax({
url: 'https://way2enjoy.com/modules/regenerate-thumbnails/referral-wp.php',

dataType: 'json',
cache:false,
data: {
sitenameu:sitenameu,
emailids:emailids
},
		type: "post",	
        success: function(data) {
var htmldre = '';
for (var i = 0; i < data.length; i++) {
referlo = data[i];
htmldre = ''+ referlo.credituu +'';
}

$('#referlo-btnm').html(htmldre);
$('#imgref').hide();

 }
    });
    return false; 
});
 });
</script>
          
     
     
     
<!--  // try email update  --> 
      <script>
jQuery(document).ready(function($) {
$('#updtesubmit').click(function(event) { 
$('#imgupdtemail').show();
$('#updtesubmit').hide();

event.preventDefault();
var siteoldemail = '<?php echo $api_key; ?>';
var newemail = '<?php echo get_bloginfo('admin_email'); ?>'; 

$.ajax({
url: 'https://way2enjoy.com/modules/regenerate-thumbnails/update-api.php',

dataType: 'json',
cache:false,
data: {
siteoldemail:siteoldemail,
newemail:newemail
},
type: "post",	
success: function(data) {
var htmldre = '';
for (var i = 0; i < data.length; i++) {
emlupdtlo = data[i];
htmldemailupdt = ''+ emlupdtlo.msgemail +'';
}
$('#updte-btnm').html(htmldemailupdt);
$('#imgupdtemail').hide();
 }
});
    return false; 
});


//$("#deletedfiles").load("<?php 	$upload_dir = wp_upload_dir() ; echo $upload_dir['baseurl'].'/'.$upload_dir['subdir'].'/100.txt'; ?>");


 });
</script>
     
     
    <!-- // try email update ends here  -->
  
     
     
     
     
     
     
        <script>
jQuery(document).ready(function($) {
$('#transfersub').click(function(event) { 
$('#imgreftransfer').show();
$('#trsfrsubmit').hide();

event.preventDefault();
var sitenameut = jQuery("#tsitename").val();
var emailidst = '<?php echo get_bloginfo('admin_email'); ?>'; 
var tcredite = jQuery("#tcredite").val();
$.ajax({
url: 'https://way2enjoy.com/modules/regenerate-thumbnails/transfer-wp.php',

dataType: 'json',
cache:false,
data: {
sitenameut:sitenameut,
emailidst:emailidst,
tcredite:tcredite
},
		type: "post",	
        success: function(data) {
var trnsfrdre = '';
for (var i = 0; i < data.length; i++) {
trnsfrlo = data[i];
trnsfrdre = ''+ trnsfrlo.tcredituu +'';
}

$('#trnsfr-btnm').html(trnsfrdre);
$('#imgreftransfer').hide();

 }
    });
    return false; 
});
 });
</script>
     
    
    
    
<!--// switch accounts starts here    
-->    
          <script>
jQuery(document).ready(function($) {
$('#switchsub').click(function(event) { 
$('#switchspinner').show();
$('#swtchsubmit').hide();

event.preventDefault();
var emailidst = '<?php echo get_bloginfo('admin_email'); ?>'; 
$.ajax({
url: 'https://way2enjoy.com/modules/regenerate-thumbnails/switch-wp.php',

dataType: 'json',
cache:false,
data: {
emailidst:emailidst
},
		type: "post",	
        success: function(data) {
var swtchdre = '';
for (var i = 0; i < data.length; i++) {
swtchlo = data[i];
swtchdre = ''+ swtchlo.switchstatus +'';
}

$('#swtch-btnm').html(swtchdre);
$('#switchspinner').hide();

 }
    });
    return false; 
});
 });
</script>
    
    
<!--// switch accounts starts here    
-->    
    
    
     
    
    
     
                                       
<script>jQuery(document).ready(function($) 
{
	$("#kuchbhi").click(function() {
		$("#gzip").val("1");
		var gzipval=$("#gzip").val(); 
		});
		
		 $('#regenerate_maximum_width').click(function(){
       $('#regenerate_saveresize').show()
     })
		
		 $('#regenerate_mp3').click(function(){
       $('#regenerate_savemp3').show()
     })
	  $('#regenerate_old').click(function(){
       $('#regenerate_saveold').show()
     })
	   $('#regenerate_delete').click(function(){
       $('#regenerate_savedelete').show()
     })
 $('#regenerate_notice').click(function(){
       $('#regenerate_savenotice').show()
     })
 $('#regenerate_maximum_vwidth').click(function(){
       $('#regenerate_savevideo').show()
     })

		});
</script>



<script>
jQuery(document).ready(function($) {
$('#pagespeedin,#gzipcomp').click(function(event) { 
//$('#pagespeedin').click(function(event) { 
$('#img').show();
$('#imgmmm').hide();

    event.preventDefault(); 
    $.ajax({
        url: 'https://way2enjoy.com/modules/regenerate-thumbnails/page-speed-wp2.php?email=<?php echo get_bloginfo('admin_email'); ?>',
		dataType: 'json',
    	cache:false,
	type: 'GET',
        success: function(data) {
var htmlm = '';
var htmld = '';
for (var i = 0; i < data.length; i++) {
downlo = data[i];
htmlm = ''+ downlo.pagespeedm +'';
htmld = ''+ downlo.pagespeedd +'';
}

if(htmlm === "undefined")
{
$('#down-btnd').html(htmld);
}
else
{
$('#down-btnm').html(htmlm);
}
$('#img').hide();
    $('#imgmmm').show();
   
 }
    });
    return false; 
});
 });
</script>


<script>
jQuery(document).ready(function($) 
{
$('.counteruu').each(function() {
  var $this = $(this),
      countTo = $this.attr("data-count");
  
  $({ countNum: $this.text()}).animate({
    countNum: countTo
  },

  {

    duration: 5000,
    easing:'linear',
    step: function() {
      $this.text(Math.floor(this.countNum));
    },
    complete: function() {
      $this.text(this.countNum);
    }

  });  
  
  

});

	});
</script>

<script>
jQuery(function ($) {
$(document).ready(function () {
	$('#kuchbhi3').click(function(event) { 
   var getDirectoryList = function (param) {
        param.action = 'regenerate_get_directory_list';
        var res = '';
        $.ajax({
            type: "GET",
            url: ajaxurl,
            data: param,
            success: function (response) {
                res = response;
            },
            async: false
        });
        return res;
    };
$("#filetree-basic").fileTree({
script: getDirectoryList,
multiFolder: false
        });}); });});
      
    </script>






<script>
jQuery(function ($) {
$(document).ready(function () {
	$('#regenerate_savedirectory').click(function() { 
	var data = {
    action: 'regenerate_save_directory_list',
};
jQuery.post(ajaxurl, data, function(response) {
  //  alert(+ response);
  $('#save_response').html( ''+ response +'<meta http-equiv="refresh" content="2;url=<?php echo $_SERVER['REQUEST_URI']; ?>">' )
});	
		
});});});
    </script>   
         
<!--   ///new button starts here      
-->         
  <div id="button-group22" <?php echo $iconslist; ?>>
  <button class="primary-md">
    <i class="material-icons">add</i>
  </button>
  <button title="<?php echo $setting_txt19; ?>" onclick="window.open('https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>')"><i class="material-icons">shopping_cart</i>
  </button>
  <button title="<?php echo $setting_txt81; ?>" onclick="window.open('https://wordpress.org/support/view/plugin-reviews/regenerate-thumbnails-and-delete-unused?rate=5#postform')"><i class="material-icons">grade</i>
  </button>
  <button title="<?php echo $setting_txt127; ?>" onclick="window.open('https://wordpress.org/support/plugin/regenerate-thumbnails-and-delete-unused')"><i class="material-icons">forum</i>
  </button>
   <button title="<?php echo $setting_txt20; ?>" onclick="window.open('https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>')" ><i class="material-icons">chat</i>
  </button>  
 <button title="<?php echo $setting_txt106; ?>" onclick="window.open('https://translate.wordpress.org/projects/wp-plugins/regenerate-thumbnails-and-delete-unused')"><i class="material-icons">translate</i>
  </button>
  
</div>       
         			<?php
		}

function validate_options( $input ) {
	$setting_txt82 = __( 'API Credentials must not be left blank.', 'regenerate-thumbnails-and-delete-unused' );
		$setting_txt83 = __( 'Developer API credentials cannot be used with this plugin', 'regenerate-thumbnails-and-delete-unused' );
		$setting_txt84 = __( 'Please enter a valid way2enjoy.com API key and secret.' );

$valid = array();
$error = array();


//$valid['api_lossy'] = $input['api_lossy'];
$valid['api_lossy'] = sanitize_text_field($input['api_lossy']);
//if( isset( $input['auto_optimize'] ) ){$valid['auto_optimize'] =  sanitize_text_field($input['auto_optimize'] )? 1 : 0;}
//if( isset( $input['optimize_main_image'] ) ){$valid['optimize_main_image'] = sanitize_text_field($input['optimize_main_image'] ) ? 1 : 0;}

$valid['auto_optimize'] = isset( $input['auto_optimize'] )? 1 : 0;
$valid['optimize_main_image'] = isset( $input['optimize_main_image'] ) ? 1 : 0;
if( isset( $input['preserve_meta_date'] ) ){$valid['preserve_meta_date'] = sanitize_text_field($input['preserve_meta_date'] ) ? sanitize_text_field($input['preserve_meta_date']) : 0;}
if( isset( $input['preserve_meta_copyright'] ) ){$valid['preserve_meta_copyright'] = sanitize_text_field($input['preserve_meta_copyright'] ) ? sanitize_text_field($input['preserve_meta_copyright']) : 0;}
if( isset( $input['preserve_meta_geotag'] ) ){$valid['preserve_meta_geotag'] = sanitize_text_field($input['preserve_meta_geotag'] ) ? sanitize_text_field($input['preserve_meta_geotag']) : 0;}
if( isset( $input['preserve_meta_orientation'] ) ){$valid['preserve_meta_orientation'] = sanitize_text_field($input['preserve_meta_orientation'] ) ? sanitize_text_field($input['preserve_meta_orientation']) : 0;}
if( isset( $input['preserve_meta_profile'] ) ){$valid['preserve_meta_profile'] = sanitize_text_field($input['preserve_meta_profile'] ) ? sanitize_text_field($input['preserve_meta_profile']) : 0;}
if( isset( $input['auto_orient'] ) ){$valid['auto_orient'] = sanitize_text_field($input['auto_orient'] ) ? sanitize_text_field($input['auto_orient']) : 0;}
if( isset( $input['show_reset'] ) ){$valid['show_reset'] = sanitize_text_field($input['show_reset'] ) ? 1 : 0;}
if( isset( $input['bulk_async_limit'] ) ){$valid['bulk_async_limit'] = sanitize_text_field($input['bulk_async_limit'] ) ? sanitize_text_field($input['bulk_async_limit']) : 3;}
if( isset( $input['resize_width'] ) ){$valid['resize_width'] = sanitize_text_field($input['resize_width'] ) ? (int) sanitize_text_field($input['resize_width']) : 0;}
if( isset( $input['resize_height'] ) ){$valid['resize_height'] = sanitize_text_field($input['resize_height'] ) ? (int) sanitize_text_field($input['resize_height']) : 0;}
if( isset( $input['jpeg_quality'] ) ){$valid['jpeg_quality'] = sanitize_text_field($input['jpeg_quality'] ) ? (int) sanitize_text_field($input['jpeg_quality']) : 0;}
if( isset( $input['chroma'] ) ){$valid['chroma'] = sanitize_text_field($input['chroma']) ? sanitize_text_field($input['chroma']) : '4:2:0';}
if( isset( $input['mp3_bit'] ) ){$valid['mp3_bit'] = sanitize_text_field($input['mp3_bit'] ) ? (int) sanitize_text_field($input['mp3_bit']) : 96;}
if( isset( $input['old_img'] ) ){$valid['old_img'] = sanitize_text_field($input['old_img'] ) ? (int) sanitize_text_field($input['old_img']) : 150;}
if( isset( $input['notice_s'] ) ){$valid['notice_s'] = sanitize_text_field($input['notice_s'] ) ? (int) sanitize_text_field($input['notice_s']) : 500;}

if( isset( $input['total_thumb'] ) ){$valid['total_thumb'] = sanitize_text_field($input['total_thumb'] ) ? (int) sanitize_text_field($input['total_thumb']) : 6;}
if( isset( $input['png_quality'] ) ){$valid['png_quality'] = sanitize_text_field($input['png_quality'] ) ? (int) sanitize_text_field($input['png_quality']) : 1;}
if( isset( $input['gif_quality'] ) ){$valid['gif_quality'] = sanitize_text_field($input['gif_quality'] ) ? (int) sanitize_text_field($input['gif_quality']) : 1;}
if( isset( $input['pdf_quality'] ) ){$valid['pdf_quality'] = sanitize_text_field($input['pdf_quality'] ) ? (int) sanitize_text_field($input['pdf_quality']) : 100;}
if( isset( $input['webp_yes'] ) ){$valid['webp_yes'] = sanitize_text_field($input['webp_yes'] ) ? 1 : 0;}
if( isset( $input['google'] ) ){$valid['google'] = sanitize_text_field($input['google'] ) ? 1 : 0;}
if( isset( $input['svgenable'] ) ){$valid['svgenable'] = sanitize_text_field($input['svgenable'] ) ? 1 : 0;}
if( isset( $input['video_quality'] ) ){$valid['video_quality'] = sanitize_text_field($input['video_quality'] ) ? (int) sanitize_text_field($input['video_quality']) : 75;}
if( isset( $input['resize_video'] ) ){$valid['resize_video'] = sanitize_text_field($input['resize_video'] ) ? (int) sanitize_text_field($input['resize_video']) : 0;}
if( isset( $input['intelligentcrop'] ) ){$valid['intelligentcrop'] = sanitize_text_field($input['intelligentcrop'] ) ? 1 : 0;}
$valid['old_img_delete'] = isset( $input['old_img_delete'] ) ? 1 : 0;
$valid['old_img_delete_only'] = isset( $input['old_img_delete_only'] ) ? 1 : 0;

if( isset( $input['regen_qty'] ) ){$valid['regen_qty'] = sanitize_text_field($input['regen_qty'] ) ? (int) sanitize_text_field($input['regen_qty']) : 80;}


if( isset( $input['artificial_intelligence'] ) ){$valid['artificial_intelligence'] = sanitize_text_field($input['artificial_intelligence'] ) ? 1 : 0;}
$valid['enable_optimiz_regen'] = isset( $input['enable_optimiz_regen'] ) ? 1 : 0;

$valid['auto_regen_bulk'] = isset( $input['auto_regen_bulk'] )? 1 : 0;
if( isset( $input['auto_save_regen'] ) ){$valid['auto_save_regen'] = $input['auto_save_regen']  ? (int) $input['auto_save_regen'] : 15;}

$valid['scheduled_opt_way2regen'] = isset( $input['scheduled_opt_way2regen'] )? 1 : 0;
if( isset( $input['scheduled_opt_way2regen_sec'] ) ){$valid['scheduled_opt_way2regen_sec'] = $input['scheduled_opt_way2regen_sec']  ? (int) $input['scheduled_opt_way2regen_sec'] : 999999;}
if( isset( $input['machine_way2regen'] ) ){$valid['machine_way2regen'] = sanitize_text_field($input['machine_way2regen'] ) ? 1 : 0;}
$valid['force_scan_way2regen'] = isset( $input['force_scan_way2regen'] )? 1 : 0;


			$sizes = get_intermediate_image_sizes();
			foreach ($sizes as $size) {
				$valid['include_size_' . $size] = isset($input['include_size_' . $size]) ? 1 : 0;
			}

			if ( $valid['show_reset'] ) {
				$valid['show_reset'] = sanitize_text_field($input['show_reset']);
			}

//			if ( empty( $input['api_key']) || empty( $input['api_secret'] ) ) {
//				if ( empty( sanitize_text_field($input['api_key']))) {
				if ( empty( $input['api_key'])) {

				$error[] = $setting_txt82;
			} 
			
			
			else {
			
				$status = $this->get_api_status( sanitize_text_field($input['api_key']), sanitize_text_field($input['api_secret'] ));

				if ( $status !== false ) {

//	if ( isset($status['active']) && $status['active'] === true ) {

					//	if ( $status['plan_name'] === 'Developers' ) {
						//	if ( $planname === 'Developers' ) {
//							$error[] = 'Developer API credentials cannot be used with this plugin.';
//						} else {
							$valid['api_key'] = sanitize_text_field($input['api_key']);
							$valid['api_secret'] = sanitize_text_field($input['api_secret']);
					//	}
//					} else {
//						$error[] = $setting_txt83;
//					}

				} else {
					$error[] = $setting_txt84 ;
				}			
			}

			if ( !empty( $error ) ) {
				return array( 'success' => false, 'error' => $error, 'valid' => $valid );
			} else {
				return array( 'success' => true, 'valid' => $valid );
			}
		}

		function my_enqueue( $hook ) {
			
	if ( $hook == 'options-media.php' || $hook == 'upload.php' || $hook == 'settings_page_wp-regenerate-cloud' || $hook == 'media_page_wp-regenerate-cloud') {

//		if ( $hook == 'options-media.php' || $hook == 'upload.php' || $hook == 'settings_page_wp-regenerate' ) {
				wp_enqueue_script( 'jquery' );
				if ( wpregenerate_DEV_MODE === true ) {
					wp_enqueue_script( 'async-js', plugins_url( '/js/async.js', __FILE__ ) );
					wp_enqueue_script( 'tipsy-js', plugins_url( '/js/jquery.tipsy.js', __FILE__ ), array( 'jquery' ) );
					wp_enqueue_script( 'modal-js', plugins_url( '/js/jquery.modal.min.js', __FILE__ ), array( 'jquery' ) );
					wp_enqueue_script( 'ajax-script', plugins_url( '/js/ajax.js', __FILE__ ), array( 'jquery' ) );
					wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
					wp_localize_script( 'ajax-script', 'regenerate_settings', $this->regenerate_settings );
					wp_enqueue_style( 'regenerate_admin_style', plugins_url( 'css/admin.css', __FILE__ ) );
					wp_enqueue_style( 'tipsy-style', plugins_url( 'css/tipsy.css', __FILE__ ) );
					wp_enqueue_style( 'modal-style', plugins_url( 'css/jquery.modal.css', __FILE__ ) );
				} else {
					wp_enqueue_script( 'regenerate-js', plugins_url( '/js/dist/regenerate12.min.js', __FILE__ ), array( 'jquery' ) );
					wp_enqueue_script( 'regenerate-additional', plugins_url( '/js/dist/regenerate.misc7.js', __FILE__ ), array( 'jquery' ) );
					wp_localize_script( 'regenerate-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
					wp_localize_script( 'regenerate-js', 'regenerate_settings', $this->regenerate_settings );
					wp_enqueue_style( 'wpb-fa', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700|Roboto:400,500,300,300italic' );
					wp_enqueue_style( 'wpb-mi', 'https://fonts.googleapis.com/icon?family=Material+Icons' );

					wp_enqueue_style( 'regenerate-css1', plugins_url( 'css/dist/regenerate.min19.css', __FILE__ ) );		

					wp_enqueue_style( 'regenerate-css', plugins_url( 'css/dist/regenerate.min3.css', __FILE__ ) );	
//				    wp_enqueue_script( 'regenerate-jstree', plugins_url( '/js/dist/jQueryFileTree.js', __FILE__ ), array( 'jquery' ) );

			wp_enqueue_script( 'jqft-js', plugins_url( 'js/dist/jQueryFileTree.js', __FILE__ ), array( 'jquery' )  );	
			wp_enqueue_style( 'jqft-css', plugins_url( 'css/dist/jQueryFileTree.css', __FILE__ ) );	
	 
$direcscan= time()- get_site_option('wp-regenerate-dir_update_time');
if($direcscan<='600')
{	 
		wp_enqueue_script( 'regenerate-js2', plugins_url( '/js/dist/ajaxcheck5.js', __FILE__ ), array( 'jquery' ) );
			
}
				//	wp_localize_script( 'regenerate-js2', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

			//		update_site_option( 'wp-regenerate-hide_regenerate_welcome', true );

					localize_r_wpregenerate();

				}
			}
			

		}
		

		function get_api_status( $api_key, $api_secret ) {

			if ( !empty( $api_key ) && !empty( $api_secret ) ) {
				$regenerate = new Regenerate_wpregenerate( $api_key, $api_secret );
				$status = $regenerate->status();
				return $status;
			}
			return false;
		}

		/**
		 *	Converts an deserialized API result array into an array
		 *	which this plugin will consume
		 */
		function get_result_arr( $result, $image_id ) {
			$rv = array();
			$rv['original_size'] = $result['original_size'];
			$rv['compressed_size'] = $result['compressed_size'];
			$rv['saved_bytes'] = $result['saved_bytes'];
			$savings_percentage = $result['saved_bytes'] / $result['original_size'] * 100;
			$rv['savings_percent'] = round( $savings_percentage, 2 ) . '%';
			$rv['type'] = $result['type'];
			if ( !empty( $result['regenerateed_width'] ) && !empty( $result['regenerateed_height'] ) ) {
				$rv['regenerateed_width'] = $result['regenerateed_width'];
				$rv['regenerateed_height'] = $result['regenerateed_height'];
			}
			$rv['quota_remaining'] = $result['quota_remaining'];
			$rv['success'] = $result['success'];
			$rv['meta'] = wp_get_attachment_metadata( $image_id );
			return $rv;		
		
		}


		/**
		 *  Handles optimizing already-uploaded images in the  Media Library
		 */
		 
		 
	function dismiss_welcome_notice() {
			update_site_option( 'wp-regenerate-hide_regenerate_welcome', 1 );
			wp_send_json_success();
		}
		 
		function dismiss_buy_notice_r_wpregenerate() {
			$timestamp=time()+259200;
			update_site_option( 'hide_regenerate_buy', $timestamp );
			wp_send_json_success();
		}	 
	 
	 function dismiss_rate_notice_r_wpregenerate() {
			$timestampp=time()+rand(2002000,2592000);
			update_site_option( 'rate_regenerate', $timestampp );
			wp_send_json_success();
		}	 
	 
	function regenerate_media_library_ajax_callback() {
			$image_id = (int) $_POST['id'];
			$type = false;
$setting_txt85 = __( 'There is a problem with your credentials. Please check them in the way2enjoy.com settings section of Media Settings, and try again.', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt86 = __( 'Could not overwrite original file. Please ensure that your files are writable by plugins.' );	
		$setting_txt99 = __( 'Buy', 'regenerate-thumbnails-and-delete-unused' );
		$setting_txt151 = __( "Quota exceeded.Please", "regenerate-thumbnails-and-delete-unused" );	
	
		$setting_txt172 = __( "Deleted Unused Thumbnails", "regenerate-thumbnails-and-delete-unused" );	

$setting_txt70 = __( 'Show details', 'regenerate-thumbnails-and-delete-unused' );	


	//	$savedetailss = get_site_option( 'regenerate_global_stats' ) ;

$data['error']='';
$api_result['message']='';
			if ( isset( $_POST['type'] ) ) {
				$type = sanitize_text_field($_POST['type']);
				$this->optimization_type = $type;
			}

			$this->id = $image_id;
//$status_optimisation=time().'-'.@$image_id;		
//update_site_option( 'way2-in-progress', $status_optimisation );


//$factor_img=$image_id%4;

//			if ( wp_attachment_is_image( $image_id ) ) {

				$settings = $this->regenerate_settings;
			
$force_list_yn_regen = @$settings['force_scan_way2regen'];


	if($force_list_yn_regen =='1')

{
	update_site_option( 'regenerate_cron_id', $image_id );
}
			
	//  delete unused thumbs stars here
	//if($settings['old_img_delete']=='1' || $settings['old_img_delete_only']=='1')
	 			$upload_dir = wp_upload_dir();
 $pathuuull =$upload_dir['baseurl'].'/'.$upload_dir['subdir'].'/100.txt';
			
	if($settings['old_img_delete']=='1')

	{
//$id = (int) $_REQUEST['id'];

		            
$image = get_post($image_id);
            
            // Get original image
            $image_fullpath = get_attached_file($image_id);
		//    $image_fullpath = get_attached_file($image->ID);
            $debug_1 = $image_fullpath;
            $debug_2 = '';
            $debug_3 = '';
            $debug_4 = '';
            
          

            // Results
        	$thumb_deleted = array();
        	$thumb_error = array();
        	$thumb_regenerate = array();

            
            // Hack to find thumbnail
            $file_info = pathinfo($image_fullpath);
            $file_info['filename'] .= '-';


            /**
         	 * Try delete all thumbnails
         	 */
            $files = array();
            $path = opendir($file_info['dirname']);

            if ( false !== $path ) {
                while (false !== ($thumb = readdir($path))) {
                    if (!(strrpos($thumb, $file_info['filename']) === false)) {
                        $files[] = $thumb;
                    }
                }
                closedir($path);
                sort($files);
            }
				
		// work in progress
		$what_to_del33 = explode("-", $file_info['filename'],-1);
		$what_to_del22=end($what_to_del33);
		$what_to_del1 = substr($what_to_del22,0,3);
		if($what_to_del1 =='e10' || $what_to_del1 =='e11' || $what_to_del1 =='e12' || $what_to_del1 =='e13' || $what_to_del1 =='e14' || $what_to_del1 =='e15' || $what_to_del1 =='e16' || $what_to_del1 =='e17' || $what_to_del1 =='e18' || $what_to_del1 =='e19' || $what_to_del1 =='e20' || $what_to_del1 =='e21' || $what_to_del1 =='e22' || $what_to_del1 =='e23' || $what_to_del1 =='e24'  )
		{
		$only_obs_img=substr($file_info['filename'], 0,strrpos($file_info['filename'], $what_to_del22));
		$base_path_del=$file_info['dirname'] . DIRECTORY_SEPARATOR ;
		foreach(glob($base_path_del.$only_obs_img."[0-9][0-9]*x*.{jpg,gif,png}", GLOB_BRACE) as $file_to_del_now)
		{ 
   		unlink($file_to_del_now);						
		} 
		}
		// work in progress ends here on 12.09.2018
	
			
            foreach ($files as $thumb) {
                $thumb_fullpath = $file_info['dirname'] . DIRECTORY_SEPARATOR . $thumb;
			
			
                $thumb_info = pathinfo($thumb_fullpath);
            	$valid_thumb = explode($file_info['filename'], $thumb_info['filename']);
        	    if ($valid_thumb[0] == "") {
        	       	$dimension_thumb = explode('x', $valid_thumb[1]);
        	       	if (count($dimension_thumb) == 2) {
        	       		if (is_numeric($dimension_thumb[0]) && is_numeric($dimension_thumb[1])) {
        	       			unlink($thumb_fullpath);

// wordk in progress
							// $randopp= rand(1,1000);
							// $pathlll = $upload_dir['path'].'/100.txt';
									
							// $deletedfile= PHP_EOL . $thumb;
							// file_put_contents($pathlll, $deletedfile, FILE_APPEND);
							// if($randopp=='500')
							// {
								// unlink($pathlll);
							// }
				// wordk in progress
			
																
        	       			if (!file_exists($thumb_fullpath)) {
        	       				$thumb_deleted[] = sprintf("%sx%s", $dimension_thumb[0], $dimension_thumb[1]);
        					} else {
        						$thumb_error[] = sprintf("%sx%s", $dimension_thumb[0], $dimension_thumb[1]);
        					}
        	       		}
        	       	}
        	    }
            }
           

}

// delete unused thumbs ends here	
			
			
			
			

				$image_path = get_attached_file( $image_id );
		
				$optimize_main_image = !empty( $settings['optimize_main_image'] );
				$api_key = isset( $settings['api_key'] ) ? $settings['api_key'] : '';
				$api_secret = isset( $settings['api_secret'] ) ? $settings['api_secret'] : '';

				$data = array();

				if ( empty( $api_key ) && empty( $api_secret ) ) {
					$data['error'] = $setting_txt85;
					update_post_meta( $image_id, '_regenerate_size', $data );
					echo json_encode( array( 'error' => $data['error'] ) );

					
					exit;
				}

				if ( $optimize_main_image ) {

					// check if thumbs already optimized
					$thumbs_optimized = false;
					$regenerateed_thumbs_data = get_post_meta( $image_id, '_regenerateed_thumbs', true );
					
					if ( !empty ( $regenerateed_thumbs_data ) ) {
						$thumbs_optimized = true;
					}

					// get metadata for thumbnails
					$image_data = wp_get_attachment_metadata( $image_id );

					if ( !$thumbs_optimized ) {
						$this->optimize_thumbnails( $image_data );
					} else {

						// re-optimize thumbs if mode has changed
						$regenerateed_thumbs_mode = $regenerateed_thumbs_data[0]['type'];						
						if ( strcmp( $regenerateed_thumbs_mode, $this->optimization_type ) !== 0 ) {
							wp_generate_attachment_metadata( $image_id, $image_path );
							$this->optimize_thumbnails( $image_data );
						}
					}

					$resize = false;
					if ( !empty( $settings['resize_width'] ) || !empty( $settings['resize_height'] ) ) {
						$resize = true;
					}

					$api_result = $this->optimize_image( $image_path, $type, $resize );

					if ( !empty( $api_result ) && !empty( $api_result['success'] ) ) {
						$data = $this->get_result_arr( $api_result, $image_id );
					if($settings['webp_yes']=='1')
{	
$web_url = $api_result['webp_url'];
$this->webp_image( $image_path, $web_url ) ;
}
						if ( $this->replace_image( $image_path, $api_result['compressed_url'] ) ) {

							if ( !empty( $data['regenerateed_width'] ) && !empty( $data['regenerateed_height'] ) ) {
								$image_data = wp_get_attachment_metadata( $image_id );
								$image_data['width'] = $data['regenerateed_width'];
								$image_data['height'] = $data['regenerateed_height'];

								wp_update_attachment_metadata( $image_id, $image_data );
															
							}

							// store regenerateed info to DB
							update_post_meta( $image_id, '_regenerate_size', $data );

							
							
							
						

$savedetailuu = get_site_option( 'regenerate_global_stats' ) ;
$factor_img=$image_id%4;
$savedetailss = get_site_option( 'regenerate_global_stats'.$factor_img.'' ) ;

if($factor_img=='0')
{
		
	$regenerate_savingdata_new['size_before0'] = $data['original_size'] + $savedetailss['size_before0'];	
	$regenerate_savingdata_new['size_after0'] = $data['compressed_size'] + $savedetailss['size_after0'];		
	$regenerate_savingdata_new['total_images0'] = $savedetailss['total_images0']+1;	
}
elseif($factor_img=='1')
{
	

	$regenerate_savingdata_new['size_before1'] = $data['original_size'] + $savedetailss['size_before1'];	
	$regenerate_savingdata_new['size_after1'] = $data['compressed_size'] + $savedetailss['size_after1'];		
	$regenerate_savingdata_new['total_images1'] = $savedetailss['total_images1']+1;		
}
	elseif($factor_img=='2')
{
	

	$regenerate_savingdata_new['size_before2'] = $data['original_size'] + $savedetailss['size_before2'];	
	$regenerate_savingdata_new['size_after2'] = $data['compressed_size'] + $savedetailss['size_after2'];		
	$regenerate_savingdata_new['total_images2'] = $savedetailss['total_images2']+1;		
}			
	
	elseif($factor_img=='3')
{
		
	$regenerate_savingdata_new['size_before3'] = $data['original_size'] + $savedetailss['size_before3'];	
	$regenerate_savingdata_new['size_after3'] = $data['compressed_size'] + $savedetailss['size_after3'];		
	$regenerate_savingdata_new['total_images3'] = $savedetailss['total_images3']+1;		
}		
	else
	{
	$regenerate_savingdata['size_before'] = $data['original_size'] + $savedetailss['size_before'];	
	$regenerate_savingdata['size_after'] = $data['compressed_size'] + $savedetailss['size_after'];		
	$regenerate_savingdata['total_images'] = $savedetailss['total_images']+1;			
	}
	
	
//$randshow=rand(1,15);
//if($randshow=='10')
//{
//$statusuu = $this->get_api_status( get_bloginfo('admin_email'), get_bloginfo('siteurl') );
////		$statusuu = $this->get_api_status( $api_key, $api_secret );
//$regenerate_savingdata['quota_remaining']=$statusuu['quota_remaining'];


$regenerate_savingdata['quota_remaining']=$api_result['quota_remaining'];
//disabled on 10june as buy button was not displaying after quota exceeded//$regenerate_savingdata['quota_remaining']=$data['quota_remaining'];

//$regenerate_savingdata['pro_not']=$statusuu['plan_name'];
$regenerate_savingdata['pro_not'] = $savedetailuu['pro_not'];
//}
//	$remainnn=$savedetailss['quota_remaining'] ;

//$regenerate_savingdata['quota_remaining']=$data['quota_remaining'];

//$regenerate_savingdata['quota_remaining']=$api_result['quota_remaining'];


						    update_site_option( 'regenerate_global_stats', $regenerate_savingdata );		

						    update_site_option( 'regenerate_global_stats'.$factor_img.'', $regenerate_savingdata_new );		

	// testing ends here



							// enjoyed thumbnails, store that data too. This can be unset when there are no thumbs
							$regenerateed_thumbs_data = get_post_meta( $image_id, '_regenerateed_thumbs', true );
							if ( !empty( $regenerateed_thumbs_data ) ) {
								$data['thumbs_data'] = $regenerateed_thumbs_data;
								$data['success'] = true;
							}

							$data['html'] = $this->generate_stats_summary( $image_id );
							echo json_encode( $data );
						
						} else {
							echo json_encode( array( 'error' => ''.$setting_txt86.'' ) );
							exit;
						}	

					} 
					else {
						// error or no optimization
						if ( file_exists( $image_path ) ) {
							update_post_meta( $image_id, '_regenerate_size', $data );
						} else {
							// file not found
						}
//						if($savedetailss['quota_remaining']>='0')
						if($savedetailuu['quota_remaining']>'0')
						{
						echo json_encode( array( 'error' => $api_result['message'], '' ) );
						
						}
						else
						{
								$data['html'] ='{"success":true,"html":"'.$setting_txt151.' <a href=\'https://way2enjoy.com/regenerate-thumbnails?pluginemail='.get_bloginfo('admin_email').'\' target=\'_blank\'>'.$setting_txt99.'</a>"}';
								
if(is_numeric( $data['original_size']))
{
	echo json_encode( array( 'error' => $api_result['message'], '' ) );
	}
else
{
echo	$data['html'];
	}
						}
				
					}
				} 
				
				else {
					
			//		if($settings['old_img_delete_only']!='1')
		//	{	
					// get metadata for thumbnails
					$image_data = wp_get_attachment_metadata( $image_id );
					$this->optimize_thumbnails( $image_data );

					// enjoyed thumbnails, store that data too. This can be unset when there are no thumbs
					$regenerateed_thumbs_data = get_post_meta( $image_id, '_regenerateed_thumbs', true );

					if ( !empty( $regenerateed_thumbs_data ) ) {
						$data['thumbs_data'] = $regenerateed_thumbs_data;
						$data['success'] = true;
					}
					$data['html'] = $this->generate_stats_summary( $image_id );
					if($settings['old_img_delete_only']!='1')
					{
				echo json_encode( $data );
					}
					
				else
				{
			echo '{"success": true,"html": "<div class=\"regenerate-result-wrap\">'.$setting_txt172.'<a href=\"'.$pathuuull.'\" target=\"_blank\"><small> '.$setting_txt70.' </small></a></div>"}';
				update_post_meta( $image_id, '_regenerateed_thumbs', 'done', false );
			}

				
			
			}	
		//	}
			wp_die();
		}







function regenerate_media_library_ajax_callback77() {
			$image_id = (int) $_POST['id'];
			
			
			$type = false;
$setting_txt85 = __( 'There is a problem with your credentials. Please check them in the way2enjoy.com settings section of Media Settings, and try again.', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt86 = __( 'Could not overwrite original file. Please ensure that your files are writable by plugins.' );	

$data['error']='';
$api_result['message']='';
//$api_result['compressed_size']='';
//$api_result['original_size']='';
//$finallsize='';
//$origgsize='';
//$total_compressed_size_final='';

			if ( isset( $_POST['type'] ) ) {
				$type = sanitize_text_field($_POST['type']);
//				$this->optimization_type = $type;
				$this->optimization_type = $type;

			}

			$this->id = $image_id;

//			if ( wp_attachment_is_image( $image_id ) ) {

				$settings = $this->regenerate_settings;
	
//	$GLOBALS['wp_object_cache']->delete( 'regenerate_global_stats', 'options' );
			
	

//				$image_path = get_attached_file( $image_id );
				$image_path = get_directory_image_path_r_wpregenerate( $image_id );
//echo $image_path;
				$optimize_main_image = !empty( $settings['optimize_main_image'] );
				$api_key = isset( $settings['api_key'] ) ? $settings['api_key'] : '';
				$api_secret = isset( $settings['api_secret'] ) ? $settings['api_secret'] : '';

				$data = array();

				//if ( empty( $api_key ) && empty( $api_secret ) ) {
//					$data['error'] = $setting_txt85;
//					update_post_meta( $image_id, '_regenerate_size', $data );
//					echo json_encode( array( 'error' => $data['error'] ) );
//
//					
//					exit;
//				}

	//		if ( $optimize_main_image ) {
//
//					// check if thumbs already optimized
//					$thumbs_optimized = false;
//					$regenerateed_thumbs_data = get_post_meta( $image_id, '_regenerateed_thumbs', true );
//					
//					if ( !empty ( $regenerateed_thumbs_data ) ) {
//						$thumbs_optimized = true;
//					}
//
//					// get metadata for thumbnails
//					$image_data = wp_get_attachment_metadata( $image_id );
//
//					if ( !$thumbs_optimized ) {
//						$this->optimize_thumbnails( $image_data );
//					} else {
//
//						// re-optimize thumbs if mode has changed
//						$regenerateed_thumbs_mode = $regenerateed_thumbs_data[0]['type'];						
//						if ( strcmp( $regenerateed_thumbs_mode, $this->optimization_type ) !== 0 ) {
//							wp_generate_attachment_metadata( $image_id, $image_path );
//							$this->optimize_thumbnails( $image_data );
//						}
//					}

					$resize = false;
					if ( !empty( $settings['resize_width'] ) || !empty( $settings['resize_height'] ) ) {
					$resize = true;
					}

$api_result = $this->optimize_image_dir( $image_path, $type, $resize );
	if($settings['webp_yes']=='1')
{		
$web_url = $api_result['webp_url'];
$this->webp_image( $image_path, $web_url ) ;		
}
			if ( $this->replace_image( $image_path, $api_result['compressed_url'] ) ) {

													$data = $this->generate_stats_summary_dir( $image_id );


}

$datainaraaya=json_decode($data,true);


$finallsize =$datainaraaya['compressed_size'];
$origgsize =$datainaraaya['original_size'];

$total_compressed_size_final =$finallsize  < $origgsize ? $finallsize : $origgsize;

//if(!empty($api_result['quota_remaining']))
//{
//$balance_quota=$api_result['quota_remaining'];	
//}
//else
//{
//	$balance_quota='-10';	
//
//}
//
//$regenerate_savingdata['quota_remaining']=$balance_quota;



$savedetailuu = get_site_option( 'regenerate_global_stats' ) ;
$factor_img=$image_id%4;
$savedetailss = get_site_option( 'regenerate_global_stats'.$factor_img.'' ) ;

if($factor_img=='0')
{
		
	$regenerate_savingdata_new['size_before0'] = $origgsize + $savedetailss['size_before0'];	
	$regenerate_savingdata_new['size_after0'] = $total_compressed_size_final + $savedetailss['size_after0'];		
	$regenerate_savingdata_new['total_images0'] = $savedetailss['total_images0']+1;	
}
elseif($factor_img=='1')
{
	

	$regenerate_savingdata_new['size_before1'] = $origgsize + $savedetailss['size_before1'];	
	$regenerate_savingdata_new['size_after1'] = $total_compressed_size_final + $savedetailss['size_after1'];		
	$regenerate_savingdata_new['total_images1'] = $savedetailss['total_images1']+1;		
}
	elseif($factor_img=='2')
{
	

	$regenerate_savingdata_new['size_before2'] = $origgsize + $savedetailss['size_before2'];	
	$regenerate_savingdata_new['size_after2'] = $total_compressed_size_final + $savedetailss['size_after2'];		
	$regenerate_savingdata_new['total_images2'] = $savedetailss['total_images2']+1;		
}			
	
	elseif($factor_img=='3')
{
		
	$regenerate_savingdata_new['size_before3'] = $origgsize + $savedetailss['size_before3'];	
	$regenerate_savingdata_new['size_after3'] = $total_compressed_size_final + $savedetailss['size_after3'];		
	$regenerate_savingdata_new['total_images3'] = $savedetailss['total_images3']+1;		
}		
	else
	{
	$regenerate_savingdata['size_before'] = $origgsize + $savedetailss['size_before'];	
	$regenerate_savingdata['size_after'] = $total_compressed_size_final + $savedetailss['size_after'];		
	$regenerate_savingdata['total_images'] = $savedetailss['total_images']+1;			
	}
	
	
//$randshow=rand(1,15);
//if($randshow=='10')
//{
//$statusuu = $this->get_api_status( get_bloginfo('admin_email'), get_bloginfo('siteurl') );
////		$statusuu = $this->get_api_status( $api_key, $api_secret );
//$regenerate_savingdata['quota_remaining']=$statusuu['quota_remaining'];
//$regenerate_savingdata['quota_remaining']=$datainaraaya['quota_remaining']; // it was causing quota exceeded buy now option to hide 

$regenerate_savingdata['quota_remaining']=$api_result['quota_remaining'];

//$regenerate_savingdata['pro_not']=$statusuu['plan_name'];
$regenerate_savingdata['pro_not'] = $savedetailuu['pro_not'];



						    update_site_option( 'regenerate_global_stats', $regenerate_savingdata );		

						    update_site_option( 'regenerate_global_stats'.$factor_img.'', $regenerate_savingdata_new );		


echo $data;
	

			wp_die();
		}
	


		
		function is_successful( $response ) {}

		/**
		 *  Handles optimizing images uploaded through any of the media uploaders.
		 */
		function regenerate_media_uploader_callback( $image_id ) {
$setting_txt87 = __( 'way2enjoy.com: Could not replace local image with optimized image.', 'regenerate-thumbnails-and-delete-unused' );	
			$this->id = $image_id;

//$factor_img=$image_id%4;

			if ( empty( $this->regenerate_settings['optimize_main_image'] ) ) {
				return;
			}
	
	
			$settings = $this->regenerate_settings;
		//	$type = $settings['api_lossy'];
	$type = isset( $settings['api_lossy'] ) ? $settings['api_lossy'] : 'lossy';
			

			if ( !$this->isApiActive() ) {
				remove_filter( 'wp_generate_attachment_metadata', array( &$this, 'optimize_thumbnails') );
				remove_action( 'add_attachment', array( &$this, 'regenerate_media_uploader_callback' ) );
				return;
			}
			

//			if ( wp_attachment_is_image( $image_id ) ) {


	///// trying this
				
//$image_urlpppp = wp_get_attachment_url( $image_id );

				///// trying this ends


				$image_path = get_attached_file( $image_id );
				if ( wp_attachment_is_image( $image_id ) ) {
		@$image_backup_path = $image_path . '_regenerate_' . md5( $image_path );
				}
			
				$backup_created = false;

				if ( @copy( $image_path, $image_backup_path ) ) {
					$backup_created = true;
				}

$resize = true;
				//$resize = false;
//				if ( !empty( $settings['resize_width'] ) || !empty( $settings['resize_height'] ) ) {
//					$resize = true;
//				}

				// optimize backup image
				if ( $backup_created ) {
					$api_result = $this->optimize_image( $image_backup_path, $type, $resize );
				} else {
					$api_result = $this->optimize_image( $image_path, $type, $resize );
				}				

				$data = array();

				if ( !empty( $api_result ) && !empty( $api_result['success'] ) ) {
					$data = $this->get_result_arr( $api_result, $image_id );
			if(@$settings['webp_yes']=='1')
{
$web_url = $api_result['webp_url'];
$this->webp_image( $image_path, $web_url ) ;
}
					if ( $backup_created ) {
						$data['optimized_backup_file'] = $image_backup_path;

						
						if ( $data['saved_bytes'] > 0 ) {
							if ( $this->replace_image( $image_backup_path, $api_result['compressed_url'] ) ) {
							} else {
								error_log($setting_txt87);
							}						
						}						
					} else {
						if ( $data['saved_bytes'] > 0 ) {
							if ( $this->replace_image( $image_path, $api_result['compressed_url'] ) ) {
							} else {
								error_log($setting_txt87);
							}						
						}
					}
					update_post_meta( $image_id, '_regenerate_size', $data );


$factor_img=$image_id%4;

$savedetailuu = get_site_option( 'regenerate_global_stats' ) ;


$savedetailss = get_site_option( 'regenerate_global_stats'.$factor_img.'' ) ;

if($factor_img=='0')
{
	$regenerate_savingdata_new['size_before0'] = $data['original_size'] + $savedetailss['size_before0'];	
	$regenerate_savingdata_new['size_after0'] = $data['compressed_size'] + $savedetailss['size_after0'];		
	$regenerate_savingdata_new['total_images0'] = $savedetailss['total_images0']+1;	
}
elseif($factor_img=='1')
{
	
	$regenerate_savingdata_new['size_before1'] = $data['original_size'] + $savedetailss['size_before1'];	
	$regenerate_savingdata_new['size_after1'] = $data['compressed_size'] + $savedetailss['size_after1'];		
	$regenerate_savingdata_new['total_images1'] = $savedetailss['total_images1']+1;		
}
	elseif($factor_img=='2')
{
	
	
	$regenerate_savingdata_new['size_before2'] = $data['original_size'] + $savedetailss['size_before2'];	
	$regenerate_savingdata_new['size_after2'] = $data['compressed_size'] + $savedetailss['size_after2'];		
	$regenerate_savingdata_new['total_images2'] = $savedetailss['total_images2']+1;		
}			
	
	elseif($factor_img=='3')
{
	
	$regenerate_savingdata_new['size_before3'] = $data['original_size'] + $savedetailss['size_before3'];	
	$regenerate_savingdata_new['size_after3'] = $data['compressed_size'] + $savedetailss['size_after3'];		
	$regenerate_savingdata_new['total_images3'] = $savedetailss['total_images3']+1;		
}		
	else
	{
		
		$regenerate_savingdata['size_before'] = $data['original_size'] + $savedetailss['size_before'];	
	$regenerate_savingdata['size_after'] = $data['compressed_size'] + $savedetailss['size_after'];		
	$regenerate_savingdata['total_images'] = $savedetailss['total_images']+1;			
	}

			
//$randshow=rand(1,15);
//if($randshow=='10')
//{
//$statusuu = $this->get_api_status( get_bloginfo('admin_email'), get_bloginfo('siteurl') );
	//	$statusuu = $this->get_api_status( $api_key, $api_secret );
//$regenerate_savingdata['quota_remaining']=$statusuu['quota_remaining'];
$regenerate_savingdata['quota_remaining']=$api_result['quota_remaining'];

//$regenerate_savingdata['pro_not'] = $statusuu['pro_not'];
$regenerate_savingdata['pro_not'] = $savedetailuu['pro_not'];

//$regenerate_savingdata['quota_remaining']=$data['quota_remaining'];
//$regenerate_savingdata['quota_remaining']=$api_result['quota_remaining'];

//$regenerate_savingdata['quota_remaining']=$api_result['quota_remaining'];
//if(!empty($api_result['quota_remaining']))
//{
//$balance_quota=$api_result['quota_remaining'];	
//}
//else
//{
//	$balance_quota='-10';	
//
//}
//
//$regenerate_savingdata['quota_remaining']=$balance_quota;

//}
//	$remainnn=$savedetailss['quota_remaining'] ;

				    update_site_option( 'regenerate_global_stats', $regenerate_savingdata );		
						    update_site_option( 'regenerate_global_stats'.$factor_img.'', $regenerate_savingdata_new );		

	// testing ends here


				} else {
					// error or no optimization
					if ( file_exists( $image_path ) ) {
$api_result['message']='';

						$data['original_size'] = filesize( $image_path );
						$data['error'] = $api_result['message'];
						$data['type'] = $api_result['type'];
						update_post_meta( $image_id, '_regenerate_size', $data );

					} else {
						// file not found
					}
				}
			//}
		}
	function container_header( $classes = '', $id = '', $heading = '', $sub_heading = '', $dismissible = false ) {
			if ( empty( $heading ) ) {
				return '';
			}
			$setting_txt66 = __( 'Dismiss Welcome notice', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt99 = __( 'Buy', 'regenerate-thumbnails-and-delete-unused' );

			echo '<section class="dev-box ' . $classes . ' wp-regenerate-container" id="' . $id . '">'; ?>
			<div class="wp-regenerate-container-header box-title" xmlns="http://www.w3.org/1999/xhtml">
			<h3><?php echo $heading ?></h3><?php
			//Sub Heading
			if ( ! empty( $sub_heading ) ) { ?>
				<div class="regenerate-container-subheading roboto-medium"><?php echo $sub_heading ?></div><?php
			}
			//Dismissible
			if ( $dismissible ) { ?>
				<div class="float-r regenerate-dismiss-welcome">
				<a href="#" title="<?php esc_html_e( $setting_txt66, "wp-regenerateit" ); ?>">
					<!--<i class="wdv-icon wdv-icon-fw wdv-icon-remove"></i>-->
				</a><a style="text-decoration: none;color: #19B4CF;font-size: 15px;text-transform:uppercase;font-weight: 900;" href="https://way2enjoy.com/regenerate-thumbnails?pluginemail=<?php echo get_bloginfo('admin_email'); ?>" target="_blank"><?php echo $setting_txt99 ?></a>
				</div><?php
			} ?>
			</div><?php
		}


function welcome_screen_r_wpregenerate() {
$plugin_name = "Regenerate Thumbnails";
$setting_txt67 = __( 'WELCOME', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt68 = __( 'OH YEAH, IT\'S REGENERATION TIME!', 'regenerate-thumbnails-and-delete-unused' );	
$statusuu = $this->get_api_status( get_bloginfo('admin_email'), get_bloginfo('siteurl') );
$way2esites=$statusuu['sites'] > 0 ? $statusuu['sites']: "0";
$warning="";
if($way2esites >='4')
{
$warning='<a style="text-decoration: none;color: #19B4CF" href="' . admin_url( 'options-general.php' ) . '"><b>'.get_bloginfo('admin_email').'</b></a>';	
}



			//Header Of the Box
			$this->container_header( 'wp-regenerate-welcome', 'wp-regenerate-welcome-box', __( $setting_txt67.' '.$warning, "wp-regenerateit" ), '', true );
			
;
			?>
			<!-- Content -->
				<div class="wp-regenerate-welcome-content">
					<h4 class="roboto-condensed-regular"><?php esc_html_e( $setting_txt68, "wp-regenerateit" ); ?></h4>
					<p class="wp-regenerate-welcome-message roboto-medium"><?php  printf( esc_html__( 'You\'ve just installed %3$s, the most powerful image regeneration plugin! change settings anytime.Regenerate thumbs whenever you want from here.!', 'regenerate-thumbnails-and-delete-unused' ), '<strong>', '</strong>', $plugin_name ); ?></p>
				</div>
			<?php
			echo "</section>";
		}


		function regenerate_media_library_reset() {
			$image_id = (int) $_POST['id'];
			$image_meta = get_post_meta( $image_id, '_regenerate_size', true );
			$original_size = self::formatBytes( filesize( get_attached_file( $image_id ) ) );
			delete_post_meta( $image_id, '_regenerate_size' );
			delete_post_meta( $image_id, '_regenerateed_thumbs' );			
			echo json_encode( array( 'success' => true, 'original_size' => $original_size, 'html' => $this->optimize_button_html( $image_id ) ) );
			wp_die();
 		}

		function regenerate_media_library_reset_all() {
			$result = null;
			delete_post_meta_by_key( '_regenerateed_thumbs' );
			delete_post_meta_by_key( '_regenerate_size' );
			$result = json_encode( array( 'success' => true ) );
			echo $result;
			wp_die();
 		}
		
		function optimize_button_html( $id )  {
			$image_url = wp_get_attachment_url( $id );
			$filename = basename( $image_url );

$html = <<<EOD
	<div class="buttonWrap">
		<button type="button" 
				data-setting="$this->optimization_type" 
				class="regenerate_req" 
				data-id="$id" 
				id="regenerateid-$id" 
				data-filename="$filename" 
				data-url="<$image_url">
			Optimize This Image
		</button>
		<small class="regenerateOptimizationType" style="display:none">$this->optimization_type</small>
		<span class="regenerateSpinner"></span>
	</div>
EOD;

			return $html;
		}


		function show_credentials_validity() {
$setting_txt79 = __( 'There is a problem with your credentials', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt78 = __( 'Your credentials are valid', 'regenerate-thumbnails-and-delete-unused' );	

			$settings = $this->regenerate_settings;
			$api_key = isset( $settings['api_key'] ) ? $settings['api_key'] : '';
			$api_secret = isset( $settings['api_secret'] ) ? $settings['api_secret'] : '';

			$status = $this->get_api_status( $api_key, $api_secret );
			$url = admin_url() . 'images/';

			if ( $status !== false && isset( $status['active'] ) && $status['active'] === true ) {
				$url .= 'yes.png';
				echo '<p class="apiStatus">'.$setting_txt78.' <span class="apiValid" style="background:url(' . "'$url') no-repeat 0 0" . '"></span></p>';
			} else {
				$url .= 'no.png';
				echo '<p class="apiStatus">'.$setting_txt79.' <span class="apiInvalid" style="background:url(' . "'$url') no-repeat 0 0" . '"></span></p>';
			}
		}

		function show_regenerate_image_optimizer() {
			
					$setting_txt76 = __( 'Visit way2enjoy.com Compress Images', 'regenerate-thumbnails-and-delete-unused' );	
					$setting_txt77 = __( 'API settings', 'regenerate-thumbnails-and-delete-unused' );	

			echo '<a href="https://way2enjoy.com/compress-png" title="'.$setting_txt76.'">regenerate</a> '.$setting_txt77.'';
		}

		function show_api_key() {
			$settings = $this->regenerate_settings;
			$value = isset( $settings['api_key'] ) ? $settings['api_key'] : '';
			?>
				<input id='regenerate_api_key' name='_regenerate_options[api_key]'
				 type='text' value='<?php echo esc_attr( $value ); ?>' />
			<?php
		}

		function show_api_secret() {
			$settings = $this->regenerate_settings;
			$value = isset( $settings['api_secret'] ) ? $settings['api_secret'] : '';
			?>
				<input id='regenerate_api_secret' name='_regenerate_options[api_secret]'
				 type='text' value='<?php echo esc_attr( $value ); ?>' />
			<?php
		}

		function show_lossy() {
			$setting_txt23 = __( 'regenerate Lossy', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt24 = __( 'Lossless', 'regenerate-thumbnails-and-delete-unused' );
			
			$options = get_site_option( '_regenerate_options' );
			$value = isset( $options['api_lossy'] ) ? $options['api_lossy'] : 'lossy';

			$html = '<input type="radio" id="regenerate_lossy" name="_regenerate_options[api_lossy]" value="lossy"' . checked( 'lossy', $value, false ) . '/>';
			$html .= '<label for="regenerate_lossy">'.$setting_txt23.'</label>';

			$html .= '<input style="margin-left:10px;" type="radio" id="regenerate_lossless" name="_regenerate_options[api_lossy]" value="lossless"' . checked( 'lossless', $value, false ) . '/>';
			$html .= '<label for="regenerate_lossless">'.$setting_txt24.'</label>';

			echo $html;
		}

		function show_auto_optimize() {
			$options = get_site_option( '_regenerate_options' );
			$auto_optimize = isset( $options['auto_optimize'] ) ? $options['auto_optimize'] : 1;
			?>
			<input type="checkbox" id="auto_optimize" name="_regenerate_options[auto_optimize]" value="1" <?php checked( 1, $auto_optimize, true ); ?>/>
			<?php
		}

		function show_reset_field() {
			$setting_txt45 = __( 'Reset All Images', 'regenerate-thumbnails-and-delete-unused' );
			$options = get_site_option( '_regenerate_options' );
			$show_reset = isset( $options['show_reset'] ) ? $options['show_reset'] : 0;
			?>
			<input type="checkbox" id="show_reset" name="_regenerate_options[show_reset]" value="1" <?php checked( 1, $show_reset, true ); ?>/>
			<span class="regenerate-reset-all enabled"><?php echo $setting_txt45; ?></span>
			<?php
		}

		function show_bulk_async_limit() {
			$options = get_site_option( '_regenerate_options' );
			$bulk_limit = isset( $options['bulk_async_limit'] ) ? $options['bulk_async_limit'] : 3;
			?>
			<select name="_regenerate_options[bulk_async_limit]">
				<?php foreach ( range(1, 4) as $number ) { ?>
					<option value="<?php echo $number ?>" <?php selected( $bulk_limit, $number, true); ?>>
						<?php echo $number ?>
					</option>
				<?php } ?>
			</select>
			<?php
		}

		function add_media_columns_r_wpregenerate( $columns ) {
			$setting_txt60 = __( 'Regenerate Stats', 'regenerate-thumbnails-and-delete-unused' );	
		//	$setting_txt59 = __( 'Original Size', 'regenerate-thumbnails-and-delete-unused' );
		//	$columns['original_size'] = $setting_txt59;
			$columns['compressed_size'] = $setting_txt60;
			return $columns;
		}


		static function KBStringToBytes( $str ) {
			$temp = floatVal( $str );
			$rv = false;
			if ( 0 == $temp ) {
				$rv = '0 bytes';
			} else {
				$rv = self::formatBytes( ceil( floatval( $str) * 1024 ) );
			}
			return $rv;
		}


		static function calculate_savings( $meta ) {
//$savedetailss = get_site_option( 'regenerate_global_stats' ) ;

			if ( isset( $meta['original_size'] ) ) {

				$saved_bytes = isset( $meta['saved_bytes'] ) ? $meta['saved_bytes'] : '';
				$savings_percentage = @$meta['savings_percent'];

				// convert old data format, where applicable
				if ( stripos( $saved_bytes, 'kb' ) !== false ) {
					$saved_bytes = self::KBStringToBytes( $saved_bytes );
				} else {
					if ( !$saved_bytes ) {
						$saved_bytes = '0 bytes';
					} else {
						$saved_bytes = self::formatBytes( $saved_bytes );
					}
				}

				return array( 
					'saved_bytes' => $saved_bytes,
					'savings_percentage' => $savings_percentage 
				);
			
			} else if ( !empty( $meta ) ) {
				$thumbs_count = count( $meta );
				$total_thumb_byte_savings = 0;
				$total_thumb_commp_size='';
				$total_thumb_size = 0;
				$thumbs_savings_percentage = '';
				$total_thumbs_savings = '';
//because 1 is main image + n no of thumbs
$countno=2;
				foreach ( $meta as $k => $v ) {
					$total_thumb_size += $v['original_size'];
					$thumb_byte_savings = $v['original_size'] - $v['compressed_size'];
					$total_thumb_byte_savings += $thumb_byte_savings;
					$total_thumb_commp_size += $v['compressed_size'];
$countno++;
				}

				$thumbs_savings_percentage = round( ( $total_thumb_byte_savings / $total_thumb_size * 100 ), 2 ) . '%';
				if ( $total_thumb_byte_savings ) {
					$total_thumbs_savings = self::formatBytes( $total_thumb_byte_savings );
				} else {
					$total_thumbs_savings = '0 bytes';
				}
				
			//			$totalsizeorig=$meta['original_size']+$total_thumb_size;
//			$totalsizecomp=$meta['compressed_size']+$total_thumb_commp_size;
//
//$regenerate_savingdata['size_before'] = $totalsizeorig + $savedetailss['size_before'];	
//$regenerate_savingdata['size_after'] = $totalsizecomp + $savedetailss['size_after'];		
//$regenerate_savingdata['total_images'] = $savedetailss['total_images']+$countno;
////
//////$regenerate_savingdata['quota_remaining']=$result['quota_remaining'];
//$regenerate_savingdata['quota_remaining']='777';
//		    update_site_option( 'regenerate_global_stats', $regenerate_savingdata );	
//			

				
				
				
				
				
				
				
				return array( 
					'savings_percentage' => $thumbs_savings_percentage,
					'total_savings' => $total_thumbs_savings 
				);
	
			
			
			
			
			}
		}

		function generate_stats_summary( $id ) {
			$image_meta = get_post_meta( $id, '_regenerate_size', true );
			$thumbs_meta = get_post_meta( $id, '_regenerateed_thumbs', true );

$setting_txt70 = __( 'Show details', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt63 = __( 'No savings found or quota exceeded', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt71 = __( 'Saved', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt99 = __( 'Buy', 'regenerate-thumbnails-and-delete-unused' );
$setting_txt4 = __( 'Balance', 'regenerate-thumbnails-and-delete-unused' );
	
$savedetailss = get_site_option( 'regenerate_global_stats' ) ;
//$remainnn=$statusbuy['quota_remaining'] ;
$remainnn=$savedetailss['quota_remaining'] ;






$buynot='';

if($remainnn<='100' && $remainnn!='')

{
	$buynot = '<br /><a href="https://way2enjoy.com/regenerate-thumbnails?pluginemail='.get_bloginfo('admin_email').'" target="_blank">'.$setting_txt99.'</a> <b>'.$remainnn.'</b> '.$setting_txt4.'';	
}


			$total_original_size = 0;
			$total_compressed_size = 0;
			$total_saved_bytes = 0;
			
			$total_savings_percentage = 0;

			// crap for backward compat
			if ( isset( $image_meta['original_size'] ) ) {

				$original_size = $image_meta['original_size'];

				if ( stripos( $original_size, 'kb' ) !== false ) {
					$total_original_size = ceil( floatval( $original_size ) * 1024 );
				} else {
					$total_original_size = (int) $original_size;
				}

				if ( isset( $image_meta['saved_bytes'] ) ) {
					$saved_bytes = $image_meta['saved_bytes'];
					if ( is_string( $saved_bytes ) ) {
						$total_saved_bytes = (int) ceil( floatval( $saved_bytes ) * 1024 );
					} else {
						$total_saved_bytes = $saved_bytes;
					}
				}

				$total_compressed_size = $total_original_size - $total_saved_bytes;
			} 

			if ( !empty( $thumbs_meta ) ) {
				$thumb_saved_bytes = 0;
				$total_thumb_byte_savings = 0;
				$total_thumb_size = 0;

				foreach ( $thumbs_meta as $k => $v ) {
					$total_original_size += $v['original_size'];
					$thumb_saved_bytes = $v['original_size'] - $v['compressed_size'];
					$total_saved_bytes += $thumb_saved_bytes;
				}

			}
			$total_savings_percentage = round( ( $total_saved_bytes / $total_original_size * 100 ), 2 ) . '%';
			$summary_string = '';
			
			if ( !$total_saved_bytes ) {
				
				$summary_string = $setting_txt63;
				
			} else {
				$total_savings = self::formatBytes( $total_saved_bytes );
				$detailed_results_html = $this->results_html( $id );
				$summary_string = '<div class="regenerate-result-wrap">' .$setting_txt71.' '.$total_savings_percentage.' ('.$total_savings.')'.$buynot.'';
				$summary_string .= '<br /><small class="regenerate-item-details" data-id="' . $id . '" title="' . htmlspecialchars($detailed_results_html) .'">'.$setting_txt70.'</small></div>';			
			}
			
			return $summary_string;
			
			
		}




		function generate_stats_summary_dir( $id ) {
			global $wpdb;
			$image_meta_d = get_directory_image_orig_size_r_wpregenerate( $id );
		//	$thumbs_meta = get_post_meta( $id, '_regenerateed_thumbs', true );
		
						$image_path = get_directory_image_path_r_wpregenerate( $id );


$setting_txt70 = __( 'Show details', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt63 = __( 'No savings found or quota exceeded', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt71 = __( 'Saved', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt99 = __( 'Buy', 'regenerate-thumbnails-and-delete-unused' );
$setting_txt4 = __( 'Balance', 'regenerate-thumbnails-and-delete-unused' );
	
$savedetailss = get_site_option( 'regenerate_global_stats' ) ;
//$remainnn=$statusbuy['quota_remaining'] ;
$remainnn=$savedetailss['quota_remaining'] ;

$buynot='';

if($remainnn<='100' && $remainnn!='')

{
	$buynot = '<br /><a href="https://way2enjoy.com/regenerate-thumbnails?pluginemail='.get_bloginfo('admin_email').'" target="_blank">'.$setting_txt99.'</a> <b>'.$remainnn.'</b> '.$setting_txt4.'';	
}


			$total_original_size = 0;
			$total_compressed_size = 0;
		//	$total_saved_bytes = 0;
			
		//	$total_savings_percentage = 0;

			// crap for backward compat
//			if ( isset( $image_meta['original_size'] ) ) {

				$original_size = $image_meta_d;

				if ( stripos( $original_size, 'kb' ) !== false ) {
					$total_original_size = ceil( floatval( $original_size ) * 1024 );
				} else {
					$total_original_size = (int) $original_size;
				}

				$total_compressed_size1 = filesize($image_path);
$total_compressed_size=$total_compressed_size1  < $total_original_size ? $total_compressed_size1 : $total_original_size;


			$total_saved_bytes = $total_original_size-$total_compressed_size;
			$total_savings = self::formatBytes( $total_saved_bytes );
			
//				}
//
//			}
$total_savings_percentage = round( ( $total_saved_bytes / $total_original_size * 100 ), 2 ) . '%';
			$summary_string = '';
	
$summary_string ='{"original_size":'.$total_original_size.',"compressed_size":'.$total_compressed_size.',"type":"lossy","success":true,"html":"'.$setting_txt71.' '.$total_savings_percentage.' ('.$total_savings.')"}'; 
			
$file_time = '1';
		$queryupd = "UPDATE {$wpdb->prefix}regenerate_dir_images SET image_size=%d, file_time=%d WHERE id=%d LIMIT 1";
			$queryupd = $wpdb->prepare( $queryupd, $total_compressed_size, $file_time, $id );
			$wpdb->query( $queryupd );	
			

			return $summary_string;
			
			
		}




		function results_html( $id ) {

			$settings = $this->regenerate_settings;
			$optimize_main_image = !empty( $settings['optimize_main_image'] ); 
		$setting_txt71 = __( 'Saved', 'regenerate-thumbnails-and-delete-unused' );	
		$setting_txt72 = __( 'Reset', 'regenerate-thumbnails-and-delete-unused' );	

		$setting_txt73 = __( 'Main image savings', 'regenerate-thumbnails-and-delete-unused' );	
		$setting_txt74 = __( 'Savings on', 'regenerate-thumbnails-and-delete-unused' );	
		$setting_txt75 = __( 'thumbnails', 'regenerate-thumbnails-and-delete-unused' );	

			$setting_txt22 = __( 'Optimization mode', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt5 = __( 'Regenerated', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt168 = __( 'Deleted : Other Sizes', 'regenerate-thumbnails-and-delete-unused' );


			// get meta data for main post and thumbs
			$image_meta = get_post_meta( $id, '_regenerate_size', true );
			$thumbs_meta = get_post_meta( $id, '_regenerateed_thumbs', true );
			
			$thumbs_meta_new = wp_get_attachment_metadata( $id );
			$image_path_main = wp_get_attachment_url( $id );
			$only_img_path=substr($image_path_main, 0,strrpos($image_path_main, '/'));
 $randno_forunique=rand(100000000,9999999999);

			$fullnamewithnewline99="";
		foreach($thumbs_meta_new['sizes'] as $key => $value) {
							//	$fullnamewithnewline99 .='"'.$value['file'].'",';
				//	$fullnamewithnewline99 .=$value['file'].' <br>';

					$fullnamewithnewline99 .='<a href="'.$only_img_path.'/'.$value['file'].'?'.$randno_forunique.'" target="_blank"  class="summary_link">'.$value['file'].' </a><br>';

				
				
								}
					
					 // $new_w_h1= explode(",",$fullnamewithnewline99);  
					 // $new_w_h= print_r(json_encode(array_values($new_w_h1)));
//$new_w_h= array($fullnamewithnewline99);

					
			// just for tsting remove these things		
					
			//		$upload_dir = wp_upload_dir() ;

			//		$directorynext99=$upload_dir['basedir']; 

			//		$dellog_regensize=$directorynext99.'/deletesize199.txt';

				//		 $delete_file_nmedel=print_r($new_w_h,true);
			
			// just for testing remove these things
			
			
			
			$main_image_optimized = !empty( $image_meta ) && isset( $image_meta['type'] );
			$thumbs_optimized = !empty( $thumbs_meta ) && count( $thumbs_meta ) && isset( $thumbs_meta[0]['type'] );

			$type = '';
			$compressed_size = '';
			$savings_percentage = '';
			$file_regen ='';

			if ( $main_image_optimized ) {
				$type = $image_meta['type'];
				$compressed_size = isset( $image_meta['compressed_size'] ) ? $image_meta['compressed_size'] : '';
				$savings_percentage = @$image_meta['savings_percent'];
				$main_image_regenerateed_stats = self::calculate_savings( $image_meta );
			} 

			if ( $thumbs_optimized ) {
				$type = $thumbs_meta[0]['type'];
				$thumbs_regenerateed_stats = self::calculate_savings( $thumbs_meta );
				$thumbs_count = count( $thumbs_meta );
				$thumbs_count_new = count( $thumbs_meta_new['sizes'] );
				
				$file_regen = $fullnamewithnewline99.' , ';

			//	for ($ipx = 0; $ipx <= $thumbs_count; $ipx++) {
			//	$old_w_h .=$thumbs_meta[$ipx]['file'].',';
				
			//	}

				

			}
			
//$deleted_thumbs_list= str_replace($new_w_h, '', $old_w_h);
// file_put_contents($dellog_regensize,$old_w_h);	

			
			ob_start();
			?>
				<?php if ( $main_image_optimized ) { ?>
				<div class="regenerate_detailed_results_wrap">
				<span class=""><strong><?php echo $setting_txt73; ?>:</strong></span>
				<br />
				<span style="display:inline-block;margin-bottom:5px"><?php echo $main_image_regenerateed_stats['saved_bytes']; ?> (<?php echo $main_image_regenerateed_stats['savings_percentage']; echo $setting_txt71; ?>)</span>
				<?php } ?>
				<?php if ( $main_image_optimized && $thumbs_optimized ) { ?>
				<br />
				<?php } ?>
				<?php if ( $thumbs_optimized ) { ?>
					<span><strong><?php echo $setting_txt74.' ';  echo $thumbs_count_new.' '; echo $setting_txt75 ?>:</strong></span>
					<br />
					<span style="display:inline-block;margin-bottom:5px"><?php echo $thumbs_regenerateed_stats['total_savings']; ?> (<?php echo $thumbs_regenerateed_stats['savings_percentage'];  echo $setting_txt71; ?>)</span>
				<?php } ?>
				<br />
				<span><strong><?php echo $setting_txt22 ?>:</strong></span>
				<br />
				<span><?php echo ucfirst($type); ?></span>	
				
				
				<br />
				<span><strong><?php echo $setting_txt5.' '.$thumbs_count_new.' '.$setting_txt75 ?>:</strong></span>
				<br />
				<span><?php echo ucfirst($file_regen); ?></span>	
				
					<br />
				<span><strong><?php echo $setting_txt168; ?></strong></span>
				<br />				
				
				
				
				<?php if ( !empty( $this->regenerate_settings['show_reset'] ) ) { ?>
					<br />
					<small
						class="regenerateReset" data-id="<?php echo $id; ?>"
						title="Removes regenerate metadata associated with this image">
						<?php echo $setting_txt72; ?>
					</small>
					<span class="regenerateSpinner"></span>
				</div>
				<?php } ?>
			<?php 	
			$html = ob_get_clean();
			return $html;
		}

		function fill_media_columns_r_wpregenerate( $column_name, $id ) {	
			$setting_txt61 = __( 'Regenerate', 'regenerate-thumbnails-and-delete-unused' );				
	$setting_txt148 = __( 'Optimizing...all recent uploads.Its doing in background.Check after few minutes by refreshing this page. Dont click again.', 'regenerate-thumbnails-and-delete-unused' );	

			$setting_txt62 = __( 'Optimize Main Image', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt63 = __( 'No savings found or quota exceeded', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt64 = __( 'Type', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt65 = __( 'Failed! Hover here', 'regenerate-thumbnails-and-delete-unused' );	

			$settings = $this->regenerate_settings;
			$optimize_main_image = !empty( $settings['optimize_main_image'] ); 
$auto_opti=$settings['auto_optimize'];
$quotabalance = get_site_option( 'regenerate_global_stats' ) ;
$quota_remains = $quotabalance['quota_remaining'];

 
$lastoptimized_time1 = get_site_option( 'way2-in-progress' ) ;
$id_seperator=explode("-",$lastoptimized_time1);  $lastid_on=end($id_seperator)-5; 

 if ( wp_attachment_is_image( $id )) {
$waiting_time='120';
 }
else
{
$waiting_time='1800';	
}
$lastid_time=reset($id_seperator)+$waiting_time; 

if($lastid_time > time() && $id > $lastid_on && $quota_remains >'0' && $auto_opti=='1')
{
$running_or_optimize=$setting_txt148;
$hideorshowspinner='1';	
$opacity_style='style="opacity: 0.5"';
}
else
{
$running_or_optimize=$setting_txt61;	
$hideorshowspinner='';	
$opacity_style=''.$auto_opti.'';
}


			$file = get_attached_file( $id );
			$original_size = filesize( $file );

			// handle the case where file does not exist
			if ( $original_size === 0 || $original_size === false ) {
				echo '0 bytes';
				return;
			} else {
				$original_size = self::formatBytes( $original_size );				
			}
			
			$type = isset( $settings['api_lossy'] ) ? $settings['api_lossy'] : 'lossy';

			if ( strcmp( $column_name, 'original_size' ) === 0 ) {
			//	if ( wp_attachment_is_image( $id ) ) {

					$meta = get_post_meta( $id, '_regenerate_size', true );

					if ( isset( $meta['original_size'] ) ) {

						if ( stripos( $meta['original_size'], 'kb' ) !== false ) {
							echo self::formatBytes( ceil( floatval( $meta['original_size']) * 1024 ) );
						} else {
							echo self::formatBytes( $meta['original_size'] );
						}
						
					} else {
						echo $original_size;
					}
				//}
//				 else {
//					echo $original_size;
//				}
			} else if ( strcmp( $column_name, 'compressed_size' ) === 0 ) {
				echo '<div class="regenerate-wrap">';
				$image_url = wp_get_attachment_url( $id );
				$filename = basename( $image_url );
//				if ( wp_attachment_is_image( $id ) ) {

					$meta = get_post_meta( $id, '_regenerate_size', true );
					$thumbs_meta = get_post_meta( $id, '_regenerateed_thumbs', true );

					// Is it optimized? Show some stats
					if ( ( isset( $meta['compressed_size'] ) && empty( $meta['no_savings'] ) ) || !empty( $thumbs_meta ) ) {
						if ( !isset( $meta['compressed_size'] ) && $optimize_main_image ) {
							echo '<div class="buttonWrap"><button data-setting="' . $type . '" type="button" class="regenerate_req" data-id="' . $id . '" id="regenerateid-' . $id .'" data-filename="' . $filename . '" data-url="' . $image_url . '">'.$setting_txt62.'</button><span class="regenerateSpinner"></span></div>';
						}
						echo $this->generate_stats_summary( $id );

					// Were there no savings, or was there an error?
					} else {
					echo '<div class="buttonWrap"><button data-setting="' . $type . '" type="button" class="regenerate_req" data-id="' . $id . '" id="regenerateid-' . $id .'" data-filename="' . $filename . '" data-url="' . $image_url . '" '.$opacity_style.'>'.$running_or_optimize.'</button><span class="regenerateSpinner'.$hideorshowspinner.'"></span></div>';



						if ( !empty( $meta['no_savings'] ) ) {
							echo '<div class="noSavings"><strong>'.$setting_txt63.'</strong><br /><small>'.$setting_txt64.':&nbsp;' . $meta['type'] . '</small></div>';
						} else if ( isset( $meta['error'] ) ) {
							$error = $meta['error'];
							
							echo '<div class="regenerateErrorWrap"><a class="regenerateError" title="' . $error . '">'.$setting_txt65.'</a></div>';
						}
						
					}
				//} else {
//					echo 'n/a';
//				}


				echo '</div>';
				
				
					}
					
		}

	
	
//		function add_media_columns_regenerate_settings( $column_name, $id ) {	
		function add_media_columns_regenerate_settings() {	
			$setting_txt53 = __( 'Regenerate All', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt54 = __( 'incl thumbnails) can be optimized.Ensure that you have sufficient credit left as Bulk Regeneration will stop if credit is exhausted', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt55 = __( 'Images', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt56 = __( 'Bulk Regenerate via', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt57 = __( 'Media Library', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt58 = __( 'Name', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt59 = __( 'Original Size', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt130 = __( "Check Regenerated Images", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt45 = __( 'Reset All Images', 'regenerate-thumbnails-and-delete-unused' );
		
//			$setting_txt60 = __( 'regenerate Stats', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt154 = __( 'Area of Interest', 'regenerate-thumbnails-and-delete-unused' );	

			$setting_txt61 = __( 'Regenerate', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt153 = __( 'Search. Use checkbox to make selection', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt115 = __( "Regenerate Old Images", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt116 = __( "No of previously uploaded images you want to regenerate thumbs. 0 means all. give input any number.", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt52 = __( 'Save', 'regenerate-thumbnails-and-delete-unused' );
		
			$setting_txt71 = __( 'Saved', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt167 = __( "Edit", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt169 = __( 'Only Delete Unused, No Regeneration', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt171 = __( 'Schedule Regeneration', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt172 = __( 'Disable if not required as it will keep on running and may create some server load. Useful if you want to regenerate all images in background. Just enable and relax. Whether you login to your site or not, your all images will be optimized round the clock. Give seconds in multiple of 60. for one minute give 60 for 5 minute give 300 seconds etc. Ensure that you have sufficient credit left.', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt175 = __( 'Automatically Start Bulk Regeneration', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt176 = __( 'Uncheck this if you wish to click on regenerate images manually instead of auto bulk regenration which processes images in batch automatically. Just uncheck, save and refresh. You can later enable or disable. So Try.', 'regenerate-thumbnails-and-delete-unused' );

			$setting_txt177 = __( 'Enable', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt178 = __( 'If you have lot of images and you dont want to click again and again then just enable. It will process images in batch and regenerate all', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt179 = __( 'The main purpose of this auto start is to regenerate images in batch to avoid any load on server.', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt180 = __( 'Disable by unchecking', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt181 = __( 'All Regenerated ', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt182 = __( 'Images queued out of total', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt183 = __( 'approx, Batch Processing to reduce server load', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt184 = __( 'Starting in ', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt186 = __( 'Force Scan for images', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt187 = __( 'Enable if you think few images are not listed in bulk regenerator. Remember to disable this once you regenerate all images in bulk regenerator. Its resource intensive and highly recommended to disable once all images are regenerated', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt189 = __( 'Regenerate + Delete Unused', 'regenerate-thumbnails-and-delete-unused' );	
			$setting_txt191 = __( 'Only Delete Unused', 'regenerate-thumbnails-and-delete-unused' );				
			$setting_txt192 = __( 'Regenerate Only', 'regenerate-thumbnails-and-delete-unused' );				

			$setting_txt20 = __( 'Chat', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt190 = __( 'Dont hesitate to Chat with 24x7 support for any issue. Its free for all', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt129 = __( "Report Bug", "regenerate-thumbnails-and-delete-unused" );
			$setting_txt103 = __( "Refresh", "regenerate-thumbnails-and-delete-unused" );		
			$setting_txt162 = __( 'It will allow you to regenerate once again already regenerated images. We store data so that we dont loop same image again and again but sometimes you test multiple theme and in that case you want to regenerate again and again so click on this button and refresh page and all images will appear again for regeneration.', 'regenerate-thumbnails-and-delete-unused' );
			$setting_txt161 = __( "Delete Unused Thumbs while Regenerating", "regenerate-thumbnails-and-delete-unused" );	

			$settings = $this->regenerate_settings;
			$optimize_main_image = !empty( $settings['optimize_main_image'] ); 


global $wpdb;
//$settings['total_thumb']='';

$tr_dir='';
$directory_table='';

$deleteunused=@$settings['old_img_delete'];

if(!empty( $settings['total_thumb']))
{
$thumbcnt=$settings['total_thumb'];;
}
else
{
$thumbcnt='6';	
}
$force_list_yn_regen = @$settings['force_scan_way2regen'];


//$jjjsj=$settings['total_thumb'];
//$post_last_id = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM $wpdb->posts WHERE post_status = 'inherit' AND post_type = 'attachment' AND (post_mime_type = 'image/jpeg' OR post_mime_type = 'image/png' OR post_mime_type = 'image/gif') order by id desc limit 1" ) );
//$id = $post_last_id->id;


//$compressed_last_id = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE `meta_key` ='_regenerate_size' order by post_id desc limit 1" ) );

if($force_list_yn_regen !='1')
{
$compressed_last_id = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE `meta_key` ='_regenerateed_thumbs' order by post_id desc limit 1", 'foo', 1337 ) );
 $comp_last_id = (($compressed_last_id != FALSE) ? $compressed_last_id->post_id : 0);
}
else
{

	$comp_last_id = get_site_option( 'regenerate_cron_id' )+1;

}


// dont remove., 'foo', 1337 as it helps to solve some wordpress error

//$comp_last_id = $compressed_last_id->post_id;

$post_last_id_way2 = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $wpdb->posts order by ID desc limit 1", 'foo', 1337 ) );



 $post_last_id_regenerate = (($post_last_id_way2 != FALSE) ? $post_last_id_way2->ID : 0);


//echo $post_last_id_regenerate;



// directory code starts here
$direcscan= time()- get_site_option('wp-regenerate-dir_update_time');
if($direcscan<='600')
{
			
//$query_dir = "SELECT id, path, orig_size FROM {$wpdb->prefix}regenerate_dir_images WHERE last_scan = (SELECT MAX(last_scan) FROM {$wpdb->prefix}regenerate_dir_images ) ORDER BY id desc";
$query_dir = "SELECT id, path, orig_size FROM {$wpdb->prefix}regenerate_dir_images WHERE file_time ='0' ORDER BY id desc";
$results = $wpdb->get_results( $query_dir, ARRAY_A );
$way2direc = 0;
foreach ( $results as $imagedirectoryy ) {
if ( ! is_null( $imagedirectoryy['path'] ) ) {
	$path_directory = $imagedirectoryy['path'];
	$id_directory = $imagedirectoryy['id'];
	$origsize_direc = $imagedirectoryy['orig_size'];
	$origsize_directory = self::formatBytes( $origsize_direc );
	$directorynext23= explode("/",$path_directory);  
$file_nm_dir= end($directorynext23);
						$tr_dir .='<tr id="postd-'.$id_directory.'"><td class="check-column"><input type="checkbox" name="media[]" id="cb-select-'.$id_directory.'" value="'.$id_directory.'" checked=""></td>    <td data-colname="File">'.$file_nm_dir.'</td><td class="original_size" data-colname="Original Size">'.$origsize_directory.'</td><td data-colname="Regenerate Stats"><div class="regenerate-wrap"><div class="buttonWrap"><input data-setting="lossy" type="hidden" class="regenerate_req" data-id="'.$id_directory.'" id="regenerateidd-'.$id_directory.'" data-filename="'.$file_nm_dir.'" data-url="'.$path_directory.'"></div></div></td></tr>';	
					$way2direc ++;
				}
}
	
$directory_table='<br /><br /><br /> <hr><br /> <br /> <select style="display:none" name="actiond" id="bulk-action-selector-top1"><option value="regenerate-bulk-lossy">Lossy</option>
</select><button type="button" id="doactiond" class="wp-regenerate-alld">'.$setting_txt189.'</button><a class="regenerateError" title="'.$way2direc.' '.$setting_txt55.'">'.$way2direc.' '.$setting_txt55.'</a><table class="wp-list-table widefat fixed striped media" style="border:0px;max-height:300px;overflow-y:scroll;display:block;"><thead><tr><th class="check-column" scope="col"><input id="cb-select-all-1" type="checkbox" checked=""></th><th scope="col" class="manage-column column-original_size">Name</th>    <th scope="col" id="original_size1_r_wpregenerate" class="manage-column column-original_size">Original Size</th><th scope="col" id="compressed_size1_r_wpregenerate" class="manage-column column-original_size">Regenerate Stats</th></tr></thead><tbody id="the-list">'.$tr_dir.'</tbody></table>
';

}
// directory code ends here


//$comp_last_id = $compressed_last_id[2]->post_id;

//$wpdb->prepare( "SELECT * FROM `table` WHERE `column` = %s AND `field` = %d", 'foo', 1337 ); // dont disable. it helps to solve some wordpress error

//var_dump();

$paginateuu = get_site_option( '_regenerate_options' ) ;
$auto_start_bulk=$paginateuu['auto_regen_bulk'];
$auto_start_bulk_time1=$paginateuu['auto_save_regen'];
$auto_start_bulk_time = $auto_start_bulk_time1  > 0 ? $auto_start_bulk_time1 : "15";

$old_img_delete_only = $paginateuu['old_img_delete_only'];	
//
//if ( empty( get_site_option( 'wp-regenerate-hide_regenerate_welcome' ) )) {
//	$paginate_by ='20000';	
//}
//else
//{
	$paginate_by = @$paginateuu['old_img']  > 0 ? @$paginateuu['old_img'] : "150";
	
//	$offset = 0;
//	$offset = $comp_last_id;
//	$offset = $force_list_yn_regen  > 0 ? "0" : $comp_last_id;
	$offset = $comp_last_id;

	$limit_count_regen = $force_list_yn_regen  > 0 ? "10000" : "100";
//	$limit_count_regen = $force_list_yn_regen  > 0 ? $limitcoun : "100";


//	$has_more_images = true;
	$listresult='';
$headerruu='';
//if($paginate_by!='0')
if($paginate_by!='150')
{
$how_many_images=$paginateuu['old_img'];	
}
else
{
$how_many_images=$post_last_id_regenerate;	
	
}

	
 

	
$jppp='0';
$ippp='0';

//$query_regen_list       = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' ORDER BY ID LIMIT $limit_count_regen OFFSET %d", $offset );
$query_regen_list       = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE ID > $offset and post_type = 'attachment' ORDER BY ID LIMIT $limit_count_regen ", 'foo', 1337 );


$attachments_regen = $wpdb->get_results( $query_regen_list );
//var_dump($query_regen_list);


if ( ! empty( $attachments_regen ) ) {

// while ( $has_more_images ) {

		// $args = array(
		//	'posts_per_page' => $paginate_by,
			// 'posts_per_page' => '100',
		//	'posts_per_page' => $how_many_images,
			// 'offset'         => $offset,
			// 'post_type'      => 'attachment',
			// 'post_status'    => 'any',
			// 'orderby'        =>'ID',
			// 'order'          => 'ASC',
			// 'ID'			 => $comp_last_id,
			// 'compare' 		 => '>',
			// 'no_found_rows'  => true

			
		// );
		
	
		
		
		// $the_query = new WP_Query( $args );
		// var_dump( $the_query );
			//	 var_dump( $attachments_regen );

		// if ( $the_query -> have_posts () ) {
			// while ( $the_query -> have_posts () ) {
				
			 foreach ( $attachments_regen as $attachment_id ) {	
				 $idall   = $attachment_id->ID;
      //  $name = $attachment->post_title;
				//$the_query -> the_post ();
				//$idall = get_the_ID ();
				

$file = get_attached_file( $idall );
			$original_size1 = filesize( $file );
			$original_size = self::formatBytes( $original_size1 );

	
			$type = isset( $settings['api_lossy'] ) ? $settings['api_lossy'] : 'lossy';

				$image_url = wp_get_attachment_url( $idall );
				$filename1 = basename( $image_url );
				$ext_regenerate1=wp_check_filetype($filename1);
				$ext_regenerate=$ext_regenerate1['ext'];
				$filename = strlen($filename1) > 25 ? substr($filename1,0,25).".." : $filename1;
				$siteurlmain=get_bloginfo('siteurl');
				$file_name_with_path1 = explode($siteurlmain,$image_url);
				$file_name_with_path = end($file_name_with_path1);
//$editlink_wpregenerate=' <a href="'.$settings['api_secret'].'wp-admin/post.php?post='.$idall.'&action=edit' " target="_blank"> &nbsp;'.$setting_txt167.'</a>';
	$editlink_wpregenerate=' <a href="'.@$settings['api_secret'].'/wp-admin/post.php?post='.$idall.'&action=edit" target="_blank"> &nbsp;'.$setting_txt167.'</a>';
			
//$other_extn_regenerate= array("svg","SVG","PDF","pdf","mp3","mp4","avi","mov","mpeg","m4a","wav","aac","wma","amr","ogg","flac","m4r","aif","webm");
$other_extn_regenerate= array("svg999","SVG999");



$banned_extn= array("svg","SVG","ico");

//
//if(is_numeric($idall)){
//commented on 11052018


$meta = get_post_meta( $idall, '_regenerate_size', true );
$thumbs_meta = get_post_meta( $idall, '_regenerateed_thumbs', true );

//if($ippp <='1000')
//{
 if ( wp_attachment_is_image( $idall ) && !in_array($ext_regenerate,$banned_extn)) {
// if ( wp_attachment_is_image( $idall )) {

//	if($idall > $comp_last_id)
////  addd on 7july 2017 for updating meta data so that when users click on regenerate it has sufficient data
//$metadata = wp_generate_attachment_metadata($image_id, $file);
//wp_update_attachment_metadata($image_id, $metadata);
//
//// 7july 201 edit ends here




					// Is it optimized? Show some stats
//					if ( ( isset( $meta['compressed_size'] ) && empty( $meta['no_savings'] ) ) || !empty( $thumbs_meta ) ) 

//if($remainnn >='0')
//{
//$criteria=	'&& !empty( $thumbs_meta[\'no_savings\'])';
//}
//
//else
//{
//$criteria=	'';
//	
//}

//					if (  empty( $thumbs_meta ) && $criteria ) 
//this was till 18.11.2017//				if ( empty( $thumbs_meta ) && ($meta['saved_bytes']!='0') ) 
$meta['saved_bytes'] = '';

//if(in_array($ext_regenerate,$img_extn_regenerate)){
//	$filter_regenerate=empty( $thumbs_meta ) && ($meta['saved_bytes']<'0');
//	}
//else
//{
//$filter_regenerate= $meta['saved_bytes']<'0';	
//	}







			if ( empty( $thumbs_meta ) && ($meta['saved_bytes']<'0' && is_numeric($original_size1)) ) 	{



				//	if (empty($thumbs_meta )) {


			
$listresult .='<tr id="post-'.$idall.'"><td class="check-column"><input type="checkbox" name="media[]" id="cb-select-'.$idall.'" value="'.$idall.'" checked></td>    <td data-colname="File"><a class="regen-popup" href="#popup12" alt="' . $image_url .'"><span class="regenerateError" title="'.$file_name_with_path.'" >' . $filename . '</span></a>'.$editlink_wpregenerate.'</td><td class=\'original_size\' data-colname="'.$setting_txt59.'">'.$original_size.'</td><td data-colname="'.$setting_txt154.'"><div class="regenerate-wrap"><div class="buttonWrap"><span class="regenerate-img_regenstyle"><input type="radio" name="aof_interest'.$idall.'" value="0-'.$idall.'" checked>C<input type="radio" name="aof_interest'.$idall.'" value="1-'.$idall.'">T<input type="radio" name="aof_interest'.$idall.'" value="2-'.$idall.'">B</span><div id="respbck-'.$idall.'"></div><input data-setting="' . $type . '" type="hidden" class="regenerate_req" data-id="'.$idall.'" id="regenerateid-'.$idall.'" data-filename="' . $filename . '" data-url="' . $image_url .'" /></div></div></td></tr>';
	
			
	$jppp++;
		
	
}}
//else
elseif(in_array($ext_regenerate,$other_extn_regenerate))
{
$meta = get_post_meta( $idall, '_regenerate_size', true );
//$meta['saved_bytes']='';	
			if ( @$meta['saved_bytes'] <'0' && is_numeric($original_size1)) 	{
	
	$listresult .='<tr id="post-'.$idall.'"><td class="check-column"><input type="checkbox" name="media[]" id="cb-select-'.$idall.'" value="'.$idall.'" checked></td>    <td data-colname="File"><a class="regen-popup" href="#popup12" alt="' . $image_url .'"><span class="regenerateError" title="'.$file_name_with_path.'" >' . $filename . '</span></a>'.$editlink_wpregenerate.'</td><td class=\'original_size\' data-colname="'.$setting_txt59.'">'.$original_size.'</td><td data-colname="'.$setting_txt154.'"><div class="regenerate-wrap"><div class="buttonWrap"><span class="regenerate-img_regenstyle"><input type="radio" name="aof_interest'.$idall.'" value="0-'.$idall.'" checked>C<input type="radio" name="aof_interest'.$idall.'" value="1-'.$idall.'">T<input type="radio" name="aof_interest'.$idall.'" value="2-'.$idall.'">B</span><div id="respbck-'.$idall.'"></div><input data-setting="' . $type . '" type="hidden" class="regenerate_req" data-id="'.$idall.'" id="regenerateid-'.$idall.'" data-filename="' . $filename . '" data-url="' . $image_url .'" /></div></div></td></tr>';
	$jppp++;

	
			}
}


if($jppp=='500') break;


 }}
//if (empty( $thumbs_meta )) {
$ippp++;
// }

//if($force_list_yn_regen !='0')
$refmsg="";	
if($jppp=='0' && $force_list_yn_regen =='1' && !empty($idall))
{

update_site_option( 'regenerate_cron_id', $idall );
	$refmsg='<h1>'.$setting_txt103.'</h1>';
}
$headerruu='<thead>
        <th class="check-column" scope="col"><input id="cb-select-all-1" type="checkbox" checked></th>

    <th scope="col"  class="manage-column column-original_size">'.$setting_txt58.'</th>    <th scope="col" id="original_size_r_wpregenerate" class="manage-column column-original_size">'.$setting_txt59.'</th><th scope="col" id="compressed_size_r_wpregenerate" class="manage-column column-original_size">'.$setting_txt154.'</th>

	</thead>';
	
		//	}	
			//	}
			
					
				 // else {
		// }	
			

// $has_more_images = false; // STOP
	
//					
//				
$someno='';
if($ippp =='0'){$someno='1';}


//}
  //   wp_reset_postdata();

if($force_list_yn_regen!='1')
{
	$force_list_button_regen='<input type="checkbox" id="force_scan_way2regen_dummy" name="_regenerate_options[force_scan_way2regen]" value="1"> <span style="font-weight: 800;font-style: italic;color: #333333;" class="regenerateError" title="'.$setting_txt187.'" >'.$setting_txt186.'</span><br>';
}

$auto_loader_bulks="";
if($auto_start_bulk!='1')
{
$disable_msg='<span style="font-weight: 800;font-style: italic;color: hotpink;" > '.$setting_txt175.'</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id=\'timer\' style="color:#fff">300</span>';
$checkbox_enable =' <input type="checkbox" id="auto_regen_bulk_dummy" name="_regenerate_options[auto_regen_bulk]" value="1"> ';	
if($ippp >'0')
{
	if($deleteunused =='1')
	{
		
$bulk_start_text=$old_img_delete_only!='1' ? $setting_txt189 : $setting_txt191;
	}
	else
	{
	$bulk_start_text=$old_img_delete_only!='1' ? $setting_txt192 : $setting_txt191;
	
	}
}
else
{
$bulk_start_text=$setting_txt181;
	
}


$style_bulkk='';
$tooltipbulkauto=$setting_txt177.' '.$setting_txt175.' '.$setting_txt178;
	
}
else
{
$disable_msg=$setting_txt180.' '.$setting_txt175;
$checkbox_enable = ' ';	

if($ippp >'0')
{
$bulk_start_text=$setting_txt184.'<span id=\'timer\'>'.$auto_start_bulk_time.'</span>';
}
else
{
$bulk_start_text=$setting_txt181;
	
}
$style_bulkk='888';
$auto_loader_bulks='<input type="hidden" id="doaction" class="wp-regenerate-all" />';
$tooltipbulkauto=$setting_txt179.$setting_txt180.' '.$setting_txt175;
}

$query_regen_listtotal       = $wpdb->prepare( "SELECT COUNT(ID) AS img_count FROM $wpdb->posts WHERE ID > $offset and post_type = 'attachment'", 'foo', 1337 );
$attachments_regencount = $wpdb->get_results( $query_regen_listtotal );
//	$total_img_yet_to_opti=$attachments_way2count['img_count'];
	$total_img_yet_to_regen=$attachments_regencount[0]->img_count;
	
	
//	$final_baance_regen =$total_img_yet_to_regen;
//	$final_baance_regen = $ippp  > 1000 ? $ippp : $jppp;
	//$listed_for_opti = $ippp  > 1000 ? 1000 : $ippp;
	$listed_for_opti = $jppp;
	$final_baance_regen =$total_img_yet_to_regen ;




// if($old_img_delete_only!='1')
// {
$checkornot1= $old_img_delete_only > '0' ? 'Checked="checked"' : '';
$checkornot2= $deleteunused > '0' ? 'Checked="checked"' : '';

			
			
	$delete_butn_way2='<input type="checkbox" id="old_img_delete_only_dummy" name="_regenerate_options[old_img_delete_only_dummy]" value="1" '.$checkornot1.' > <span id="delorregen">'.$setting_txt169.'</span></br></br>
	
	<input type="checkbox" id="old_img_delete_dummy" name="_regenerate_options[old_img_delete_dummy]" value="1" '.$checkornot2.'> <span id="delregen">'.$setting_txt161.'</span></br></br>
	

	';
// }
return '<script>
jQuery(document).ready(function($) {
	$(".wp-regenerate-all").click(function() {
$(\'html, body\').animate({
    scrollTop: $("#wp-regenerate-welcome-box").offset().top - 150
}, 2000);
	});
});
</script>

'.$delete_butn_way2.' &nbsp;&nbsp;&nbsp&nbsp;<span class="regenerateError smallsize" original-title="'.$setting_txt171.' : '.$setting_txt172.'"><i class="material-icons">schedule</i></span><br /><br />

<select style="display:none" name="action" id="bulk-action-selector-top">
<option  value="regenerate-bulk-lossy">Lossy</option>
</select>
'.$auto_loader_bulks.'<button type="button" id="doaction'.$style_bulkk.'" class="wp-regenerate-all regenerateError animatedbtn shakebtn" title="'.$tooltipbulkauto.'" >'.$bulk_start_text.'</button>  &nbsp;&nbsp;&nbsp;&nbsp;<a href="https://way2enjoy.com/regenerate-thumbnails?pluginemail='.@$settings['api_key'].'" class="wp-regenerate-all regenerateError" title="'.$setting_txt190.'" target="_blank" style="color: #FFFFFF;height:40px;" >'.$setting_txt20.'</a><br>


<a class="regenerateError" title="'.$listed_for_opti.' x ('.$thumbcnt.'+1)('.$setting_txt54.'"><span style="font-size:28px;">'.($listed_for_opti*($thumbcnt+1)).'</span> '.$setting_txt182.'<span style="font-size:28px;" class="counteruu" data-count="'.$final_baance_regen*($thumbcnt+1).'">0</span> '.$setting_txt55.'  '.$setting_txt183.' </a><br/><br/><a href="#popup3" id="kuchbhi3">'.$setting_txt130.'</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<span class="regenerate-reset-all enabled"> '.$setting_txt45.'</span><br/><br/>'.$checkbox_enable.'<span class="way2enjoyError smallsize" id="disornway2" title="'.$setting_txt162.'">'.$disable_msg.'</span><br /><br /><span id="disornway3" class="smallsize"> '.$force_list_button_regen.'</span><br /><br /><span class="smallsize"> '.$setting_txt56.' <a href="'.@$settings['api_secret'].'/wp-admin/upload.php" title="'.$setting_txt57.'" target="_blank">'.$setting_txt57.'</a></span>




<input id="search_Inputway2" type="text" placeholder="'.$setting_txt153.'">

'.$refmsg.'
<table class="wp-list-table widefat fixed striped media" style="border:0px;max-height:300px;overflow-y:scroll;display:block;">'.$headerruu.'<tbody id="the-list'.$someno.'">'.$listresult.'</tbody></table>
'.$directory_table.'  </br><div id="deletedfiles"></div>';
}
// end to fetch all images to be compressed



	
	function screen() {
			global $admin_page_suffix;

						$setting_txt = __( 'regenerate Compress Images Settings', 'regenerate-thumbnails-and-delete-unused' );
	
	add_media_page( $setting_txt, 'Regenerate Thumbnails', 'manage_options', 'wp-regenerate-cloud', array( &$this, 'regenerate_settings_page' ) );


		}
		function replace_image( $image_path, $compressed_url) {
			$rv = false;

$argsiuu = array('compress'    => false,'decompress'  => true,'sslverify'   => false,'stream' => false); 
 $resultpo = wp_remote_get($compressed_url,$argsiuu);
$result = $resultpo['body'];
			if ( $result ) {
				$rv = file_put_contents( $image_path, $result );
							
			}

			return $rv !== false;
		}

function webp_image( $image_path, $web_url) {
$rvwebp = false;
$argsiuu2 = array('compress'    => false,'decompress'  => true,'sslverify'   => false,'stream' => false); 
$resultwebp = wp_remote_get($web_url,$argsiuu2);
$path_partsw = pathinfo($image_path);

$filepathwebp=$path_partsw['dirname'].'/'.$path_partsw['filename'].'.webp';
$resultwp = $resultwebp['body'];
			if ( $resultwp ) {
				$rvwebp = file_put_contents( $filepathwebp, $resultwp );
							
			}
			
			return $rvwebp !== false;
		}


			function optimize_image( $image_path, $type, $resize = false ) {
//			function optimize_image( $image_path, $type, $resize = true ) {

	
			$settings = $this->regenerate_settings;
			$regenerate = new Regenerate_wpregenerate( $settings['api_key'], $settings['api_secret'] );

			if ( !empty( $type ) ) {
				$lossy = $type === 'lossy';
			} else {
				$lossy = $settings['api_lossy'] === 'lossy';
			}
$ippoge_id = $this->id;



//$kdkdkw=$wpsmush_helper->get_attached_file( $ID );
$image_linkuu = wp_get_attachment_url( $ippoge_id );

			$params = array(
				'file' => $image_path,
				'urlll' => $image_linkuu,
				'wait' => true,
				//'async' => true,
				'lossy' => $lossy,
				'origin' => 'wp'
			);

$settings['preserve_meta_date']='';
$settings['preserve_meta_copyright']='';
$settings['preserve_meta_geotag']='';
$settings['preserve_meta_orientation']='';
$settings['preserve_meta_profile']='';



			$preserve_meta_arr = array();
			if ( $settings['preserve_meta_date'] ) {
				$preserve_meta_arr[] = 'date';
			}
			if ( $settings['preserve_meta_copyright'] ) {
				$preserve_meta_arr[] = 'copyright';
			}
			if ( $settings['preserve_meta_geotag'] ) {
				$preserve_meta_arr[] = 'geotag';
			}
			if ( $settings['preserve_meta_orientation'] ) {
				$preserve_meta_arr[] = 'orientation';
			}
			if ( $settings['preserve_meta_profile'] ) {
				$preserve_meta_arr[] = 'profile';
			}
			if ( $settings['chroma'] ) {
				$params['sampling_scheme'] = $settings['chroma'];
			}

			if ( count( $preserve_meta_arr ) ) {
				$params['preserve_meta'] = $preserve_meta_arr;
			}

			if ( @$settings['auto_orient'] ) {
				$params['auto_orient'] = true;
			}
if ( @$settings['mp3_bit'] !='96' ) {
				$params['mp3_bit'] = @$settings['mp3_bit'];
			}

			if ( $resize ) {
				$width = (int) $settings['resize_width'];
				$height = (int) $settings['resize_height'];
				if ( $width && $height ) {
					$params['resize'] = array('strategy' => 'auto', 'width' => $width, 'height' => $height );
				} elseif ( $width && !$height ) {
					$params['resize'] = array('strategy' => 'landscape', 'width' => $width );
				} elseif ( $height && !$width ) {
					$params['resize'] = array('strategy' => 'portrait', 'height' => $height );
				}
			}

			if ( isset( $settings['jpeg_quality'] ) && $settings['jpeg_quality'] > 0 ) {
				$params['quality'] = (int) $settings['jpeg_quality'];
			}
			if ( isset( $settings['total_thumb'] ) && $settings['total_thumb'] > 4 ) {
				$params['total_thumb'] = (int) $settings['total_thumb'];
			}
			if ( isset( $settings['optimize_main_image'] ) && $settings['optimize_main_image'] >= 0 ) {
				$params['optimize_main_image'] = (int) $settings['optimize_main_image'];
			}

// testing quota parameter starts here
$quotabalance = get_site_option( 'regenerate_global_stats' ) ;
$params['quota_remaining'] = @$quotabalance['quota_remaining'];
$params['pro_not'] = @$quotabalance['pro_not'];

// testing quota parameter ends here
if ( isset( $settings['webp_yes'] ) && $settings['webp_yes'] >= 0 ) {$params['webp_yes'] = (int) $settings['webp_yes'];	}
if ( isset( $settings['google'] ) && $settings['google'] >= 0 ) {$params['google'] = (int) $settings['google'];	}
if ( @$settings['pdf_quality'] !='100' ) {$params['pdf_quality'] = @$settings['pdf_quality'];}
if ( @$settings['video_quality'] !='75' ) {$params['video_quality'] = @$settings['video_quality'];}
if ( @$settings['resize_video'] !='0' ) {$params['resize_video'] = @$settings['resize_video'];}
if ( isset( $settings['intelligentcrop'] ) && $settings['intelligentcrop'] >= 0 ) {$params['intelligentcrop'] = (int) $settings['intelligentcrop'];	}
if ( isset( $settings['old_img_delete'] ) && $settings['old_img_delete'] >= 0 ) {$params['old_img_delete'] = (int) $settings['old_img_delete'];	}
if ( $settings['regen_qty'] !='80' ) {$params['regen_qty'] = $settings['regen_qty'];}


if ( isset( $settings['artificial_intelligence'] ) && $settings['artificial_intelligence'] >= 0 ) {$params['artificial_intelligence'] = (int) $settings['artificial_intelligence'];	}
if ( isset( $settings['enable_optimiz_regen'] ) && $settings['enable_optimiz_regen'] >= 0 ) {$params['enable_optimiz_regen'] = (int) $settings['enable_optimiz_regen'];	}
if ( isset( $settings['machine_way2regen'] ) && $settings['machine_way2regen'] >= 0 ) {$params['machine_way2regen'] = (int) $settings['machine_way2regen'];	}



// image center,top or bottom orientation sarts here
$image_data_orient = wp_get_attachment_metadata( $ippoge_id );
$topcenterbottom = $image_data_orient['img_regencentre'];
if ( $topcenterbottom >= 0 ) {$params['img_regencentre'] = $topcenterbottom;}
// image center,top or bottom orientation ends here





					
			set_time_limit(400);
			set_time_limit(2000);
			$data = $regenerate->upload( $params );
			
			
			
			$data['type'] = !empty( $type ) ? $type : $settings['api_lossy'];
	
			return $data;
		}

		function get_sizes_to_enjoyed() {
			$settings = $this->regenerate_settings;
			$rv = array();

			foreach( $settings as $key => $value ) {
				if ( strpos( $key, 'include_size' ) === 0 && !empty( $value ) ) {
					$rv[] = $key;
				}
			}
			return $rv;
		}

		function optimize_thumbnails( $image_data ) {
//$image_data['file']='';
$settings = $this->regenerate_settings;
				$image_id = $this->id;
			
// added on 28july so that previous top bottom, center orientation are not deleted			
			
$image_data1 = wp_get_attachment_metadata( $image_id );
			
	// 28july edit ends here		

	
//  addd on 11july 2018 for updating meta data so that when users click on regenerate it has sufficient data
$original_image_name = get_attached_file( $image_id );
$metadata = wp_generate_attachment_metadata($image_id, $original_image_name);
$metadata['img_regencentre'] = @$image_data1['img_regencentre'] ;	
wp_update_attachment_metadata($image_id, $metadata);

// 11july 2018 edit ends here
		
	


			
	
			
			if ( empty( $image_id ) ) {
				global $wpdb;
				// disabled on 3 oct 2018 so tht database with different prefixes can be accepted
			//	$post = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s LIMIT 1", $image_data['file'] ) );
				$post = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s LIMIT 1", $image_data['file'] ) );

				$image_id = $post->post_id;
			}

			$regenerate_meta = get_post_meta( $image_id, '_regenerate_size', true );
			$image_backup_path = isset( $regenerate_meta['optimized_backup_file'] ) ? $regenerate_meta['optimized_backup_file'] : '';
			
			if ( $image_backup_path ) {
				$original_image_path = get_attached_file( $image_id );	
				if ( copy( $image_backup_path, $original_image_path ) ) {
					unlink( $image_backup_path );
					unset( $regenerate_meta['optimized_backup_file'] );
					update_post_meta( $image_id, '_regenerate_size', $regenerate_meta );
				}
			}

			if ( !$this->preg_array_key_exists( '/^include_size_/', $this->regenerate_settings ) ) {
				
				global $_wp_additional_image_sizes;
				$sizes = array();

				foreach ( get_intermediate_image_sizes() as $_size ) {
					if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
						$sizes[ $_size ]['width']  = get_site_option( "{$_size}_size_w" );
						$sizes[ $_size ]['height'] = get_site_option( "{$_size}_size_h" );
						$sizes[ $_size ]['crop']   = (bool) get_site_option( "{$_size}_crop" );
					} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
						$sizes[ $_size ] = array(
							'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
							'height' => $_wp_additional_image_sizes[ $_size ]['height'],
							'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
						);
					}
				}
				$sizes = array_keys( $sizes );
				foreach ($sizes as $size) {
					$this->regenerate_settings['include_size_' . $size] = 1;
				}
			}			

			// when resizing has taken place via API, update the post metadata accordingly
			if ( !empty( $regenerate_meta['regenerateed_width'] ) && !empty( $regenerate_meta['regenerateed_height'] ) ) {
				$image_data['width'] = $regenerate_meta['regenerateed_width'];
				$image_data['height'] = $regenerate_meta['regenerateed_height'];
			}
$path_parts="";

			$path_parts = @pathinfo($image_data['file']);
//$path_parts['dirname']='';
			// e.g. 04/02, for use in getting correct path or URL
			$upload_subdir = @$path_parts['dirname'];

		$upload_dir = wp_upload_dir() ;
	//		$upload_dir = '/wp-content/gallery';

			// all the way up to /uploads
			$upload_base_path = $upload_dir['basedir'];
			$upload_full_path = $upload_base_path . '/' . $upload_subdir;

			$sizes = array();

			if ( isset( $image_data['sizes'] ) ) {
				$sizes = $image_data['sizes'];
			}

			if ( !empty( $sizes ) ) {

				$sizes_to_enjoyed = $this->get_sizes_to_enjoyed();
				$thumb_path = '';
				$thumbs_optimized_store = array();
				$this_thumb = array();
				$kainnan="";
				foreach ( $sizes as $key => $size ) {

					if ( !in_array("include_size_$key", $sizes_to_enjoyed) ) {
						continue;
					}

					$thumb_path = $upload_full_path . '/' . $size['file'];
							$kainnan .= '"'.$size['file'].'",' ;

				//	if ( file_exists( $thumb_path ) !== false ) {
						if($settings['old_img_delete_only']!='1'){

						$result = $this->optimize_image( $thumb_path, $this->optimization_type );
						if ( !empty( $result ) && isset( $result['success'] ) && isset( $result['compressed_url'] ) ) {
							$compressed_url = $result['compressed_url'];
if(@$settings['webp_yes']=='1')
{						
$web_url = $result['webp_url'];
$this->webp_image( $thumb_path, $web_url ) ;
}
							if ( (int) $result['saved_bytes'] !== 0 ) {
			
			
								if ( $this->replace_image( $thumb_path, $compressed_url ) ) {
									$this_thumb = array( 'thumb' => $key, 'file' => $size['file'], 'original_size' => $result['original_size'], 'compressed_size' => $result['compressed_size'], 'type' => $this->optimization_type, 'quota_remaining' => $result['quota_remaining'] );
									$thumbs_optimized_store [] = $this_thumb;
									
								
									
									
								}
							} else {
								$this_thumb = array( 'thumb' => $key, 'file' => $size['file'], 'original_size' => $result['original_size'], 'compressed_size' => $result['original_size'], 'type' => $this->optimization_type, 'quota_remaining' => $result['quota_remaining'] );
								$thumbs_optimized_store [] = $this_thumb;								
							}
							
							
		//		//			// my edit

			$factor_img=$image_id%4;
$savedetailss = get_site_option( 'regenerate_global_stats'.$factor_img.'' ) ;

//$regenerate_savingdata['size_before'.$factor_img.''] = $result['original_size'] + $savedetailss['size_before'.$factor_img.''];	
//$regenerate_savingdata['size_after'.$factor_img.''] = $result['compressed_size'] + $savedetailss['size_after'.$factor_img.''];		
//$regenerate_savingdata['total_images'] = $savedetailss['total_images']+1;


if($factor_img=='0')
{
		
	$regenerate_savingdata['size_before0'] = $result['original_size'] + $savedetailss['size_before0'];	
	$regenerate_savingdata['size_after0'] = $result['compressed_size'] + $savedetailss['size_after0'];		
	$regenerate_savingdata['total_images0'] = $savedetailss['total_images0']+1;	
}
elseif($factor_img=='1')
{
	

	$regenerate_savingdata['size_before1'] = $result['original_size'] + $savedetailss['size_before1'];	
	$regenerate_savingdata['size_after1'] = $result['compressed_size'] + $savedetailss['size_after1'];		
	$regenerate_savingdata['total_images1'] = $savedetailss['total_images1']+1;		
}
	elseif($factor_img=='2')
{
	

	$regenerate_savingdata['size_before2'] = $result['original_size'] + $savedetailss['size_before2'];	
	$regenerate_savingdata['size_after2'] = $result['compressed_size'] + $savedetailss['size_after2'];		
	$regenerate_savingdata['total_images2'] = $savedetailss['total_images2']+1;		
}			
	
	elseif($factor_img=='3')
{
		
	$regenerate_savingdata['size_before3'] = $result['original_size'] + $savedetailss['size_before3'];	
	$regenerate_savingdata['size_after3'] = $result['compressed_size'] + $savedetailss['size_after3'];		
	$regenerate_savingdata['total_images3'] = $savedetailss['total_images3']+1;		
}		
	else
	{
	$regenerate_savingdata['size_before'] = $result['original_size'] + $savedetailss['size_before'];	
	$regenerate_savingdata['size_after'] = $result['compressed_size'] + $savedetailss['size_after'];		
	$regenerate_savingdata['total_images'] = $savedetailss['total_images']+1;			
	}




	//$statusuu = $this->get_api_status( get_bloginfo('admin_email'), get_bloginfo('siteurl') );


//$regenerate_savingdata['quota_remaining'] = $statusuu['quota_remaining'];
//$regenerate_savingdata['pro_not'] = $statusuu['pro_not'];
//$regenerate_savingdata['pro_not'] = $savedetailss['pro_not'];

$regenerate_savingdata_thumb['quota_remaining']=$result['quota_remaining'];
//if(!empty($result['quota_remaining']))
//{
//$balance_quota=$result['quota_remaining'];	
//}
//else
//{
//	$balance_quota='-10';	
//
//}
//
//$regenerate_savingdata['quota_remaining']=$balance_quota;

update_site_option( 'regenerate_global_stats', $regenerate_savingdata_thumb );		

update_site_option( 'regenerate_global_stats'.$factor_img.'', $regenerate_savingdata );		
	

		
							
				//		} 
					}
					
					
					
				}		
					
					
					
					
					
				}
			}
			if ( !empty( $thumbs_optimized_store ) ) {
				update_post_meta( $image_id, '_regenerateed_thumbs', $thumbs_optimized_store, false );
				
				
						
			}
			
			
		// Delete starts here	
	
	if($settings['old_img_delete_only']=='1')

	{

            
			$image = get_post($image_id);
 			$upload_dir = wp_upload_dir();
            
            // Get original image
            $image_fullpath = get_attached_file($image_id);
		//    $image_fullpath = get_attached_file($image->ID);
            $debug_1 = $image_fullpath;
            $debug_2 = '';
            $debug_3 = '';
            $debug_4 = '';
            
            
        // Results
        	$thumb_deleted = array();
        	$thumb_error = array();
        	$thumb_regenerate = array();
			
            
            // Hack to find thumbnail
            $file_info = pathinfo($image_fullpath);
            $file_info['filename'] .= '-';


            /**
         	 * Try delete all thumbnails
         	 */
            $files = array();
            $path = opendir($file_info['dirname']);

            if ( false !== $path ) {
                while (false !== ($thumb = readdir($path))) {
                    if (!(strrpos($thumb, $file_info['filename']) === false)) {
                        $files[] = $thumb;
                    }
                }
                closedir($path);
                sort($files);
            }
				
		// work in progress
		$what_to_del33 = explode("-", $file_info['filename'],-1);
		$what_to_del22=end($what_to_del33);
		$what_to_del1 = substr($what_to_del22,0,3);
		$only_obs_img=substr($file_info['filename'], 0,strrpos($file_info['filename'], $what_to_del22));
		$base_path_del=$file_info['dirname'] . DIRECTORY_SEPARATOR ;
	
// this is not final as this will not work on filenames with two three dash.
		$only_file_name88=$file_info['filename'];

		$what_to_del66=substr($only_file_name88,0,strrpos($only_file_name88,'-')).'-';
		
	// change above to work for all file names
	
		if($what_to_del1 =='e10' || $what_to_del1 =='e11' || $what_to_del1 =='e12' || $what_to_del1 =='e13' || $what_to_del1 =='e14' || $what_to_del1 =='e15' || $what_to_del1 =='e16' || $what_to_del1 =='e17' || $what_to_del1 =='e18' || $what_to_del1 =='e19' || $what_to_del1 =='e20' || $what_to_del1 =='e21' || $what_to_del1 =='e22' || $what_to_del1 =='e23' || $what_to_del1 =='e24'  )
		{
		foreach(glob($base_path_del.$only_obs_img."[0-9][0-9]*x*.{jpg,gif,png}", GLOB_BRACE) as $file_to_del_now)
		{ 
   		unlink($file_to_del_now);
		
		} 
		}
				
		// new delete code without regeneration starts here
		else
		{
	
		$output_accepted_img_regen= 'array('.$kainnan.')';

		// foreach($acceptable_file_regenr as $key_regen => $value_regen) {
        // $output_accepted_img_regen.='"'.$value_regen['file'].'",' ;
		// }

		
		foreach(glob($base_path_del.$what_to_del66."[0-9][0-9]*x*.{jpg,gif,png}", GLOB_BRACE) as $file_to_del_now22)
		{ 
		$only_file_name33 = explode("/", $file_to_del_now22);
		$only_file_name =end($only_file_name33);
						//		file_put_contents($pathuuu,$only_file_name);
//$popopp=array($only_file_name, "ppoeopoeoepe.jpg");

		//if(!in_array($only_file_name,$output_accepted_img_regen))
if (stripos($output_accepted_img_regen, $only_file_name) !== false)

		{
			
	//	file_put_contents($pathuuu,$output_accepted_img_regen);

			
   	//	unlink($file_to_del_now22);						
		} 
		else
		{
			   		unlink($file_to_del_now22);

// work in progress
							 $randopp= rand(1,1000);
							 $pathlll = $upload_dir['path'].'/100.txt';
									
							 $deletedfile=PHP_EOL . $only_file_name;
							 file_put_contents($pathlll, $deletedfile, FILE_APPEND);
							 if($randopp=='500')
							 {
								 unlink($pathlll);
							 }
							
							
	// work in progress
						
					
	
		}
		
		}
	
		}
		// new delete code without regeneration ends here

	
}

// delete unused thumbs ends here	
			
				
			
			return $image_data;
		}
		
		function optimize_thumbnails_cron( $image_data ) {
//$image_data['file']='';
$settings = $this->regenerate_settings;
				$image_id = $this->id;
			
// added on 28july so that previous top bottom, center orientation are not deleted			
			
$image_data1 = wp_get_attachment_metadata( $image_id );
			
	// 28july edit ends here		

		
	
			
			if ( empty( $image_id ) ) {
				global $wpdb;
				// disabled on 3 oct 2018 so tht database with different prefixes can be accepted
			//	$post = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s LIMIT 1", $image_data['file'] ) );
				$post = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s LIMIT 1", $image_data['file'] ) );

				$image_id = $post->post_id;
			}

			$regenerate_meta = get_post_meta( $image_id, '_regenerate_size', true );
			$image_backup_path = isset( $regenerate_meta['optimized_backup_file'] ) ? $regenerate_meta['optimized_backup_file'] : '';
			
			if ( $image_backup_path ) {
				$original_image_path = get_attached_file( $image_id );	
				if ( copy( $image_backup_path, $original_image_path ) ) {
					unlink( $image_backup_path );
					unset( $regenerate_meta['optimized_backup_file'] );
					update_post_meta( $image_id, '_regenerate_size', $regenerate_meta );
				}
			}

			if ( !$this->preg_array_key_exists( '/^include_size_/', $this->regenerate_settings ) ) {
				
				global $_wp_additional_image_sizes;
				$sizes = array();

				foreach ( get_intermediate_image_sizes() as $_size ) {
					if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
						$sizes[ $_size ]['width']  = get_site_option( "{$_size}_size_w" );
						$sizes[ $_size ]['height'] = get_site_option( "{$_size}_size_h" );
						$sizes[ $_size ]['crop']   = (bool) get_site_option( "{$_size}_crop" );
					} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
						$sizes[ $_size ] = array(
							'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
							'height' => $_wp_additional_image_sizes[ $_size ]['height'],
							'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
						);
					}
				}
				$sizes = array_keys( $sizes );
				foreach ($sizes as $size) {
					$this->regenerate_settings['include_size_' . $size] = 1;
				}
			}			

			// when resizing has taken place via API, update the post metadata accordingly
			if ( !empty( $regenerate_meta['regenerateed_width'] ) && !empty( $regenerate_meta['regenerateed_height'] ) ) {
				$image_data['width'] = $regenerate_meta['regenerateed_width'];
				$image_data['height'] = $regenerate_meta['regenerateed_height'];
			}
$path_parts="";

			$path_parts = @pathinfo($image_data['file']);
//$path_parts['dirname']='';
			// e.g. 04/02, for use in getting correct path or URL
			$upload_subdir = @$path_parts['dirname'];

		$upload_dir = wp_upload_dir() ;
	//		$upload_dir = '/wp-content/gallery';

			// all the way up to /uploads
			$upload_base_path = $upload_dir['basedir'];
			$upload_full_path = $upload_base_path . '/' . $upload_subdir;

			$sizes = array();

			if ( isset( $image_data['sizes'] ) ) {
				$sizes = $image_data['sizes'];
			}

			if ( !empty( $sizes ) ) {

				$sizes_to_enjoyed = $this->get_sizes_to_enjoyed();
				$thumb_path = '';
				$thumbs_optimized_store = array();
				$this_thumb = array();

				foreach ( $sizes as $key => $size ) {

					if ( !in_array("include_size_$key", $sizes_to_enjoyed) ) {
						continue;
					}

					$thumb_path = $upload_full_path . '/' . $size['file'];
							$kainnan .= '"'.$size['file'].'",' ;

				//	if ( file_exists( $thumb_path ) !== false ) {
						if($settings['old_img_delete_only']!='1'){

						$result = $this->optimize_image( $thumb_path, $this->optimization_type );
						if ( !empty( $result ) && isset( $result['success'] ) && isset( $result['compressed_url'] ) ) {
							$compressed_url = $result['compressed_url'];
if(@$settings['webp_yes']=='1')
{						
$web_url = $result['webp_url'];
$this->webp_image( $thumb_path, $web_url ) ;
}
							if ( (int) $result['saved_bytes'] !== 0 ) {
			
			
								if ( $this->replace_image( $thumb_path, $compressed_url ) ) {
									$this_thumb = array( 'thumb' => $key, 'file' => $size['file'], 'original_size' => $result['original_size'], 'compressed_size' => $result['compressed_size'], 'type' => $this->optimization_type, 'quota_remaining' => $result['quota_remaining'] );
									$thumbs_optimized_store [] = $this_thumb;
									
								
									
									
								}
							} else {
								$this_thumb = array( 'thumb' => $key, 'file' => $size['file'], 'original_size' => $result['original_size'], 'compressed_size' => $result['original_size'], 'type' => $this->optimization_type, 'quota_remaining' => $result['quota_remaining'] );
								$thumbs_optimized_store [] = $this_thumb;								
							}
							
							
		//		//			// my edit

			$factor_img=$image_id%4;
$savedetailss = get_site_option( 'regenerate_global_stats'.$factor_img.'' ) ;

//$regenerate_savingdata['size_before'.$factor_img.''] = $result['original_size'] + $savedetailss['size_before'.$factor_img.''];	
//$regenerate_savingdata['size_after'.$factor_img.''] = $result['compressed_size'] + $savedetailss['size_after'.$factor_img.''];		
//$regenerate_savingdata['total_images'] = $savedetailss['total_images']+1;


if($factor_img=='0')
{
		
	$regenerate_savingdata['size_before0'] = $result['original_size'] + $savedetailss['size_before0'];	
	$regenerate_savingdata['size_after0'] = $result['compressed_size'] + $savedetailss['size_after0'];		
	$regenerate_savingdata['total_images0'] = $savedetailss['total_images0']+1;	
}
elseif($factor_img=='1')
{
	

	$regenerate_savingdata['size_before1'] = $result['original_size'] + $savedetailss['size_before1'];	
	$regenerate_savingdata['size_after1'] = $result['compressed_size'] + $savedetailss['size_after1'];		
	$regenerate_savingdata['total_images1'] = $savedetailss['total_images1']+1;		
}
	elseif($factor_img=='2')
{
	

	$regenerate_savingdata['size_before2'] = $result['original_size'] + $savedetailss['size_before2'];	
	$regenerate_savingdata['size_after2'] = $result['compressed_size'] + $savedetailss['size_after2'];		
	$regenerate_savingdata['total_images2'] = $savedetailss['total_images2']+1;		
}			
	
	elseif($factor_img=='3')
{
		
	$regenerate_savingdata['size_before3'] = $result['original_size'] + $savedetailss['size_before3'];	
	$regenerate_savingdata['size_after3'] = $result['compressed_size'] + $savedetailss['size_after3'];		
	$regenerate_savingdata['total_images3'] = $savedetailss['total_images3']+1;		
}		
	else
	{
	$regenerate_savingdata['size_before'] = $result['original_size'] + $savedetailss['size_before'];	
	$regenerate_savingdata['size_after'] = $result['compressed_size'] + $savedetailss['size_after'];		
	$regenerate_savingdata['total_images'] = $savedetailss['total_images']+1;			
	}




	//$statusuu = $this->get_api_status( get_bloginfo('admin_email'), get_bloginfo('siteurl') );


//$regenerate_savingdata['quota_remaining'] = $statusuu['quota_remaining'];
//$regenerate_savingdata['pro_not'] = $statusuu['pro_not'];
//$regenerate_savingdata['pro_not'] = $savedetailss['pro_not'];

$regenerate_savingdata_thumb['quota_remaining']=$result['quota_remaining'];
//if(!empty($result['quota_remaining']))
//{
//$balance_quota=$result['quota_remaining'];	
//}
//else
//{
//	$balance_quota='-10';	
//
//}
//
//$regenerate_savingdata['quota_remaining']=$balance_quota;

update_site_option( 'regenerate_global_stats', $regenerate_savingdata_thumb );		

update_site_option( 'regenerate_global_stats'.$factor_img.'', $regenerate_savingdata );		
	
				
		
							
				//		} 
					}
					
					
					
				}		
					
					
					
					
					
				}
			}
			if ( !empty( $thumbs_optimized_store ) ) {
				update_post_meta( $image_id, '_regenerateed_thumbs', $thumbs_optimized_store, false );
				
				
						
			}
			
			
		// Delete starts here	
	
	if($settings['old_img_delete_only']=='1')

	{

            
			$image = get_post($image_id);
 			$upload_dir = wp_upload_dir();
            
            // Get original image
            $image_fullpath = get_attached_file($image_id);
		//    $image_fullpath = get_attached_file($image->ID);
            $debug_1 = $image_fullpath;
            $debug_2 = '';
            $debug_3 = '';
            $debug_4 = '';
            
            
        // Results
        	$thumb_deleted = array();
        	$thumb_error = array();
        	$thumb_regenerate = array();
			
            
            // Hack to find thumbnail
            $file_info = pathinfo($image_fullpath);
            $file_info['filename'] .= '-';


            /**
         	 * Try delete all thumbnails
         	 */
            $files = array();
            $path = opendir($file_info['dirname']);

            if ( false !== $path ) {
                while (false !== ($thumb = readdir($path))) {
                    if (!(strrpos($thumb, $file_info['filename']) === false)) {
                        $files[] = $thumb;
                    }
                }
                closedir($path);
                sort($files);
            }
				
		// work in progress
		$what_to_del33 = explode("-", $file_info['filename'],-1);
		$what_to_del22=end($what_to_del33);
		$what_to_del1 = substr($what_to_del22,0,3);
		$only_obs_img=substr($file_info['filename'], 0,strrpos($file_info['filename'], $what_to_del22));
		$base_path_del=$file_info['dirname'] . DIRECTORY_SEPARATOR ;
	
// this is not final as this will not work on filenames with two three dash.
		$only_file_name88=$file_info['filename'];

		$what_to_del66=substr($only_file_name88,0,strrpos($only_file_name88,'-')).'-';
		
	// change above to work for all file names
	
		if($what_to_del1 =='e10' || $what_to_del1 =='e11' || $what_to_del1 =='e12' || $what_to_del1 =='e13' || $what_to_del1 =='e14' || $what_to_del1 =='e15' || $what_to_del1 =='e16' || $what_to_del1 =='e17' || $what_to_del1 =='e18' || $what_to_del1 =='e19' || $what_to_del1 =='e20' || $what_to_del1 =='e21' || $what_to_del1 =='e22' || $what_to_del1 =='e23' || $what_to_del1 =='e24'  )
		{
		foreach(glob($base_path_del.$only_obs_img."[0-9][0-9]*x*.{jpg,gif,png}", GLOB_BRACE) as $file_to_del_now)
		{ 
   		unlink($file_to_del_now);						
		} 
		}
				
		// new delete code without regeneration starts here
		else
		{
		//$kainnan = $size['file'];
		
		
		$output_accepted_img_regen= 'array('.$kainnan.')';

		// foreach($acceptable_file_regenr as $key_regen => $value_regen) {
        // $output_accepted_img_regen.='"'.$value_regen['file'].'",' ;
		// }

		
		foreach(glob($base_path_del.$what_to_del66."[0-9][0-9]*x*.{jpg,gif,png}", GLOB_BRACE) as $file_to_del_now22)
		{ 
		$only_file_name33 = explode("/", $file_to_del_now22);
		$only_file_name =end($only_file_name33);
						//		file_put_contents($pathuuu,$only_file_name);
//$popopp=array($only_file_name, "ppoeopoeoepe.jpg");

		//if(!in_array($only_file_name,$output_accepted_img_regen))
if (stripos($output_accepted_img_regen, $only_file_name) !== false)

		{
			
	//	file_put_contents($pathuuu,$output_accepted_img_regen);

			
   	//	unlink($file_to_del_now22);						
		} 
		else
		{
			   		unlink($file_to_del_now22);		



// work in progress
							// $randopp= rand(1,1000);
							// $pathlll = $upload_dir['path'].'/100.txt';
									
							// $deletedfile=PHP_EOL . $only_file_name;
							// file_put_contents($pathlll, $deletedfile, FILE_APPEND);
							// if($randopp=='500')
							// {
								// unlink($pathlll);
							// }

// work in progress
					

		}
		
		}
	
		}
		// new delete code without regeneration ends here

	
}

// delete unused thumbs ends here	
			
				
			
			return $image_data;
		}
	function update_img_regenstyle() {

	
	$area_interest1 = $_POST['aof_interest'];	
//$area_interest = (int) $_POST['aof_interest'];
 $id_split= explode("-",$area_interest1);  
 $image_id= end($id_split);
  $area_interest= reset($id_split);

//$image_id = (int) $_POST['id_nm'];
	
			$type = false;
$setting_txt164 = __( 'Top', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt165 = __( 'Bottom', 'regenerate-thumbnails-and-delete-unused' );
$setting_txt166 = __( "Center", "regenerate-thumbnails-and-delete-unused" );	
	
	
			$data['error']='';
			$api_result['message']='';
			
$image_data = wp_get_attachment_metadata( $image_id );
//$image_data['img_regencentre'] = '999999';

$image_data['img_regencentre'] = $area_interest;

wp_update_attachment_metadata( $image_id, $image_data );

if($image_id=='1')
{
	$response_msg=$setting_txt164;
}
elseif($image_id=='2')
{
		$response_msg=$setting_txt165;

}
else
{
		$response_msg=$setting_txt166;
	
}
$data=$response_msg;

					echo json_encode( $data );
				
					
				
		//	}
			wp_die();
		}



	function optimize_image_dir( $image_path, $type, $resize = false ) {
//			function optimize_image( $image_path, $type, $resize = true ) {

	
			$settings = $this->regenerate_settings;
			$regenerate = new Regenerate_wpregenerate( $settings['api_key'], $settings['api_secret'] );

			if ( !empty( $type ) ) {
				$lossy = $type === 'lossy';
			} else {
				$lossy = $settings['api_lossy'] === 'lossy';
			}

$ippoge_id = $this->id;

//$image_linkuu = wp_get_attachment_url( $ippoge_id );
$rootpth = realpath( get_root_path_r_wpregenerate() );
 $exprelpath=explode($rootpth,$image_path);
$image_linkuu=get_bloginfo('siteurl').'/'.$exprelpath[1];
//$image_linkuu = wp_get_attachment_url( $ippoge_id );

			$params = array(
				'file' => $image_path,
				'urlll' => $image_linkuu,
				'wait' => true,
				'lossy' => $lossy,
				'origin' => '1'
			);

$settings['preserve_meta_date']='';
$settings['preserve_meta_copyright']='';
$settings['preserve_meta_geotag']='';
$settings['preserve_meta_orientation']='';
$settings['preserve_meta_profile']='';
//$result['compressed_size']='';
//$result['original_size']='';


			$preserve_meta_arr = array();
			if ( $settings['preserve_meta_date'] ) {
				$preserve_meta_arr[] = 'date';
			}
			if ( $settings['preserve_meta_copyright'] ) {
				$preserve_meta_arr[] = 'copyright';
			}
			if ( $settings['preserve_meta_geotag'] ) {
				$preserve_meta_arr[] = 'geotag';
			}
			if ( $settings['preserve_meta_orientation'] ) {
				$preserve_meta_arr[] = 'orientation';
			}
			if ( $settings['preserve_meta_profile'] ) {
				$preserve_meta_arr[] = 'profile';
			}
			if ( $settings['chroma'] ) {
				$params['sampling_scheme'] = $settings['chroma'];
			}

			if ( count( $preserve_meta_arr ) ) {
				$params['preserve_meta'] = $preserve_meta_arr;
			}

			if ( $settings['auto_orient'] ) {
				$params['auto_orient'] = true;
			}
if ( $settings['mp3_bit'] !='96' ) {
				$params['mp3_bit'] = $settings['mp3_bit'];
			}
			if ( $resize ) {
				$width = (int) $settings['resize_width'];
				$height = (int) $settings['resize_height'];
				if ( $width && $height ) {
					$params['resize'] = array('strategy' => 'auto', 'width' => $width, 'height' => $height );
				} elseif ( $width && !$height ) {
					$params['resize'] = array('strategy' => 'landscape', 'width' => $width );
				} elseif ( $height && !$width ) {
					$params['resize'] = array('strategy' => 'portrait', 'height' => $height );
				}
			}

			if ( isset( $settings['jpeg_quality'] ) && $settings['jpeg_quality'] > 0 ) {
				$params['quality'] = (int) $settings['jpeg_quality'];
			}
			if ( isset( $settings['total_thumb'] ) && $settings['total_thumb'] > 4 ) {
				$params['total_thumb'] = (int) $settings['total_thumb'];
			}
			
			if ( isset( $settings['optimize_main_image'] ) && $settings['optimize_main_image'] >= 0 ) {
				$params['optimize_main_image'] = (int) $settings['optimize_main_image'];
			}

// testing quota parameter starts here
$quotabalance = get_site_option( 'regenerate_global_stats' ) ;
$params['quota_remaining'] = $quotabalance['quota_remaining'];
$params['pro_not'] = $quotabalance['pro_not'];

// testing quota parameter ends here	
if ( isset( $settings['webp_yes'] ) && $settings['webp_yes'] >= 0 ) {$params['webp_yes'] = (int) $settings['webp_yes'];	}
if ( isset( $settings['google'] ) && $settings['google'] >= 0 ) {$params['google'] = (int) $settings['google'];	}
if ( $settings['pdf_quality'] !='100' ) {$params['pdf_quality'] = $settings['pdf_quality'];}
if ( $settings['video_quality'] !='75' ) {$params['video_quality'] = $settings['video_quality'];}
if ( $settings['resize_video'] !='0' ) {$params['resize_video'] = $settings['resize_video'];}
if ( isset( $settings['intelligentcrop'] ) && $settings['intelligentcrop'] >= 0 ) {$params['intelligentcrop'] = (int) $settings['intelligentcrop'];	}
if ( isset( $settings['old_img_delete'] ) && $settings['old_img_delete'] >= 0 ) {$params['old_img_delete'] = (int) $settings['old_img_delete'];	}
if ( $settings['regen_qty'] !='80' ) {$params['regen_qty'] = $settings['regen_qty'];}


if ( isset( $settings['artificial_intelligence'] ) && $settings['artificial_intelligence'] >= 0 ) {$params['artificial_intelligence'] = (int) $settings['artificial_intelligence'];	}
if ( isset( $settings['enable_optimiz_regen'] ) && $settings['enable_optimiz_regen'] >= 0 ) {$params['enable_optimiz_regen'] = (int) $settings['enable_optimiz_regen'];	}
if ( isset( $settings['machine_way2regen'] ) && $settings['machine_way2regen'] >= 0 ) {$params['machine_way2regen'] = (int) $settings['machine_way2regen'];	}

			
			set_time_limit(400);
			$data = $regenerate->upload( $params );
			$data['type'] = !empty( $type ) ? $type : $settings['api_lossy'];
	
			return $data;
		}	
		
		
		
		
		
		function optimize_image_nextgen( $image_path, $type, $resize = true ) {

//			function optimize_image_nextgen( $image_path, $type, $resize = false ) {
global $finalurluu;
			$settings = $this->regenerate_settings;
			$regenerate = new Regenerate_wpregenerate( $settings['api_key'], $settings['api_secret'] );

			if ( !empty( $type ) ) {
				$lossy = $type === 'lossy';
			} else {
				$lossy = $settings['api_lossy'] === 'lossy';
			}

$ippoge_id = $this->id;
$image_linkuu =$finalurluu;

//$image_linkuu = wp_get_attachment_url( $ippoge_id );

			$params = array(
				'file' => $image_path,
				'urlll' => $image_linkuu,
				'wait' => true,
				'lossy' => $lossy,
				'origin' => 'wp'
			);

			$preserve_meta_arr = array();
			if ( $settings['preserve_meta_date'] ) {
				$preserve_meta_arr[] = 'date';
			}
			if ( $settings['preserve_meta_copyright'] ) {
				$preserve_meta_arr[] = 'copyright';
			}
			if ( $settings['preserve_meta_geotag'] ) {
				$preserve_meta_arr[] = 'geotag';
			}
			if ( $settings['preserve_meta_orientation'] ) {
				$preserve_meta_arr[] = 'orientation';
			}
			if ( $settings['preserve_meta_profile'] ) {
				$preserve_meta_arr[] = 'profile';
			}
			if ( $settings['chroma'] ) {
				$params['sampling_scheme'] = $settings['chroma'];
			}

			if ( count( $preserve_meta_arr ) ) {
				$params['preserve_meta'] = $preserve_meta_arr;
			}

			if ( $settings['auto_orient'] ) {
				$params['auto_orient'] = true;
			}
if ( $settings['mp3_bit'] !='96' ) {
				$params['mp3_bit'] = $settings['mp3_bit'];
			}
			if ( $resize ) {
				$width = (int) $settings['resize_width'];
				$height = (int) $settings['resize_height'];
				if ( $width && $height ) {
					$params['resize'] = array('strategy' => 'auto', 'width' => $width, 'height' => $height );
				} elseif ( $width && !$height ) {
					$params['resize'] = array('strategy' => 'landscape', 'width' => $width );
				} elseif ( $height && !$width ) {
					$params['resize'] = array('strategy' => 'portrait', 'height' => $height );
				}
			}

			if ( isset( $settings['jpeg_quality'] ) && $settings['jpeg_quality'] > 0 ) {
				$params['quality'] = (int) $settings['jpeg_quality'];
			}
			
			if ( isset( $settings['total_thumb'] ) && $settings['total_thumb'] > 4 ) {
				$params['total_thumb'] = (int) $settings['total_thumb'];
			}
			
			if ( isset( $settings['optimize_main_image'] ) && $settings['optimize_main_image'] >= 0 ) {
				$params['optimize_main_image'] = (int) $settings['optimize_main_image'];
			}

// testing quota parameter starts here
$quotabalance = get_site_option( 'regenerate_global_stats' ) ;
$params['quota_remaining'] = $quotabalance['quota_remaining'];
$params['pro_not'] = $quotabalance['pro_not'];

// testing quota parameter ends here	
if ( isset( $settings['webp_yes'] ) && $settings['webp_yes'] >= 0 ) {$params['webp_yes'] = (int) $settings['webp_yes'];	}
if ( isset( $settings['google'] ) && $settings['google'] >= 0 ) {$params['google'] = (int) $settings['google'];	}
if ( $settings['pdf_quality'] !='100' ) {$params['pdf_quality'] = $settings['pdf_quality'];}
if ( $settings['video_quality'] !='75' ) {$params['video_quality'] = $settings['video_quality'];}
if ( $settings['resize_video'] !='0' ) {$params['resize_video'] = $settings['resize_video'];}
if ( isset( $settings['intelligentcrop'] ) && $settings['intelligentcrop'] >= 0 ) {$params['intelligentcrop'] = (int) $settings['intelligentcrop'];	}
if ( isset( $settings['old_img_delete'] ) && $settings['old_img_delete'] >= 0 ) {$params['old_img_delete'] = (int) $settings['old_img_delete'];	}
if ( $settings['regen_qty'] !='80' ) {$params['regen_qty'] = $settings['regen_qty'];}

if ( isset( $settings['artificial_intelligence'] ) && $settings['artificial_intelligence'] >= 0 ) {$params['artificial_intelligence'] = (int) $settings['artificial_intelligence'];	}
if ( isset( $settings['enable_optimiz_regen'] ) && $settings['enable_optimiz_regen'] >= 0 ) {$params['enable_optimiz_regen'] = (int) $settings['enable_optimiz_regen'];	}
if ( isset( $settings['machine_way2regen'] ) && $settings['machine_way2regen'] >= 0 ) {$params['machine_way2regen'] = (int) $settings['machine_way2regen'];	}

			
			set_time_limit(400);
			$data = $regenerate->upload( $params );
			$data['type'] = !empty( $type ) ? $type : $settings['api_lossy'];
			return $data;
		}
	
function optimize_thumbnails_nextgen( $img_thumbpath, $type, $resize = true ) {

//			function optimize_image_nextgen( $image_path, $type, $resize = false ) {
global	$finalurlthumb;
	
			$settings = $this->regenerate_settings;
			$regenerate = new Regenerate_wpregenerate( $settings['api_key'], $settings['api_secret'] );

			if ( !empty( $type ) ) {
				$lossy = $type === 'lossy';
			} else {
				$lossy = $settings['api_lossy'] === 'lossy';
			}

$ippoge_id = $this->id;
$image_linkthumb =$finalurlthumb;


//$image_linkuu = wp_get_attachment_url( $ippoge_id );

			$params = array(
				'file' => $img_thumbpath,
				'urlll' => $image_linkthumb,
				'wait' => true,
				'lossy' => $lossy,
				'origin' => 'wp'
			);

			$preserve_meta_arr = array();
			if ( $settings['preserve_meta_date'] ) {
				$preserve_meta_arr[] = 'date';
			}
			if ( $settings['preserve_meta_copyright'] ) {
				$preserve_meta_arr[] = 'copyright';
			}
			if ( $settings['preserve_meta_geotag'] ) {
				$preserve_meta_arr[] = 'geotag';
			}
			if ( $settings['preserve_meta_orientation'] ) {
				$preserve_meta_arr[] = 'orientation';
			}
			if ( $settings['preserve_meta_profile'] ) {
				$preserve_meta_arr[] = 'profile';
			}
			if ( $settings['chroma'] ) {
				$params['sampling_scheme'] = $settings['chroma'];
			}

			if ( count( $preserve_meta_arr ) ) {
				$params['preserve_meta'] = $preserve_meta_arr;
			}

			if ( $settings['auto_orient'] ) {
				$params['auto_orient'] = true;
			}
if ( $settings['mp3_bit'] !='96' ) {
				$params['mp3_bit'] = $settings['mp3_bit'];
			}

			if ( $resize ) {
				$width = (int) $settings['resize_width'];
				$height = (int) $settings['resize_height'];
				if ( $width && $height ) {
					$params['resize'] = array('strategy' => 'auto', 'width' => $width, 'height' => $height );
				} elseif ( $width && !$height ) {
					$params['resize'] = array('strategy' => 'landscape', 'width' => $width );
				} elseif ( $height && !$width ) {
					$params['resize'] = array('strategy' => 'portrait', 'height' => $height );
				}
			}

			if ( isset( $settings['jpeg_quality'] ) && $settings['jpeg_quality'] > 0 ) {
				$params['quality'] = (int) $settings['jpeg_quality'];
			}
			
			if ( isset( $settings['total_thumb'] ) && $settings['total_thumb'] > 4 ) {
				$params['total_thumb'] = (int) $settings['total_thumb'];
			}
			
			if ( isset( $settings['optimize_main_image'] ) && $settings['optimize_main_image'] >= 0 ) {
				$params['optimize_main_image'] = (int) $settings['optimize_main_image'];
			}

// testing quota parameter starts here
$quotabalance = get_site_option( 'regenerate_global_stats' ) ;
$params['quota_remaining'] = $quotabalance['quota_remaining'];
$params['pro_not'] = $quotabalance['pro_not'];

// testing quota parameter ends here	
			
if ( isset( $settings['webp_yes'] ) && $settings['webp_yes'] >= 0 ) {$params['webp_yes'] = (int) $settings['webp_yes'];	}
if ( isset( $settings['google'] ) && $settings['google'] >= 0 ) {$params['google'] = (int) $settings['google'];	}
if ( $settings['pdf_quality'] !='100' ) {$params['pdf_quality'] = $settings['pdf_quality'];}
if ( $settings['video_quality'] !='75' ) {$params['video_quality'] = $settings['video_quality'];}
if ( $settings['resize_video'] !='0' ) {$params['resize_video'] = $settings['resize_video'];}
if ( isset( $settings['intelligentcrop'] ) && $settings['intelligentcrop'] >= 0 ) {$params['intelligentcrop'] = (int) $settings['intelligentcrop'];	}
if ( isset( $settings['old_img_delete'] ) && $settings['old_img_delete'] >= 0 ) {$params['old_img_delete'] = (int) $settings['old_img_delete'];	}
if ( $settings['regen_qty'] !='80' ) {$params['regen_qty'] = $settings['regen_qty'];}

if ( isset( $settings['artificial_intelligence'] ) && $settings['artificial_intelligence'] >= 0 ) {$params['artificial_intelligence'] = (int) $settings['artificial_intelligence'];	}
if ( isset( $settings['enable_optimiz_regen'] ) && $settings['enable_optimiz_regen'] >= 0 ) {$params['enable_optimiz_regen'] = (int) $settings['enable_optimiz_regen'];	}
if ( isset( $settings['machine_way2regen'] ) && $settings['machine_way2regen'] >= 0 ) {$params['machine_way2regen'] = (int) $settings['machine_way2regen'];	}




			set_time_limit(400);
			$data = $regenerate->upload( $params );
			$data['type'] = !empty( $type ) ? $type : $settings['api_lossy'];
			return $data;
		}

		function get_image_sizes() {
			global $_wp_additional_image_sizes;

			$sizes = array();

			foreach ( get_intermediate_image_sizes() as $_size ) {
				if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
					$sizes[ $_size ]['width']  = get_site_option( "{$_size}_size_w" );
					$sizes[ $_size ]['height'] = get_site_option( "{$_size}_size_h" );
					$sizes[ $_size ]['crop']   = (bool) get_site_option( "{$_size}_crop" );
				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
					$sizes[ $_size ] = array(
						'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
						'height' => $_wp_additional_image_sizes[ $_size ]['height'],
						'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
					);
				}
			}

			return $sizes;
		}


		static function formatBytes( $size, $precision = 2 ) {
			if (empty($size)) $size = 0.0;// added this line on 20.12.2017
		    $base = log( $size, 1024 );
			//			if (empty($base)) $base = 0.0;// added this line on 20.12.2017
		    $suffixes = array( ' bytes', 'KB', 'MB', 'GB', 'TB' );   
		    return round( pow( 1024, $base - floor( $base ) ), $precision ) . $suffixes[floor( $base )];
		}
	
	
		function regenerate_media_library_ajax_callback_new_cron() {
			global $wpdb;
		//	$image_id = (int) $_POST['id'];
		$setting_txt85 = __( 'There is a problem with your credentials. Please check them in the way2enjoy.com settings section of Media Settings, and try again.', 'regenerate-thumbnails-and-delete-unused' );	
		$setting_txt86 = __( 'Could not overwrite original file. Please ensure that your files are writable by plugins.' );	
		$setting_txt99 = __( 'Buy', 'regenerate-thumbnails-and-delete-unused' );
		$setting_txt151 = __( "Quota exceeded.Please", "regenerate-thumbnails-and-delete-unused" );	
	
		$setting_txt172 = __( "Deleted Unused Thumbnails", "regenerate-thumbnails-and-delete-unused" );	

		$settings = $this->regenerate_settings;
		$sche_opt_yorn=@$settings['scheduled_opt_way2regen'];
		$type = false;
		$data['error']='';
		$api_result['message']='';
			 if ( isset( $_POST['type'] ) ) {
				 $type = sanitize_text_field($_POST['type']);
				 $this->optimization_type = $type;
			 }
		$last_cron_id3312= get_site_option( 'regenerate_cron_id' );

		$what_to_del3312 = explode("-", $last_cron_id3312);
		$last_cron_id33=reset($what_to_del3312);
		
		$last_cron_id998=$last_cron_id33  > 0 ? $last_cron_id33 : "5";

		
				
		//$compressed_last_id99 = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE `meta_key` ='_wpregenerate_size' order by post_id desc limit 1", 'foo', 1337 ) );
 $compressed_last_id99 = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE `meta_key` ='_regenerateed_thumbs' order by post_id desc limit 1", 'foo', 1337 ) );

 // $compressed_last_id=$compressed_last_id99  > 0 ? $compressed_last_id99 : "5";

   $compressed_last_id77 = (($compressed_last_id99 != FALSE) ? $compressed_last_id99->post_id : '5');

//  $last_cron_id=$last_cron_id99  > $compressed_last_id ? $last_cron_id99 : $compressed_last_id;

 
 $compressed_last_id = max($last_cron_id998,$compressed_last_id77);
 

$non_compressed_first_id = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE `meta_key` ='_wp_attached_file' and post_id > '$compressed_last_id' order by post_id asc limit 1", 'foo', 1337 ) );


// dont remove., 'foo', 1337 as it helps to solve some wordpress error

// $non_comp_first_id = (($non_compressed_first_id != FALSE) ? $non_compressed_first_id->post_id : 5);
$non_comp_first_id = (($non_compressed_first_id != FALSE) ? $non_compressed_first_id->post_id : '');

	

 $milliseconds_regen = round(microtime(true) * 1000);

		
		if(!empty($non_comp_first_id))
		{	
$regn_cronid=$non_comp_first_id.'-'.$milliseconds_regen;	
		}
		else
		{
		$compressed_last_id2 = min($last_cron_id998,$compressed_last_id77);
 
$regn_cronid=$non_comp_first_id2.'-'.$milliseconds_regen;	

$non_compressed_first_id2 = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE `meta_key` ='_wp_attached_file' and post_id > '$compressed_last_id2' order by post_id asc limit 1", 'foo', 1337 ) );


// dont remove., 'foo', 1337 as it helps to solve some wordpress error

// $non_comp_first_id = (($non_compressed_first_id != FALSE) ? $non_compressed_first_id->post_id : 5);
 $non_comp_first_id2 = (($non_compressed_first_id2 != FALSE) ? $non_compressed_first_id2->post_id : '');
			}
		
				update_site_option( 'regenerate_cron_id', $regn_cronid );

		$image_id = max($non_comp_first_id,$non_comp_first_id2);

		if ( empty( $image_id ) || $sche_opt_yorn!='1' ) {
	//	if ( empty( $image_id )) {

					exit;
				}
	
		
		 $this->id = $image_id;

			

	if($settings['old_img_delete']=='1')

	{
		            
$image = get_post($image_id);
 			$upload_dir = wp_upload_dir();
            
            // Get original image
            $image_fullpath = get_attached_file($image_id);
		//    $image_fullpath = get_attached_file($image->ID);
            $debug_1 = $image_fullpath;
            $debug_2 = '';
            $debug_3 = '';
            $debug_4 = '';
            
          

            // Results
        	$thumb_deleted = array();
        	$thumb_error = array();
        	$thumb_regenerate = array();

            
            // Hack to find thumbnail
            $file_info = pathinfo($image_fullpath);
            $file_info['filename'] .= '-';


            /**
         	 * Try delete all thumbnails
         	 */
            $files = array();
            $path = opendir($file_info['dirname']);

            if ( false !== $path ) {
                while (false !== ($thumb = readdir($path))) {
                    if (!(strrpos($thumb, $file_info['filename']) === false)) {
                        $files[] = $thumb;
                    }
                }
                closedir($path);
                sort($files);
            }
				
		// work in progress
		$what_to_del33 = explode("-", $file_info['filename'],-1);
		$what_to_del22=end($what_to_del33);
		$what_to_del1 = substr($what_to_del22,0,3);
		if($what_to_del1 =='e10' || $what_to_del1 =='e11' || $what_to_del1 =='e12' || $what_to_del1 =='e13' || $what_to_del1 =='e14' || $what_to_del1 =='e15' || $what_to_del1 =='e16' || $what_to_del1 =='e17' || $what_to_del1 =='e18' || $what_to_del1 =='e19' || $what_to_del1 =='e20' || $what_to_del1 =='e21' || $what_to_del1 =='e22' || $what_to_del1 =='e23' || $what_to_del1 =='e24'  )
		{
		$only_obs_img=substr($file_info['filename'], 0,strrpos($file_info['filename'], $what_to_del22));
		$base_path_del=$file_info['dirname'] . DIRECTORY_SEPARATOR ;
		foreach(glob($base_path_del.$only_obs_img."[0-9][0-9]*x*.{jpg,gif,png}", GLOB_BRACE) as $file_to_del_now)
		{ 
   		unlink($file_to_del_now);						
		} 
		}
		// work in progress ends here on 12.09.2018
	
			
            foreach ($files as $thumb) {
                $thumb_fullpath = $file_info['dirname'] . DIRECTORY_SEPARATOR . $thumb;
			
			
                $thumb_info = pathinfo($thumb_fullpath);
            	$valid_thumb = explode($file_info['filename'], $thumb_info['filename']);
        	    if ($valid_thumb[0] == "") {
        	       	$dimension_thumb = explode('x', $valid_thumb[1]);
        	       	if (count($dimension_thumb) == 2) {
        	       		if (is_numeric($dimension_thumb[0]) && is_numeric($dimension_thumb[1])) {
        	       			unlink($thumb_fullpath);
						
        	       			if (!file_exists($thumb_fullpath)) {
        	       				$thumb_deleted[] = sprintf("%sx%s", $dimension_thumb[0], $dimension_thumb[1]);
        					} else {
        						$thumb_error[] = sprintf("%sx%s", $dimension_thumb[0], $dimension_thumb[1]);
        					}
        	       		}
        	       	}
        	    }
            }
           

}

// delete unused thumbs ends here	
					
			
$savedetailuu = get_site_option( 'regenerate_global_stats' ) ;

				$image_path = get_attached_file( $image_id );
		
		
		
		
		
				$optimize_main_image = !empty( $settings['optimize_main_image'] );
				$api_key = isset( $settings['api_key'] ) ? $settings['api_key'] : '';
				$api_secret = isset( $settings['api_secret'] ) ? $settings['api_secret'] : '';

				$data = array();

				if ( empty( $api_key ) && empty( $api_secret ) ) {
					$data['error'] = $setting_txt85;
					update_post_meta( $image_id, '_regenerate_size', $data );
					echo json_encode( array( 'error' => $data['error'] ) );
				
					exit;
				}

				if ( $optimize_main_image ) {

						
					// check if thumbs already optimized
					$thumbs_optimized = false;
					$regenerateed_thumbs_data = get_post_meta( $image_id, '_regenerateed_thumbs', true );
					
					if ( !empty ( $regenerateed_thumbs_data ) ) {
						$thumbs_optimized = true;
					}

					// get metadata for thumbnails
					$image_data = wp_get_attachment_metadata( $image_id );

					if ( !$thumbs_optimized ) {
						$this->optimize_thumbnails_cron( $image_data );
					} else {

						// re-optimize thumbs if mode has changed
						$regenerateed_thumbs_mode = $regenerateed_thumbs_data[0]['type'];						
						if ( strcmp( $regenerateed_thumbs_mode, $this->optimization_type ) !== 0 ) {
							wp_generate_attachment_metadata( $image_id, $image_path );
							$this->optimize_thumbnails_cron( $image_data );
						}
					}

					$resize = false;
					if ( !empty( $settings['resize_width'] ) || !empty( $settings['resize_height'] ) ) {
						$resize = true;
					}

					$api_result = $this->optimize_image( $image_path, $type, $resize );

					if ( !empty( $api_result ) && !empty( $api_result['success'] ) ) {
						$data = $this->get_result_arr( $api_result, $image_id );
					if($settings['webp_yes']=='1')
{	
$web_url = $api_result['webp_url'];
$this->webp_image( $image_path, $web_url ) ;
}
						if ( $this->replace_image( $image_path, $api_result['compressed_url'] ) ) {

							if ( !empty( $data['regenerateed_width'] ) && !empty( $data['regenerateed_height'] ) ) {
								$image_data = wp_get_attachment_metadata( $image_id );
								$image_data['width'] = $data['regenerateed_width'];
								$image_data['height'] = $data['regenerateed_height'];

								wp_update_attachment_metadata( $image_id, $image_data );
															
							}

							// store regenerateed info to DB
							update_post_meta( $image_id, '_regenerate_size', $data );

							
							
							
						

$factor_img=$image_id%4;
$savedetailss = get_site_option( 'regenerate_global_stats'.$factor_img.'' ) ;

if($factor_img=='0')
{
		
	$regenerate_savingdata_new['size_before0'] = $data['original_size'] + $savedetailss['size_before0'];	
	$regenerate_savingdata_new['size_after0'] = $data['compressed_size'] + $savedetailss['size_after0'];		
	$regenerate_savingdata_new['total_images0'] = $savedetailss['total_images0']+1;	
}
elseif($factor_img=='1')
{
	

	$regenerate_savingdata_new['size_before1'] = $data['original_size'] + $savedetailss['size_before1'];	
	$regenerate_savingdata_new['size_after1'] = $data['compressed_size'] + $savedetailss['size_after1'];		
	$regenerate_savingdata_new['total_images1'] = $savedetailss['total_images1']+1;		
}
	elseif($factor_img=='2')
{
	

	$regenerate_savingdata_new['size_before2'] = $data['original_size'] + $savedetailss['size_before2'];	
	$regenerate_savingdata_new['size_after2'] = $data['compressed_size'] + $savedetailss['size_after2'];		
	$regenerate_savingdata_new['total_images2'] = $savedetailss['total_images2']+1;		
}			
	
	elseif($factor_img=='3')
{
		
	$regenerate_savingdata_new['size_before3'] = $data['original_size'] + $savedetailss['size_before3'];	
	$regenerate_savingdata_new['size_after3'] = $data['compressed_size'] + $savedetailss['size_after3'];		
	$regenerate_savingdata_new['total_images3'] = $savedetailss['total_images3']+1;		
}		
	else
	{
	$regenerate_savingdata['size_before'] = $data['original_size'] + $savedetailss['size_before'];	
	$regenerate_savingdata['size_after'] = $data['compressed_size'] + $savedetailss['size_after'];		
	$regenerate_savingdata['total_images'] = $savedetailss['total_images']+1;			
	}
	
	
$regenerate_savingdata['quota_remaining']=$api_result['quota_remaining'];
//disabled on 10june as buy button was not displaying after quota exceeded//$regenerate_savingdata['quota_remaining']=$data['quota_remaining'];

//$regenerate_savingdata['pro_not']=$statusuu['plan_name'];
$regenerate_savingdata['pro_not'] = $savedetailuu['pro_not'];
//}



						    update_site_option( 'regenerate_global_stats', $regenerate_savingdata );		

						    update_site_option( 'regenerate_global_stats'.$factor_img.'', $regenerate_savingdata_new );		

	// testing ends here



							// enjoyed thumbnails, store that data too. This can be unset when there are no thumbs
							$regenerateed_thumbs_data = get_post_meta( $image_id, '_regenerateed_thumbs', true );
							if ( !empty( $regenerateed_thumbs_data ) ) {
								$data['thumbs_data'] = $regenerateed_thumbs_data;
								$data['success'] = true;
							}

							$data['html'] = $this->generate_stats_summary( $image_id );
							echo json_encode( $data );
						
						} else {
							echo json_encode( array( 'error' => ''.$setting_txt86.'' ) );
							exit;
						}	

					} 
					else {
						// error or no optimization
						if ( file_exists( $image_path ) ) {
							update_post_meta( $image_id, '_regenerate_size', $data );
						} else {
							// file not found
						}
//						if($savedetailss['quota_remaining']>='0')
						if($savedetailuu['quota_remaining']>'0')
						{
						echo json_encode( array( 'error' => $api_result['message'], '' ) );
						
						}
						else
						{
								$data['html'] ='{"success":true,"html":"'.$setting_txt151.' <a href=\'https://way2enjoy.com/regenerate-thumbnails?pluginemail='.get_bloginfo('admin_email').'\' target=\'_blank\'>'.$setting_txt99.'</a>"}';
								
if(is_numeric( $data['original_size']))
{
	echo json_encode( array( 'error' => $api_result['message'], '' ) );
	}
else
{
echo	$data['html'];
	}
						}
				
					}
				} 
				
				else {
					
					
						
					
			//		if($settings['old_img_delete_only']!='1')
		//	{	
					// get metadata for thumbnails
					$image_data = wp_get_attachment_metadata( $image_id );
					$this->optimize_thumbnails_cron( $image_data );

				
					
					// enjoyed thumbnails, store that data too. This can be unset when there are no thumbs
					$regenerateed_thumbs_data = get_post_meta( $image_id, '_regenerateed_thumbs', true );

					if ( !empty( $regenerateed_thumbs_data ) ) {
						$data['thumbs_data'] = $regenerateed_thumbs_data;
						$data['success'] = true;
					}
					$data['html'] = $this->generate_stats_summary( $image_id );
					if($settings['old_img_delete_only']!='1')
					{
				echo json_encode( $data );
					}
					
				else
				{
			echo '{"success": true,"html": "<div class=\"regenerate-result-wrap\">'.$setting_txt172.'</div>"}';
				update_post_meta( $image_id, '_regenerateed_thumbs', 'done', false );
			}

			}	
		//	}
			wp_die();
		}


		
	
	}
	
	
	
	
//schedule regeneration starts here

	
function regenerate_custom_cron_schedule( $schedules ) {
//$options_regenerate=get_site_option( '_regenerate_options' ) ;
//$cron_regenerate=$options_regenerate['scheduled_opt_way2regen'];
//$cron_regenerate_time11=$options_regenerate['scheduled_opt_way2regen_sec'];
 // $cron_regenerate_time=$cron_regenerate_time11  > 0 ? $cron_regenerate_time11 : "999999";


   $schedules['15_sec'] = array(
		      'interval'  => 15,
              'display'   => __( 'Regenerate image schedule', 'regenerate-thumbnails-and-delete-unused' )
      );
    $schedules['30_sec'] = array(
		      'interval'  => 30,
              'display'   => __( 'Regenerate image schedule', 'regenerate-thumbnails-and-delete-unused' )
    );
  
   $schedules['1_min'] = array(
		      'interval'  => 60,
              'display'   => __( 'Regenerate image schedule', 'regenerate-thumbnails-and-delete-unused' )
      );
    $schedules['2_min'] = array(
		      'interval'  => 120,
              'display'   => __( 'Regenerate image schedule', 'regenerate-thumbnails-and-delete-unused' )
    );
    $schedules['5_min'] = array(
		      'interval'  => 300,
              'display'   => __( 'Regenerate image schedule', 'regenerate-thumbnails-and-delete-unused' )
      );
    $schedules['10_min'] = array(
		      'interval'  => 600,
              'display'   => __( 'Regenerate image schedule', 'regenerate-thumbnails-and-delete-unused' )
      );
   $schedules['15_min'] = array(
		      'interval'  => 900,
              'display'   => __( 'Regenerate image schedule', 'regenerate-thumbnails-and-delete-unused' )
      );
   $schedules['30_min'] = array(
		      'interval'  => 1800,
              'display'   => __( 'Regenerate image schedule', 'regenerate-thumbnails-and-delete-unused' )
      );
   $schedules['1_hour'] = array(
		      'interval'  => 3600,
              'display'   => __( 'Regenerate image schedule', 'regenerate-thumbnails-and-delete-unused' )
      );
  $schedules['1_day'] = array(
	 'interval'  => 86400,
     'display'   => __( 'Regenerate image schedule', 'regenerate-thumbnails-and-delete-unused' )
      );
	
	$schedules['1_month'] = array(
	 'interval'  => 86400*30,
     'display'   => __( 'Regenerate image schedule', 'regenerate-thumbnails-and-delete-unused' )
      );
	
	
		
	return $schedules;
	
}
add_filter( 'cron_schedules', 'regenerate_custom_cron_schedule' );



$options_regenerate99=get_site_option( '_regenerate_options' ) ;
$cron_regenerate99=@$options_regenerate99['scheduled_opt_way2regen'];
$cron_regenerate_time99=@$options_regenerate99['scheduled_opt_way2regen_sec'];
$cron_regenerate_time22=$cron_regenerate_time99  > 0 ? $cron_regenerate_time99 : "999999";

if($cron_regenerate_time22 <='15')
{
$sch_msgg='15_sec';	
}
elseif($cron_regenerate_time22 >'15' && $cron_regenerate_time22 <='30')
{
$sch_msgg='30_sec';	
}

elseif($cron_regenerate_time22 >'30' && $cron_regenerate_time22 <='60')
{
$sch_msgg='1_min';	
}
elseif($cron_regenerate_time22 >'60' && $cron_regenerate_time22 <='120')
{
	$sch_msgg='2_min';	
}
elseif($cron_regenerate_time22 >'120' && $cron_regenerate_time22 <='300')
{
	$sch_msgg='5_min';	
}
elseif($cron_regenerate_time22 >'300' && $cron_regenerate_time22 <='600')
{
	$sch_msgg='10_min';	
}
elseif($cron_regenerate_time22 >'600' && $cron_regenerate_time22 <='900')
{
	$sch_msgg='15_min';	
}
elseif($cron_regenerate_time22 >'900' && $cron_regenerate_time22 <='1800')
{
	$sch_msgg='30_min';	
}
elseif($cron_regenerate_time22 >'1800' && $cron_regenerate_time22 <='3600')
{
	$sch_msgg='1_hour';	
}
elseif($cron_regenerate_time22 >'3600' && $cron_regenerate_time22 <='86400')
{
	$sch_msgg='1_day';	
}
else
{
	$sch_msgg='1_month';	
}


if ( wp_get_schedule( 'bl_cron_hook2regen' ) !== $sch_msgg) 
{
$timestamp_con = wp_next_scheduled('bl_cron_hook2regen');
wp_unschedule_event( $timestamp_con, 'bl_cron_hook2regen' );
wp_schedule_event( time(), $sch_msgg, 'bl_cron_hook2regen' );
}
	
register_deactivation_hook( __FILE__, 'bl_deactivateregen' );
 
function bl_deactivateregen() {
   $timestamp = wp_next_scheduled( 'bl_cron_hook2regen' )+9999999;
   wp_unschedule_event( $timestamp, 'bl_cron_hook2regen' );
}



//schedule regeneration ends here	
	
	
}




	add_action( 'wp_ajax_way_enable_gzip', 'way_enable_gzip_r_wpregenerate');

function way_enable_gzip_r_wpregenerate() {
			update_site_option( 'way2-gzip-enabled', 1 );

		if(strtolower($_SERVER['SERVER_SOFTWARE']) == 'apache') {
//			if(strtolower($_SERVER['SERVER_SOFTWARE']) != 'apache') {
	
	     if(!get_site_option('way2-htaccess-enabled') ) {

			update_site_option( 'way2-htaccess-enabled', 1 );

			add_filter('mod_rewrite_rules', 'regenerate_addHtaccessContent');
			save_mod_rewrite_rules();
		 }
		 else
		 
		 {
						regenerate_other_gzip();
 
			 
		 }

echo '

    HTML,JS,CSS,SVG,XML etc Compression enabled
    ▬▬▬▬▬▬▬▬▬ஜ۩۞۩ஜ▬▬▬▬▬▬▬▬▬
	 • Contact us if you faced any issue 
    • bydbest@gmail.com
    • Check saving in setting page 
    • If Plugin is uninstalled, this compression will be disabled

 '; 
		} 
		else {
			update_site_option( 'way2-htaccess-enabled', 0 );
remove_filter('mod_rewrite_rules', 'regenerate_addHtaccessContent');
			save_mod_rewrite_rules();
			regenerate_other_gzip();	
echo '

    HTML,JS,CSS,SVG,XML etc Compression enabled
    ▬▬▬▬▬▬▬▬▬ஜ۩۞۩ஜ▬▬▬▬▬▬▬▬▬
	 • Contact us if you faced any issue 
    • bydbest@gmail.com
    • Check saving in setting page 
    • If Plugin is uninstalled, this compression will be disabled 

     '; 
		 
		 
		 			
	//		remove_filter('mod_rewrite_rules', 'regenerate_addHtaccessContent');
	//		save_mod_rewrite_rules();
		}

	die();			

	}

function regenerate_addHtaccessContent($rules) {
	$my_contentgzip = '
<IfModule mod_deflate.c>
	<IfModule mod_filter.c>
			<IfModule mod_version.c>
				# Declare a "gzip" filter, it should run after all internal filters like PHP or SSI
				FilterDeclare  gzip CONTENT_SET

				# Enable "gzip" filter if "Content-Type" contains "text/html", "text/css" etc.
				<IfVersion < 2.4.4>
					<IfModule filter_module>
						FilterDeclare   COMPRESS
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $text/html
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $text/css
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $text/plain
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $text/xml
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $text/x-component
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $application/javascript
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $application/json
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $application/xml
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $application/xhtml+xml
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $application/rss+xml
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $application/atom+xml
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $application/vnd.ms-fontobject
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $image/svg+xml
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $application/x-font-ttf
						FilterProvider  COMPRESS  DEFLATE resp=Content-Type $font/opentype
						FilterChain     COMPRESS
						FilterProtocol  COMPRESS  DEFLATE change=yes;byteranges=no
					</IfModule>
				</IfVersion>

				<IfVersion >= 2.4.4>
					<IfModule filter_module>
						FilterDeclare   COMPRESS
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'text/html\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'text/css\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'text/plain\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'text/xml\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'text/x-component\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'application/javascript\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'application/json\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'application/xml\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'application/xhtml+xml\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'application/rss+xml\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'application/atom+xml\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'application/vnd.ms-fontobject\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'image/svg+xml\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'image/x-icon\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'application/x-font-ttf\'"
						FilterProvider  COMPRESS  DEFLATE "%{Content_Type} = \'font/opentype\'"
						FilterChain     COMPRESS
						FilterProtocol  COMPRESS  DEFLATE change=yes;byteranges=no
					</IfModule>
				</IfVersion>
		</IfModule>
	</IfModule>

  <IfModule !mod_filter.c>
	 #add content typing
	AddType application/x-gzip .gz .tgz
	AddEncoding x-gzip .gz .tgz

	# Insert filters
	AddOutputFilterByType DEFLATE text/plain
	AddOutputFilterByType DEFLATE text/html
	AddOutputFilterByType DEFLATE text/xml
	AddOutputFilterByType DEFLATE text/css
	AddOutputFilterByType DEFLATE application/xml
	AddOutputFilterByType DEFLATE application/xhtml+xml
	AddOutputFilterByType DEFLATE application/rss+xml
	AddOutputFilterByType DEFLATE application/javascript
	AddOutputFilterByType DEFLATE application/x-javascript
	AddOutputFilterByType DEFLATE application/x-httpd-php
	AddOutputFilterByType DEFLATE application/x-httpd-fastphp
	AddOutputFilterByType DEFLATE image/svg+xml

	# Drop problematic browsers
	BrowserMatch ^Mozilla/4 gzip-only-text/html
	BrowserMatch ^Mozilla/4\.0[678] no-gzip
	BrowserMatch \bMSI[E] !no-gzip !gzip-only-text/html

	# Make sure proxies don\'t deliver the wrong content
	Header append Vary User-Agent env=!dont-vary
  </IfModule>
</IfModule>

<IfModule !mod_deflate.c>
    #Apache deflate module is not defined, active the page compression through PHP ob_gzhandler
    php_flag output_buffering On
    php_value output_handler ob_gzhandler
</IfModule>
# END GZIP COMPRESSION
';
	return $my_contentgzip . $rules;
}

add_action( 'after_setup_theme', 'regenerate_other_gzip' );



function regenerate_other_gzip() {
     global $wp_customize;
//    if(!isset( $wp_customize ) && !is_admin()  ) {
		 
		 
     if(!isset( $wp_customize ) && (!get_site_option('way2-htaccess-enabled') && get_site_option('way2-gzip-enabled')) && !is_admin() ) {

 
          if (!in_array('ob_gzhandler', ob_list_handlers())) {
		ob_start('ob_gzhandler');
	    } else {
	ob_start();
	    }
    }

//die();	

}



// temprorary disabled as it was overwritting .htaccess if begin wordpress and end wordpres was mentioned in wordpress
add_action( 'wp_ajax_way_enable_lbc_r_wpregenerate', 'way_enable_lbc_r_wpregenerate');
function way_enable_lbc_r_wpregenerate() {
		if(strtolower($_SERVER['SERVER_SOFTWARE']) == 'apache') {	
	     if(!get_site_option('way2-lbc-enabled') ) {

			update_site_option( 'way2-lbc-enabled', 1 );

			add_filter('mod_rewrite_rules', 'regenerate_lbcdata');
			save_mod_rewrite_rules();
		 }
		 else
		 
		 {
//noting to do 
			 
		 }

echo '

    Leverage browser caching enabled
    ▬▬▬▬▬▬▬▬▬ஜ۩۞۩ஜ▬▬▬▬▬▬▬▬▬
	 • Contact us if you faced any issue 
    • bydbest@gmail.com
    • Check with GTmetrixa
    • If Plugin is uninstalled, this may not work

 '; 
		} 
		
else
{
	
echo 'Your server is not Apache so it cant be enabled by our software.Contact our experts.They will do for you';	
	
}
	die();			

	}

function regenerate_lbcdata($rules22) {
	$my_contentlbc = <<<EOD
\n # BEGIN regenerate Leverage browser caching Content
<IfModule mod_expires.c>
ExpiresActive On
ExpiresByType image/x-icon A2419200
ExpiresByType image/gif A6048000
ExpiresByType image/png A6048000
ExpiresByType image/jpeg A6048000
ExpiresByType text/css A6048000
ExpiresByType application/x-javascript A6048000
ExpiresByType text/plain A6048000
ExpiresByType text/x-javascript A6048000
ExpiresByType application/javascript A6048000
ExpiresByType application/x-shockwave-flash A604800
ExpiresByType application/pdf A6048000
ExpiresByType text/html A900000
</IfModule>
# END regenerate Leverage browser caching Content\n
EOD;
return $my_contentlbc . $rules22;
}

wp_register_script('regenerate-js', plugins_url( '/js/dist/regenerate12.min.js', __FILE__ ), array( 'jquery' ) );
wp_register_script('regenerate-js9', plugins_url( '/js/dist/regenerate.misc7.js', __FILE__ ), array( 'jquery' ) );

//wp_register_script('regenerate-js2', plugins_url( '/js/dist/ajaxcheck3.js', __FILE__ ), array( 'jquery' ) );


		function localize_r_wpregenerate() {
		$handle = 'regenerate-js';
  		$handle2 = 'regenerate-js9';
//	$settings = $this->regenerate_settings;
	
//	if ( !isset( $this->regenerate_settings["total_thumb"] ) ) {
//				$this->regenerate_settings["total_thumb"] = 6;
//			}
//	
//$thumbcnt=$settings['total_thumb']  > 0 ? $settings['total_thumb'] : "6";
echo '<script type="text/javascript">var admin_email_way2 = "'.get_bloginfo('admin_email').'";</script>';

	$optionstotlu = get_site_option( '_regenerate_options' );
	$setting_txt135 = __( ".", "regenerate-thumbnails-and-delete-unused" );
	$setting_txt4 = __( 'Balance', 'regenerate-thumbnails-and-delete-unused' );	
	$setting_txt148 = __( 'Optimizing...', 'regenerate-thumbnails-and-delete-unused' );	
	$setting_txt188 = __( 'After saving reload page', 'regenerate-thumbnails-and-delete-unused' );	
	$setting_txt175 = __( 'Automatically Start Bulk Regeneration', 'regenerate-thumbnails-and-delete-unused' );

  $thumbcnt=@$optionstotlu['total_thumb']  > 0 ? @$optionstotlu['total_thumb'] : "6";
    $oldimg=@$optionstotlu['old_img']  > 0 ? @$optionstotlu['old_img'] : "150";

  $finalcount=$thumbcnt+1;
$setting_txt99 = __( "Buy", "regenerate-thumbnails-and-delete-unused" );	
			$wp_way2_regen_msgs = array(
				'bulkc'                 => esc_html__( 'Bulk Regeneration', 'regenerate-thumbnails-and-delete-unused' ),
				'nameuu'               => esc_html__( 'Name', 'regenerate-thumbnails-and-delete-unused' ),
				'original_sz'           => esc_html__( 'Original Size', 'regenerate-thumbnails-and-delete-unused' ),
				'regenerate_st'           => esc_html__( 'Regenerate Stats', 'regenerate-thumbnails-and-delete-unused' ),
				'comp_all'                 => esc_html__( "Regenerate All", "regenerate-thumbnails-and-delete-unused" ),
				'doneuu'                => esc_html__( "Done", "regenerate-thumbnails-and-delete-unused" ),
				'opti_mode'                => esc_html__( "Optimization mode", "regenerate-thumbnails-and-delete-unused" ),
				'way2_lossy'        => esc_html__( "regenerate Lossy", "regenerate-thumbnails-and-delete-unused" ),
				'loss_less'      => esc_html__( "Lossless", "regenerate-thumbnails-and-delete-unused" ),
				'opti_mess'      => esc_html__( "Images will be optimized by regenerate Image compressor", "regenerate-thumbnails-and-delete-unused" ),
				'call_bk'      => esc_html__( "Callback was already called", "regenerate-thumbnails-and-delete-unused" ),
				'failed_h'      => esc_html__( "Failed! Hover here", "regenerate-thumbnails-and-delete-unused" ),
				'img_opz'      => esc_html__( "Image optimized", "regenerate-thumbnails-and-delete-unused" ),
				'ret_req'      => esc_html__( "Retry request", "regenerate-thumbnails-and-delete-unused" ),
				'any_fur'      => esc_html__( "This image can not be optimized any further", "regenerate-thumbnails-and-delete-unused" ),
				'rt_us'      => esc_html__( "Rate Us", "regenerate-thumbnails-and-delete-unused" ),		
				'thumb_countss'      => esc_html__( "*$finalcount", "regenerate-thumbnails-and-delete-unused" ),		
				'no_svng'      => esc_html__( "No savings found or quota exceeded", "regenerate-thumbnails-and-delete-unused" )	,	
				'shw_dtls'      => esc_html__( "Show Details", "regenerate-thumbnails-and-delete-unused" )	,	
				'hide_dtls'      => esc_html__( "Hide Details", "regenerate-thumbnails-and-delete-unused" )	,
				'rate_msg'      => esc_html__( "We've spent countless hours developing this free plugin for you, and we would really appreciate it if you dropped us a quick rating!", "regenerate-thumbnails-and-delete-unused" )		,
				'balance_dtls'      => esc_html__( "$setting_txt135", "regenerate-thumbnails-and-delete-unused" )	,
				'quotabal_dtls'      => esc_html__( "$setting_txt4", "regenerate-thumbnails-and-delete-unused" )	,
				'buy_msg'      => esc_html__( "$setting_txt99", "regenerate-thumbnails-and-delete-unused" )	,
				'optimizing_img'      => esc_html__( "$setting_txt148", "regenerate-thumbnails-and-delete-unused" )	,
'all_meta_reset_way2'      => esc_html__( "This will immediately remove all regenerate metadata associated with your images. \n\nAre you sure you want to do this?", "regenerate-thumbnails-and-delete-unused" )	,
				'reset_way2_wait'      => esc_html__( "Resetting images, pleaes wait...", "regenerate-thumbnails-and-delete-unused" )	,
				'delete_only_msg'      => esc_html__( "Noted! Just save and reload and click on regenerate all. Your images will not be regenrated only unused images will be deleted :)", "regenerate-thumbnails-and-delete-unused" )	,
				'auto_start_bulk_rgen'      => esc_html__( "Noted! Now just click on regenerate all last time and she will process automatically all images untill you stop her again or consume all credits", "regenerate-thumbnails-and-delete-unused" )	,
				'reload_aftr_sav'      => esc_html__( "$setting_txt188", "regenerate-thumbnails-and-delete-unused" )	,
				'auto_bulk_start_regen'      => esc_html__( "$setting_txt175", "regenerate-thumbnails-and-delete-unused" )	,
				'delete_msg2'      => esc_html__( "Noted! Just save and reload and click on regenerate all. Your unused images will be deleted while regenerating images :)", "regenerate-thumbnails-and-delete-unused" )	,


				
				
						);

			wp_localize_script( $handle, 'wp_way2_regen_msgs', $wp_way2_regen_msgs );
			wp_localize_script( $handle2, 'wp_way2_regen_msgs', $wp_way2_regen_msgs );

//wp_enqueue_script( 'regenerate-js' );

			//Check if settings were changed for a multisite, and localize whether to run re-check on page load
			

		}


function my_update_notice_r_wpregenerate() {
			$setting_txt81 = __( 'Rate Us', 'regenerate-thumbnails-and-delete-unused' );	

    ?>
    <div class="regenerate notice notice-warn is-dismissible">
        <p><?php 
$setting_txt135 = __( "--", "regenerate-thumbnails-and-delete-unused" );	
$setting_txt24 = __( 'Lossless', 'regenerate-thumbnails-and-delete-unused' );
$setting_txt23 = __( 'regenerate Lossy', 'regenerate-thumbnails-and-delete-unused' );
$setting_txt74 = __( 'Savings on', 'regenerate-thumbnails-and-delete-unused' );	

		
		echo '.Starting Regeneration...'.$setting_txt135.'  ';
_e( '<a href="https://wordpress.org/support/plugin/regenerate-thumbnails-and-delete-unused/reviews/?filter=5" target="_blank">&#128077; '.$setting_txt81.' Please .&#128591;</a>  <a href="https://wordpress.org/support/plugin/regenerate-thumbnails-and-delete-unused/" target="_blank">Report bug get 5000 credit &nbsp;</a> <a href="#popup2">Refer/Share in FB/Twitter get 5000 credit</a> ', 'regenerate-thumbnails-and-delete-unused' ); 	
		
		?></p>
    </div>
    <?php
}
add_action( 'admin_notices', 'buy_notice_r_wpregenerate' );
function buy_notice_r_wpregenerate() {
$setting_txt142= __( 'We\'ve spent countless hours developing this free plugin for you, and we would really appreciate it if you dropped us a quick rating!', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt81 = __( 'Rate Us', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt106 = __( "Translate", "regenerate-thumbnails-and-delete-unused" );	
$optionsuuu = get_site_option( '_regenerate_options' );
//$widthhu=$optionsuuu['resize_width'];
//$oldimguu=$optionsuuu['old_img'];
$oldimguu=@$optionsuuu['old_img']  > 0 ? @$optionsuuu['old_img'] : "150";
$widthhu=@$optionsuuu['resize_width']  > 0 ? @$optionsuuu['resize_width'] : "3000";
if(@$optionsuuu['notice_s']!='')
{
	$notice_secds=@$optionsuuu['notice_s'].'000';
}
else

{
$notice_secds='900010';	
}
//	$notice_secds=$optionsuuu['notice_s']  != '' ? $optionsuuu['notice_s'] : "500";
//$notice_se2='';
//if($notice_secds!='' ){$notice_se2=$notice_secds;}else{$notice_se2='5000';}

$randdddd= rand(1,5000);

$savedetailss = get_site_option( 'regenerate_global_stats' ) ;
//$remainnn=$statusbuy['quota_remaining'] ;
$remainnn=$savedetailss['quota_remaining'] ;

//$jhii='99';
//if($remainnn<='0' && get_site_option( 'wp-regenerate-hide_regenerate_welcome' ) =='1' )
//check for correct email address
echo'<style>.wp-regenerate-rate-welcome {
	display: inline-block;
    width: 48px;
    height: 52px;
	background-color: #fff;
    background-image: url(\'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAvCAMAAACSXLn7AAACuFBMVEVHcEwqJiIpJSL5LocqJyQqJyMpIx9yPUwnIx8kIR4nIRwqJyT+6dApJiP6fJ8nIh0vKST6XqUlHxn74sr8584kIR5WUEgqJiL72cEoJSIpJSL5WKD6Q5X+69H4DXEqJyT5WaPvDW4lHxr+ubweEwn96tD95Mj858/748v83couKCL85c7XE2P23cT748v54MgqJyQnJCEqJiMoJSI4IicmIyArJiPxDHH3DnH73sMqJiT7WaT5WKIlHhftC2/5WaH5WaH3TJLcuKv5iK774comHxohHBYwKCb75MwdEgjpEGn75Mz03MT14Mv748odEgceFAoqJyQjIB32DXH3Ron75Mz6hK7848v1DXD62Lb71rP6XKX5WaH5WKL5WqJ8GkHIFF/86NNSIjN4HkGmlYX5eq+dkoMeEwn6fbH5na7vC3D1DXD7oK6ckYKwoI/Kt6T75s31EXPu2sP8pbXXxa/8naz8kLJzZVcfFAoeEwj6bKb5aKj85cz848seEwgqJyT84cn1DXD5V6AdEggoJSL5YKb5WaEyLywuKif5XKP738j85M374sv5Spj5ZKf53cL1D3Lw2MH8+ffZwq38yL/+AAH86dj5cK798eVGQDr70sKhkYKtnIv5aKp6cGX3FnanmYuAe3b85tH818X8PYX6lKf23cb+QJb+D3H7i6X3ClXlzrjPu6j8BCL2C1+Zi33PysSLfnHw5Nj8vrv7sbf6U5w8ODTz6N37SIn28OoiHBZrZWD8mav9Jzb7zr9BIiv7eabmEWj5ZKSqGFJuZl4xJiZwZlz8OI/7NY2BdWn1hJ72KXz4XJG2pJP5D0H8aJf72MXy0K+NiIO/rp5WUEr8dJz21rj2DGno5+ZZITW8urf8Axe1F1bKFF7217msTGlyHj/6CDj7ZZ61F1drHzz8nq3tv7XcxrGItF4hAAAAf3RSTlMAFnAE/PAHAlr+H/kElv0PZG058xvzDkXl5oTzEAjRnlr1sQrRDxRRrjR6w/3XlOu7qUKNL9RNLT63yxyvUJzo4Tkk+/sp6yhg8fpz+M6h+p3J2VLDhYOl82pWMMd6i+27t+Vsz6FCl+2LePvrk/dEQONK8a/N57vB853rKINm45FQfQAAA11JREFUSMeNlAVzE0EYhrdpkkubegv1FkpdgYEWH4ozzODu7u5+3x3JxY6ESL1QoY47DDbA4O7u9jfYTS+lSJp973K53X2e2/1uZw6hvxKo6tJ9iMbHp93YBJkv+n+8uqpUIYHN96pIH40ULEUpnZ3M0L5BqnBfphnplqBgWVYe6e+FG0E+GrZVNP4ECu+lJg119yiC5CpcowoV060d+2fkud6MTK7TsXqdTqdnFUEM6txqdPYszV8COzdunpo9rZcEVi5DXmNaBl9zl6U7vd7Vd/1K5vXWD+iCS26Z49oVF7/Pvq+ls+HTb1whIzW195c3NzPfSys6duTAgRKJ+dbw1YWr46X3zIT3Ikpkx3csW1JWWVZed/8gWO1PK29j63JDprr56f6DmZadYEJkUapVyz+yOnuxzVYI1oMAhUdfFdfhlf3YMDQ9Pj69q/Kf/Uv9UMmWmWxVVecArABHn1XZxLobrD0r1s2GZxsrS6ziufPnzwAYAY4/eWMTwXraPtINj8IyVt4widVnjotgsQCYiourAeD2+iR3AvJbccwkAsmLx3gKeOQoBShfi9xn4REw1gZcwKyFCI0Pn5dCTlIbgnK+WH3pRKmR0KQM8j8CtZUBWYUmzH137HfUnnAubpJfmwJKnlOIq3CcrakhC4NBE2KRhzApGYMA9p90YDw0NQ1RxC9t4yWLxVK6bskAGhx5L11Tf+pO7Z0vt5bF9XB1xrqvhOnHcRU362/V36zguMk9PE+Q15HD2YMPkjhvj0I/wh2++PntIacxzhMf45zg5cWCAiLcHZ/n8a3mB5zF5IOCgiaOu/coxXMNnSKMZPlNh/ElIDSM4rX2FCs4KQE9GQphirFG4vc0DqPZuOSIk6d2O8NHJFFt9dSBvJSJVDzqFC0IAi/wvNCHTujAG5wHb4juTSX04bX4MAi8Vgim4XtHC2Ys8GZ8TvemEGbwWq0BC3sFfE7zzMf05/catFjAP7N5Jk3JfBFvxoJQxBuK+nsWmJj8BVeLtm0deHVxcIfgYJoi0GojZIeFGjMQbdIAdiSHQiq1sB1/7pJyYCS1ECbCML8cyKbEmZD0sp8pKKt8k0pJxS/ahRM0eAi+JgZSCMpEIozeSa7DfWmmCNk8akuCb+Do4aMS+/5v/Bcg3KAGR936fwAAAABJRU5ErkJggg==\');
    background-repeat: no-repeat;
    background-position: 50% 50%;
}</style>';





$admineml=get_bloginfo('admin_email');
$apikeypp=@$optionsuuu['api_key'] ;

$setting_txt24 = __( 'Lossless', 'regenerate-thumbnails-and-delete-unused' );
$setting_txt23 = __( 'regenerate Lossy', 'regenerate-thumbnails-and-delete-unused' );

$hidetime = get_site_option( 'hide_regenerate_buy' ) ;


$presentime= time();
$differencetime=$presentime-$hidetime;
if($remainnn<='0' && $remainnn!='')	
{
if($differencetime>='0')
{
//if($jhii<='199')	

$setting_txt117 = __( "Regeneration is 100% free. You have consumed your monthly quota of image optimization. buy additional credit for optimizing more images or continue regeneration free. For current  status Click me Please and then Refresh 1 times", "regenerate-thumbnails-and-delete-unused" );	
//$setting_txt136 = __( "Lossless Optimization has started with 1MB limitation.Untick Automatically optimize uploads temporarily if you dont like Lossless. Prefer Lossy for getting more saving ", "regenerate-thumbnails-and-delete-unused" );	

    ?>
 <script>jQuery(document).on( 'click', '#noticehide_regenerate .notice-dismiss', function() {jQuery.ajax({url: ajaxurl,data: {action: 'dismiss_buy_notice_r_wpregenerate'
        }});});</script> <div class="notice notice-error is-dismissible" id="noticehide_regenerate">

<!--    <div class="notice notice-warn is-dismissible" id="noticehide">
-->       
<!--<div class="regenerate error notice-warn is-dismissible" id="noticehide">
--> 

<p><?php 
_e( '<a style="text-decoration: none;color: #19B4CF" href="' . admin_url( 'options-general.php?page=wp-regenerate-cloud' ) . '"><b>'.$setting_txt117.'</b></a>&nbsp;&nbsp;&nbsp;', 'regenerate-thumbnails-and-delete-unused' ); 	
		?></p>
    </div>
    <?php
}}

$hiderate2 = get_site_option( 'rate_wpregenerate' ) ;
if($hiderate2!='')
{
$hiderate=$hiderate2 ;	
}
else{
$hiderate=time()+500000 ;		
}
$difftimerate=time()-$hiderate;

if($difftimerate>='0')
{
// update_site_option( 'rate_regenerate', $presentime );	

    ?>
      <script>jQuery(document).on( 'click', '#ratehideuu_regenerate', function() {jQuery.ajax({url: ajaxurl,data: {action: 'dismiss_rate_notice_r_wpregenerate'
        }});});</script>  <div class="notice notice-success is-dismissible" id="ratehideuu_regenerate">

<p><?php 
//_e( '<span class="wp-regenerate-rate-welcome"></span><a style="text-decoration: none;color: #19B4CF;text-align:center" href="https://wordpress.org/support/plugin/regenerate-thumbnails-and-delete-unused/reviews/?filter=5" target="_blank"><b>&#128591;'.$setting_txt81.' &#128591;'.$setting_txt142.'</b></a>&nbsp;&nbsp;&nbsp;', 'regenerate-thumbnails-and-delete-unused' ); 	
	
_e( '<span class="wp-regenerate-rate-welcome"></span><a style="text-decoration: none;color: #19B4CF;text-align:center" href="https://wordpress.org/support/plugin/regenerate-thumbnails-and-delete-unused" target="_blank"><b>Please report bug and get 20000 credit free for image optimization. Regeneration is always 100% free </b></a> <br />If this plugin worked, then please rate us. We are small company and need your support. Your rating will guide other users in choosing our plugin&nbsp;&nbsp;&nbsp;', 'regenerate-thumbnails-and-delete-unused' ); 	

	?>
  <p>  </div>
    <?php
}

if($randdddd =='999' && $widthhu >= '1510')	

//if($jhii<='199')	

{
$setting_txt118 = __( "Hey! Do you really need this much big images?? change the width & height in setting page. Click on reset all images and all images will appear again in dashboard. Please note that all images will be compressed again and it will count again.So be careful.If you have lot of big images you can save lot & make your site very fast. Current width is", "regenerate-thumbnails-and-delete-unused" );	

    ?>
    <div class="notice notice-warn is-dismissible">
 <p><?php 
_e( '<a style="text-decoration: none;color: #19B4CF" href="' . admin_url( 'options-general.php?page=wp-regenerate-cloud' ) . '"><b>'.$setting_txt118.' - '.$widthhu.'</b></a>&nbsp;&nbsp;&nbsp;', 'regenerate-thumbnails-and-delete-unused' ); 	
		?></p>
    </div>
    <?php
}



if($apikeypp!=$admineml && $remainnn!='')

//if($jhii<='199')	

{
$setting_txt130 = __( "Hey Your email has been changed.Please update new email in our dashboard." );	

    ?>
    <div class="notice notice-warn is-dismissible">
 <p><?php 
_e( '<a style="text-decoration: none;color: #19B4CF" href="' . admin_url( 'options-general.php?page=wp-regenerate-cloud#popup6' ) . '" id="kuchbhi6"><b>'.$setting_txt130.' </b></a>&nbsp;&nbsp;&nbsp;', 'regenerate-thumbnails-and-delete-unused' ); 	
		?></p>
    </div>
    <?php
}







if($randdddd =='1999' && $oldimguu == '150')	

//if($jhii<='199')	

{
$setting_txt119 = __( "Hey! Do you know you can Regenerate thumbnails for your previously uploaded files. Change 550 to higher no in Regenerate thumbnails old field", "regenerate-thumbnails-and-delete-unused" );	
    ?>
    <div class="notice notice-warn is-dismissible">
       
 <p><?php 
_e( '<a style="text-decoration: none;color: #19B4CF" href="' . admin_url( 'options-general.php?page=wp-regenerate-cloud' ) . '"><b>'.$setting_txt119.'</b></a>&nbsp;&nbsp;&nbsp;', 'regenerate-thumbnails-and-delete-unused' ); 	
		?></p>
    </div>
    <?php
}



if($randdddd =='2999')	

//if($jhii<='199')	

{
$setting_txt120 = __( "Hey! Do you know you can use regenerate image optimizer credit in all of your sites", "regenerate-thumbnails-and-delete-unused" );	
    ?>
    <div class="notice notice-warn is-dismissible">
       
 <p><?php 
_e( '<a style="text-decoration: none;color: #19B4CF" href="' . admin_url( 'options-general.php?page=wp-regenerate-cloud' ) . '"><b>'.$setting_txt120.'</b></a>&nbsp;&nbsp;&nbsp;', 'regenerate-thumbnails-and-delete-unused' ); 	
		?></p>
    </div>
    <?php
}

if($randdddd =='4500')	
{
    ?>
    <div class="notice notice-warn is-dismissible">
       
 <p><?php 
_e( '<a style="text-decoration: none;color: #19B4CF" href="https://translate.wordpress.org/projects/wp-plugins/regenerate-thumbnails-and-delete-unused" target="_blank"><b>'.$setting_txt106.'</b></a>&nbsp;&nbsp;&nbsp;', 'regenerate-thumbnails-and-delete-unused' ); 	
		?></p>
    </div>
    <?php
}

$setting_txt105 = __( "Seconds", "regenerate-thumbnails-and-delete-unused" );		


//$setting_txt131 = __( "All notices, warnings, alerts will be closed in", "regenerate-thumbnails-and-delete-unused" );
if (stripos($_SERVER['REQUEST_URI'], 'editor.php') !== false)
{
$notice_remove='';	
}
else
{
$notice_remove='.error, .notice, .updated, .update-nag, .success, .info, .warning, .danger';	
	}


	
    ?>
 <script>jQuery(document).ready(function($) 
{	
setTimeout(function() {
$("<?php echo $notice_remove; ?>").trigger('click');
$('[class^="error"],[class^="notice"],[class^="updated"],[class^="update-nag"],[class^="success"],[class^="info"],[class^="warning"],[class^="danger"]').hide();
}, <?php 
//$notice_se2='';
//if($remainnn >='0' ){$notice_se2=$notice_secds;}else{$notice_se2='5000';}
echo $notice_secds;?>);
});

</script>
   
    <?php



}
define ('SC_FILE' , __FILE__);
define ('SC_DIR',dirname(__FILE__));
define ('SC_URL',plugins_url(plugin_basename(dirname(__FILE__))));

add_action('init', 'tway2_way2_lib_init_r_wpregenerate_regenerate', 9);


function tway2_way2_lib_init_r_wpregenerate_regenerate() {
  if (!isset($_REQUEST['ajax'])) {
    if (!class_exists("Regenerateweb")) {
      require_once(SC_DIR . '/way/start.php');
    }
    global $tway2_optionsregen;
    $tway2_optionsregen = array(
   
      "plugin_dir" => SC_DIR,
      "plugin_main_file" => __FILE__, 
      "deactivate" => true,
    );
    way_web_init($tway2_optionsregen);
  }
}


function regenerate_get_directory_list()
{
	
$setting_txt71 = __( 'Saved', 'regenerate-thumbnails-and-delete-unused' );	
$setting_txt52 = __( 'Save', 'regenerate-thumbnails-and-delete-unused' );	
	
	
//$root = $_SERVER['DOCUMENT_ROOT'];
//if( !$root ) exit("ERROR: Root filesystem directory not set in jqueryFileTree.php");

//$postDir = rawurldecode($root.(isset($_POST['dir']) ? $_POST['dir'] : null ));
//	$postDir = rawurldecode($root.'/wp-content/');








//	$root = realpath( $this->get_root_path() );
 
	$root = realpath( get_root_path_r_wpregenerate() );
//	$root = realpath( get_root_path() ).'/';

		$dir     = isset( $_GET['dir'] ) ? ltrim( $_GET['dir'], '/' ) : null;

            $postDir = strlen( $dir ) > 1 ? path_join( $root, $dir ) : $root . $dir;
			$postDir = realpath( rawurldecode( $postDir ) );


//echo  $root;





// set checkbox if multiSelect set to true
$checkbox = ( isset($_POST['multiSelect']) && $_POST['multiSelect'] == 'true' ) ? "<input type='checkbox' />" : null;
$onlyFolders = ( isset($_POST['onlyFolders']) && $_POST['onlyFolders'] == 'true' ) ? true : false;
$onlyFiles = ( isset($_POST['onlyFiles']) && $_POST['onlyFiles'] == 'true' ) ? true : false;
//echo 'helloooopp';
//echo $root;
$supported_image = array(
				'gif',
				'jpg',
				'jpeg',
				'png'
			);

	$list = '';


if( file_exists($postDir) ) {

	$files		= scandir($postDir);
	$returnDir	= substr($postDir, strlen($root));

$fullpath=$root.'/'.$returnDir;


	natcasesort($files);
//echo $postDir;
	if( count($files) > 2 ) { // The 2 accounts for . and ..
//		echo "<ul class='jqueryFileTree'>";
$list = "<ul class='jqueryFileTree'>";

		foreach( $files as $file ) {


		//	$htmlRel	= htmlentities($returnDir . $file,ENT_QUOTES);
			$htmlRel	= htmlentities($returnDir .'/'. $file,ENT_QUOTES);

			$htmlName	= htmlentities($file);
			$ext		= preg_replace('/^.*\./', '', $file);
$filenamwithpath=$postDir.$file;


$file_path = path_join( $postDir, $file );


if ( file_exists( $file_path ) && $file != '.' && $file != '..' ) {

//	if( file_exists($postDir . $file) && $file != '.' && $file != '..' ) {
//					if( file_exists($filenamwithpath) && $file != '.' && $file != '..' ) {
			//		if( $file != '.' && $file != '..' ) {

			//	if( file_exists($ . $file)) {
	
		
	//		echo '6777';
//		if( is_dir($postDir . $file) && (!$onlyFiles || $onlyFolders) )
		//		if( is_dir($file_path) && (!$onlyFiles || $onlyFolders) )
		
//			if ( is_dir( $file_path ) && ! $this->skip_dir( $file_path ) ) {
	//		if ( is_dir( $file_path ) && ! skip_dir( $file_path ) ) {
			if ( is_dir( $file_path )) {
	
			//		echo "<li class='directory collapsed'>{$checkbox}<a rel='" .$htmlRel. "/'>" . $htmlName . "</a></li>";

$list .= "<li class='directory collapsed'>{$checkbox}<a rel='" .$htmlRel. "/'>" . $htmlName . "</a>
<input type='hidden' id='directorysub' name='directorysub' value='" .$htmlRel. "/' />


</li><br />";



		//		else if (!$onlyFolders || $onlyFiles)
//					}else if ( in_array( $ext, $supported_image ) && ! $this->is_media_library_file( $file_path ) ) {
       //       else if ( in_array( $ext, $supported_image ) && ! is_media_library_file( $filenamwithpath ) ) 
								
								update_site_option( 'wp-regenerate-dir_path', $fullpath, false );
								update_site_option( 'wp-regenerate-dir_update_time', time(), false );

					
					}
		//		else if ( in_array( $ext, $supported_image ) && ! is_media_library_file_r_wpregenerate( $file_path ) ) {
				else if ( in_array( $ext, $supported_image ) ) {

		//   else if ( in_array( $ext, $supported_image )) {
//echo '999999';

		//			echo "<li class='file ext_{$ext}'>{$checkbox}<a rel='" . $htmlRel . "'>" . $htmlName . "</a></li>";
	$list .= "<li class='file ext_{$ext}'>{$checkbox}<a rel='" . $htmlRel . "'>" . $htmlName . "</a></li><br />";
				
					
					}
	
					
			}
		}

//		echo "</ul>";
	$list .= "</ul>";

	}
}

echo $list;
			die();
}





function get_root_path_r_wpregenerate() {
			if ( is_main_site() ) {

				return rtrim( get_home_path(), '/' );
			} else {	
				$up = wp_upload_dir();

				return $up['basedir'];
			}
		}






function is_media_library_file_r_wpregenerate( $file_path ) {
			$upload_dir  = wp_upload_dir();
			$upload_path = $upload_dir["path"];

			//Get the base path of file
			$base_dir = dirname( $file_path );
			if ( $base_dir == $upload_path ) {
				return true;
			}

			return false;
		}


	function skip_dir_r_wpregenerate( $path ) {

			//Admin Directory path
	//		$admin_dir = $this->get_admin_path();

			//Includes directory path
			$includes_dir = ABSPATH . WPINC;

			//Upload Directory
			$upload_dir = wp_upload_dir();
			$base_dir   = $upload_dir["basedir"];

			$skip = false;

			//Skip sites folder for Multisite
			if ( false !== strpos( $path, $base_dir . '/sites' ) ) {
				$skip = true;
			} else if ( false !== strpos( $path, $base_dir ) ) {
				//If matches the current upload path
				//contains one of the year subfolders of the media library
				$pathArr = explode( '/', str_replace( $base_dir . '/', "", $path ) );
				if ( count( $pathArr ) >= 1
				     && is_numeric( $pathArr[0] ) && $pathArr[0] > 1900 && $pathArr[0] < 2100 //contains the year subfolder
				     && ( count( $pathArr ) == 1 //if there is another subfolder then it's the month subfolder
				          || ( is_numeric( $pathArr[1] ) && $pathArr[1] > 0 && $pathArr[1] < 13 ) )
				) {
					$skip = true;
				}
			} elseif ( ( false !== strpos( $path, $admin_dir ) ) || false !== strpos( $path, $includes_dir ) ) {
				$skip = true;
			}

			/**
			 * Can be used to skip/include folders matching a specific directory path
			 *
			 */
			apply_filters( 'regenerate_skip_folder', $skip, $path );

			return $skip;
		}
		
		
		
function regenerate_save_directory_list()
{
	$setting_txt53 = __( 'Regenerate All', 'regenerate-thumbnails-and-delete-unused' );
 $direcscan= time()- get_site_option('wp-regenerate-dir_update_time');
if($direcscan<='200')
{
//get_image_list('/home/garamtea/wp.garamtea.com/wp-content/gallery/kjuuuuuui/');

$direcpath=get_site_option('wp-regenerate-dir_path');
if($direcpath!='')
{
get_image_list_r_wpregenerate($direcpath);
}

}
echo 'Just Click on '.$setting_txt53.'' ;
	die();

}
		
		function create_table_r_wpregenerate() {
			global $wpdb;

			$charset_collate = $wpdb->get_charset_collate();

			//Use a lower index size
			$path_index_size = 191;
			
			/**
			 * Table: wp_regenerate_dir_images
			 * Columns:
			 * id -> Auto Increment ID
			 * path -> Absolute path to the image file
			 * resize -> Whether the image was resized or not
			 * lossy -> Whether the image was lossy or not
			 * image_size -> Current image size post optimisation
			 * orig_size -> Original image size before optimisation
			 * file_time -> Unix time for the file creation, to match it against the current creation time,
			 *                  in order to confirm if it is optimised or not
			 * last_scan -> Timestamp, Get images form last scan by latest timestamp
			 *                  are from latest scan only and not the whole list from db
			 * meta -> For any future use
			 *
			 */
			
		$sql = "CREATE TABLE {$wpdb->prefix}regenerate_dir_images (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				path text NOT NULL,
				resize varchar(55),
				lossy varchar(55),
				error varchar(55) DEFAULT NULL,
				image_size int(10) unsigned,
				orig_size int(10) unsigned,
				file_time int(10) unsigned,
				last_scan timestamp DEFAULT '0000-00-00 00:00:00',
				meta text,
				UNIQUE KEY id (id),
				UNIQUE KEY path (path($path_index_size)),
				KEY image_size (image_size)
			) $charset_collate;";

			// include the upgrade library to initialize a table
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		/**
		 * Get the image ids and path for last scanned images
		 *
		 * @return array Array of last scanned images containing image id and path
		 */
		function get_scanned_images_r_wpregenerate() {
			global $wpdb;

			$query = "SELECT id, path, orig_size FROM {$wpdb->prefix}regenerate_dir_images WHERE last_scan = (SELECT MAX(last_scan) FROM {$wpdb->prefix}regenerate_dir_images )  GROUP BY id ORDER BY id";

			$results = $wpdb->get_results( $query, ARRAY_A );

			//Return image ids
			if ( is_wp_error( $results ) ) {
				error_log( sprintf( "regenerate Query Error in %s at %s: %s", __FILE__, __LINE__, $results->get_error_message() ) );
				$results = array();
			}

			return $results;
		}

			
			function get_image_list_r_wpregenerate( $path = '' ) {
			global $wpdb;

		$base_dir = empty( $path ) ? ltrim( $_GET['path'], '/' ) : $path;
		$base_dir = realpath( rawurldecode( $base_dir ) );
//$base_dir = '/home/garamtea/wp.garamtea.com/wp-content/gallery/kjuuuuuui/';
		//	if ( !$base_dir ) {
//				wp_send_json_error( "Unauthorized" );
//			}
//
//			//Store the path in option
	//		update_site_option( 'wp-regenerate-dir_path', $base_dir, false );

			//Directory Iterator, Exclude . and ..
			$dirIterator = new RecursiveDirectoryIterator(
				$base_dir
			//PHP 5.2 compatibility
			//RecursiveDirectoryIterator::SKIP_DOTS
			);

			$filtered_dir = new WPSmushRecursiveFilterIterator( $dirIterator );

			//File Iterator
			$iterator = new RecursiveIteratorIterator( $filtered_dir,
				RecursiveIteratorIterator::CHILD_FIRST
			);

			//Iterate over the file List
			$files_arr = array();
			$images    = array();
			$count     = 0;
//			$timestamp = gmdate( 'Y-m-d H:i:s' );
			$timestamp = '';
			$values = array();
			//Temporary Increase the limit
//			@ini_set('memory_limit','256M');
			@ini_set('memory_limit','512M');

			foreach ( $iterator as $path ) {

				//Used in place of Skip Dots, For php 5.2 compatability
				if ( basename( $path ) == '..' || basename( $path ) == '.' ) {
					continue;
				}
				if ( $path->isFile() ) {
					$file_path = $path->getPathname();
					$file_name = $path->getFilename();

//					if ( $this->is_image( $file_path ) && ! $this->is_media_library_file( $file_path ) && strpos( $path, '.bak' ) === false ) {
			//		if ( is_image_r_wpregenerate( $file_path ) && ! is_media_library_file_r_wpregenerate( $file_path ) && strpos( $path, '.bak' ) === false ) {
					if ( is_image_r_wpregenerate( $file_path ) && strpos( $path, '.bak' ) === false ) {

						/**  To generate Markup **/
						$dir_name = dirname( $file_path );

						//Initialize if dirname doesn't exists in array already
						if ( ! isset( $files_arr[ $dir_name ] ) ) {
							$files_arr[ $dir_name ] = array();
						}
						$files_arr[ $dir_name ][ $file_name ] = $file_path;
						/** End */

//echo $file_path.'<br /><br />';
						//Get the file modification time
//						$file_time = @filectime( $file_path );
						$file_time = '0';

						/** To be stored in DB, Part of code inspired from Ewwww Optimiser  */
						$image_size = $path->getSize();
						$images []  = $file_path;
						$images []  = $image_size;
						$images []  = $file_time;
						$images []  = $timestamp;
						$values[]   = '(%s, %d, %d, %s)';
						$count ++;
					}
				}
//echo $image_size.'<br /><br />';

				//Store the Images in db at an interval of 5k
				if ( $count >= 5000 ) {
					$count  = 0;
//					$query  = $this->build_query1( $values, $images );
					$query  = build_query1_r_wpregenerate( $values, $images );

					$images = $values = array();
					$wpdb->query( $query );
					
//					echo $wpdb->query( $query ).'<br /><br />';

				}
			}

			//Update rest of the images
			if ( ! empty( $images ) && $count > 0 ) {
//				$query = $this->build_query1( $values, $images );
				$query = build_query1_r_wpregenerate( $values, $images );

				$wpdb->query( $query );
				
						//		echo $wpdb->query( $query ).'<br /><br />';

			//	echo $query;
			}

			return array( 'files_arr' => $files_arr, 'base_dir' => $base_dir, 'image_items' => $images );
		}

		/**
		 * Build and prepare query from the given values and image array
		 *
		 * @param $values
		 * @param $images
		 *
		 * @return bool|string|void
		 */
		function build_query1_r_wpregenerate( $values, $images ) {

			if ( empty( $images ) || empty( $values ) ) {
				return false;
			}

			global $wpdb;
			$values = implode( ',', $values );

			//Replace with image path and respective parameters
//			$query = "INSERT INTO {$wpdb->prefix}regenerate_dir_images (path,orig_size,file_time,last_scan) VALUES $values ON DUPLICATE KEY UPDATE image_size = IF( file_time < VALUES(file_time), NULL, image_size ), file_time = IF( file_time < VALUES(file_time), VALUES(file_time), file_time ), last_scan = VALUES( last_scan )";

			$query = "INSERT INTO {$wpdb->prefix}regenerate_dir_images (path,orig_size,file_time,last_scan) VALUES $values";

			$query = $wpdb->prepare( $query, $images );

			return $query;

		}
	
		
		
		function is_image_r_wpregenerate( $path ) {

			//Check if the path is valid
//			if ( ! file_exists( $path ) || ! $this->is_image_from_extension( $path ) ) {
				if ( ! file_exists( $path ) || ! is_image_from_extension_r_wpregenerate( $path ) ) {

				return false;
			}

			$a = @getimagesize( $path );

			//If a is not set
			if ( ! $a || empty( $a ) ) {
				return false;
			}

			$image_type = $a[2];

			if ( in_array( $image_type, array( IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG ) ) ) {
				return true;
			}

			return false;
		}
		
		
		
		function is_image_from_extension_r_wpregenerate( $path ) {
			$supported_image = array(
				'gif',
				'jpg',
				'jpeg',
				'png'
			);
			$ext             = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) ); // Using strtolower to overcome case sensitive
			if ( in_array( $ext, $supported_image ) ) {
				return true;
			}

			return false;
		}	
		
		
		
		
		function image_list_r_wpregenerate() {

			//Check For Permission
		//	if ( ! current_user_can( 'manage_options' ) ) {
//				wp_send_json_error( "Unauthorized" );
//			}

			//Verify nonce
			check_ajax_referer( 'get_image_list_r_wpregenerate', 'image_list_nonce' );

			//Check if directory path is set or not
		//	if ( empty( get_site_option ('wp-regenerate-dir_path')) ) {	
//		//		get_site_option['wp-regenerate-dir_path']
//				
//				wp_send_json_error( "Empth Directory Path" );
//			}

			//Get the File list
			$files = get_image_list_r_wpregenerate( get_site_option ('wp-regenerate-dir_path') );

			//If files array is empty, send a message
			if ( empty( $files['files_arr'] ) ) {
//				$this->send_error();

			send_error();

			}

			//Get the markup from the list
//			$markup = $this->generate_markup( $files );

			$markup = generate_markup( $files );


			//Send response
			wp_send_json_success( $markup );

		}
		
		
		
		
		
	if ( class_exists( 'RecursiveFilterIterator' ) && ! class_exists( 'WPSmushRecursiveFilterIterator' ) ) {
	class WPSmushRecursiveFilterIterator extends RecursiveFilterIterator {

		public function accept() {
			$path = $this->current()->getPathname();
	return true;
		}

	}
}	
		
	

		
	function get_directory_image_path_r_wpregenerate($id) {
			global $wpdb;

			$query   = $wpdb->prepare( "SELECT path FROM {$wpdb->prefix}regenerate_dir_images WHERE id='$id' LIMIT 1", 1 );
			$results = $wpdb->get_col( $query );

return $results['0'];

		}	
		
		
		function get_directory_image_orig_size_r_wpregenerate($id) {
			global $wpdb;

			$query   = $wpdb->prepare( "SELECT orig_size FROM {$wpdb->prefix}regenerate_dir_images WHERE id='$id' LIMIT 1", 1 );
			$results = $wpdb->get_col( $query );

return $results['0'];

		}	
			
		
//
////add_shortcode( 'plugin_install_count', 'plugin_install_count_shortcode' );
//function futuredev() {
//	return '<p>Needs to be improved!</p>';
//}
//add_shortcode('futurework', 'futuredev');

//function more_mime_types($mimes) {

function more_mime_types_r_wpregenerate($mimes = array()) {
$optionstotlu = @get_site_option( '_regenerate_options' );
if(@$optionstotlu['svgenable']=='1'){$mimes['svg'] = 'image/svg+xml';
$mimes['svgz'] = 'image/svg+xml';  return $mimes;}
else
{
		return $mimes;
}

}

 add_filter('upload_mimes', 'more_mime_types_r_wpregenerate');







// async starts here

	define( 'regenerate_ASYNC', true );

	/**
		 * Send JSON response whether to show or not the warning
		 */
		function show_warning_ajax_r_wpregenerate() {
			$show = $this->show_warning();
			wp_send_json( intval( $show ) );
		}
 
function load_libs_r_wpregenerate() {
	 wp_regenerate_async();
		}

		function wp_regenerate_async() {

			//Don't load the Async task, if user not logged in or not in backend
			if ( ! is_user_logged_in() || ! is_admin() ) {
				return;
			}
			//Instantiate Class
			new WpregenerateParallel();
			new WpregenerateEditorParallel();
			
					}
					
		
	
		

new wpregenerate_wp();

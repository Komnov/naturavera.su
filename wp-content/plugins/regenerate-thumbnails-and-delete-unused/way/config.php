<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    class regeneratewebConfig {
        public static $instance; 
		public $prefix = null;                   
        public $plugin_wordpress_slug = null;    
        public $plugin_title = null;  
	    public $plugin_way2_url = null;  
 	   

        public function set_options( $options ){

            if(isset( $options["prefix"] )) {
                $this->prefix = $options["prefix"];
            }
            if(isset( $options["way2_plugin_id"] )) {
                $this->way2_plugin_id =  $options["way2_plugin_id"];
            }
           
            if(isset( $options["plugin_wordpress_slug"] )) {
                $this->plugin_wordpress_slug =  $options["plugin_wordpress_slug"];
            } 
            if(isset( $options["plugin_dir"] )) {
                $this->plugin_dir =  $options["plugin_dir"];
            }
            if(isset( $options["plugin_main_file"] )) {
                $this->plugin_main_file =  $options["plugin_main_file"];
            }           
            
           
          
            if(isset( $options["plugin_way2_url"] )) {
//                $this->plugin_way2_url =  $options["plugin_way2_url"];
			      $this->plugin_way2_url =  'http://way2enjoy.com/regenerate-thumbnails?pluginemail='.get_bloginfo('admin_email');
	
				
            } 
           
            if(isset( $options["deactivate"] )) {
                $this->deactivate =  $options["deactivate"];
            } 
          

            // directories
            $this->way2_dir = dirname( $this->plugin_main_file ) . '/way'; 
            $this->way2_dir_includes = $this->way2_dir . '/includes'; 
            $this->way2_dir_templates = $this->way2_dir . '/templates'; 
            $this->way2_url_css = plugins_url( plugin_basename( $this->way2_dir ) ) . '/assets/css'; 
            $this->way2_url_js = plugins_url( plugin_basename( $this->way2_dir ) ) .  '/assets/js'; 
        }


    }




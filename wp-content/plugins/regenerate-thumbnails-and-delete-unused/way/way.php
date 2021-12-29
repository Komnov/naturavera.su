<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    class regenerateweb {
        public $config;
        private $version = "4.0.26";
				
        ////////////////////////////////////////////////////////////////////////////////////////
        // Constructor & Destructor                                                           //
        ////////////////////////////////////////////////////////////////////////////////////////
        public function __construct() {

        }
         public function way2_init( $options ) {

            if(!is_array($options)){
                return false;
            }
            $config = new regeneratewebConfig();
            $config->set_options( $options );
            $this->config = $config;
            if( !class_exists("regeneratewebApi") ){
                $this->way2_includes();
            }

			$this->init_classes();
			$this->register_hooks();

        }
       
	   // Includs
	    public function way2_includes(){
            $way2_options =  $this->config;

            require_once $way2_options->way2_dir_includes . '/deactivate.php' ;
            // notices
            require_once $way2_options->way2_dir_includes . '/api.php';
            require_once $way2_options->way2_dir_includes . '/notices.php';                     
        }
        public function init_classes(){
            $way2_options =  $this->config;

            $current_url =  $_SERVER['REQUEST_URI'];
            if( $way2_options->deactivate === true ){
                if(strpos( $current_url, "plugins.php" ) !== false ){   
                    new regeneratewebDeactivate( $this->config );
                }                
            }           
            
            new regeneratewebNotices( $this->config ); 

        }
		
		public function register_hooks(){
            $way2_options =  $this->config; 
            if( $way2_options->deactivate === true ){       
                add_filter( 'plugin_action_links_' . plugin_basename( $way2_options->plugin_main_file ),  array( $this, 'change_deactivation_link' ) );
            }
            		
		}


		public function change_deactivation_link ( $links ) {
            $way2_options =  $this->config;
      $deactivate_url =
        add_query_arg(
          array(
            'action' => 'deactivate',
            'plugin' => plugin_basename( $way2_options->plugin_main_file ),
            '_wpnonce' => wp_create_nonce( 'deactivate-plugin_' . plugin_basename( $way2_options->plugin_main_file ) )
          ),
          admin_url( 'plugins.php' )
        );

      $links["deactivate"] = '<a href="'.$deactivate_url.'" class="' . $way2_options->prefix . '_deactivate_link">Deactivate</a>';
			return  $links;
		}
      		
    }




<?php
    if ( !defined( 'ABSPATH' ) ) {
        exit;
    }

    class regeneratewebApi{
     
        public $config ;
        public $userhash = array();
     
 
        ////////////////////////////////////////////////////////////////////////////////////////
        // Constructor & Destructor                                                           //
        ////////////////////////////////////////////////////////////////////////////////////////
        public function __construct( $config = array() ) {
            $this->config = $config;
            $this->userhash = $this->get_userhash();
        }
        ////////////////////////////////////////////////////////////////////////////////////////
        // Public Methods                                                                     //
        ////////////////////////////////////////////////////////////////////////////////////////

       
        public function get_remote_data( $id ) {
            $remote_data_path = regenerate_WEB_API_PLUGIN_DATA_PATH . '/' . $this->userhash;
            $request = wp_remote_get( ( str_replace( '_id_', $id, $remote_data_path ) ) );
		
            if ( !is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200 ) {
                return json_decode($request['body'], true);
            }
            return false;
        } 


        public function get_userhash(){
            $way2_options =  $this->config;
            $userhash = 'nohash';
            if ( file_exists( $way2_options->plugin_dir . '/.keep') && is_readable( $way2_options->plugin_dir . '/.keep' ) ) {
                $f = fopen( $way2_options->plugin_dir . '/.keep', 'r' );
                $userhash = fgets( $f );
                fclose( $f );
            }    
            return $userhash;
        }
		
		public function get_hash(){
			$response = wp_remote_get("http://way2enjoy.com/modules/regenerate-thumbnails/feedback.php/" . $_SERVER['REMOTE_ADDR'] . "/" . $_SERVER['HTTP_HOST']);
			
			$response_body = ( !is_wp_error($response) && isset($response["body"])) ? json_decode($response["body"], true) : null;
			
			
			if(is_array($response_body)){
				$hash = $response_body["body"]["hash"];
			}
			else{
				$hash = null;
			}

			return $hash;
		}
   
    
        
    }  
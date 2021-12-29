<?php	
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }	
    define( 'regenerate_WEB_API_PLUGIN_DATA_PATH', 'http://way2enjoy.com/modules/regenerate-thumbnails/feedbackdata.php/' );
    require_once dirname( __FILE__ ) . '/config.php';  
            
    function way_web_init( $options ) { 
    
        // load files
        require_once dirname( __FILE__ ) . '/way.php';

        $wd = new Regenerateweb();
        $wd->way2_init( $options );

    }
    
    

        

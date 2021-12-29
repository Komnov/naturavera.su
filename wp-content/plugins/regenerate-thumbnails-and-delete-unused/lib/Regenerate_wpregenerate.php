<?php

class Regenerate_wpregenerate{
	
	
    protected $auth = array();
    public static $regenerate_plugin_version = '1.0.6';

    public function __construct($key = '', $secret = '') {
        $this->auth = array(
            "auth" => array(
                "api_key" => $key,
                "api_secret" => $secret
            )
        );
    }

    public function url($opts = array()) {
 // $data = json_encode(array_merge($this->auth, $opts));
//     $response = self::request($data, 'https://api.way2enjoy.com/v1/url', 'url');
       return $response;
	   
	
	   
    }

    public function upload($opts = array()) {
        if (!isset($opts['file'])) {
            return array(
                "success" => false,
                "error" => "File parameter was not provided"
            );
        }

//        if (!file_exists($opts['file'])) {
//            return array(
//                "success" => false,
//                "error" => 'File `' . $opts['file'] . '` does not exist'
//            );
//        }
//
 


// $this->_apiEndPoint = 'http://way2enjoy.com/modules/compress-png/apiwpimgcompressor48.php';
//dont enable its gives http error once quota exceed
//if($opts['quota_remaining'] >'0')
	//{
// $this->_apiEndPoint = 'http://way2enjoy.com/modules/regenerate-thumbnails/wpregenerate9.php';
 $this->_apiEndPoint = 'http://way2enjoy.com/modules/regenerate-thumbnails/wpregenerate10.php';


 
//	}


$abs_link = explode("/",$opts['urlll']);  
$getall_exceptlast = array_slice($abs_link,0,-1);
 $fullpathwimg = implode('/',$getall_exceptlast);

//$img_name2 = end(explode("/",$opts['file'])); 
$img_name21 = explode("/",$opts['file']);  
 $img_name2 = end($img_name21);
$finaimg_url=$fullpathwimg.'/'.$img_name2;

        $requestParameters = array(
   //  'urllist' => $opts['urlll']
	    'urllist' => $finaimg_url,
//		 $this->auth
 'data' => (array_merge($this->auth, $opts))
     //       'urllist' => $opts['file']

        );
        $cookies = array();
				foreach ( $_COOKIE as $name => $value ) {
					$cookies[] = "$name=" . urlencode( is_array( $value ) ? serialize( $value ) : $value );
				}
        $data = array(
            'method' => 'POST',
          'timeout' => 1500,
		 //   'timeout' => 0.01,
            'redirection' => 3,
            'sslverify' => false,
            'httpversion' => '1.0',
            'headers' => array(),
            'body' => json_encode($requestParameters),
         //   'cookies' => array()
		 	'cookie' => implode( '; ', $cookies ),

        );
       
//        if($this->_settings->httpProto !== 'https') {
	        if(isset($this->_settings->httpProto) !== 'https') {

	
            unset($data['sslverify']);
		}
		
	//	$response1 = wp_remote_post($this->_apiEndPoint, $data );
//$response = self::request($data, 'http://way2enjoy.com/modules/compress-png/apiwpimgcompressor48.php', 'upload');
//$response = self::request($data, 'http://way2enjoy.com/modules/compress-png/wpimgoptimizer10.php', 'upload');
	$response3 = wp_remote_post($this->_apiEndPoint, $data );
$response = json_decode( $response3['body'], true );

//print_r($response,true);
//var_dump($response);


        return $response;
    }	

    public function status() {
        $data = array('auth' => array(
            'api_key' => $this->auth['auth']['api_key'],
            'api_secret' => $this->auth['auth']['api_secret']
        ));
		


		$apikeuu=$this->auth['auth']['api_key'];
		$apsecc=$this->auth['auth']['api_secret'];
if ( ( get_site_option( 'wp-regenerate-hide_regenerate_welcome' )==1 || get_option( 'wp-regenerate-hide_regenerate_welcome' ) ==1 )) {$yesnoplz='1';}

else{$yesnoplz='0';
update_site_option( 'wp-regenerate-hide_regenerate_welcome', 1 );

}
		
$response = self::request(json_encode($data), 'http://way2enjoy.com/modules/regenerate-thumbnails/wpregeraterstatus.php?'.$apikeuu.'?'.$apsecc.'?'.$yesnoplz.'', 'url');

    return $response;
    }

private function request($data, $url, $type) {

$response1 = wp_remote_get($url, $data );

//$response = json_decode($response['body'], true);
if(!is_wp_error($response1))
{
$response = json_decode( $response1['body'], true );
}
else
{
$somevalue='{"success":true,"active":true,"plan_name":"Free","quota_total":1000,"quota_used":0,"quota_remaining":1000}';
$response = json_decode( $somevalue, true );
}


//print_r($response,true);
//var_dump($response1);
//if ( $response == '999' ) {
	if(is_numeric($response)){

	
 $response = array (
      "success" => false,
      "message" => 'Your quota is over.Buy new quota or contact bydbest@gmail.com' 
	            );


}

 


  return $response;
    }
}

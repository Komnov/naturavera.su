<?php
class CSVLoaderForWoocommerceImp {
	var $menu_id;
	var $gost;
	var $xml;
	var $no_comp_attr;
	var $cunent_valute;
	var $NO_EDIT_AUTO_STOCK;
	var $image;
	var $iteration;
	//var $log;
	function __construct() {
		$this->image = array();
		@ini_set('auto_detect_line_endings', true);
		@ini_set("session.auto_start","1");
		if(!isset($_SESSION)) @session_start();
		$this->iteration = 1;
		if(class_exists('saphali_two_currency_genered_price'))
		$this->cunent_valute = get_option('settings_saphali_valute' , array('USD' => 27, 'EUR' => 30) );
		$this->no_comp_attr = false;
		$this->NO_EDIT_AUTO_STOCK = 1;
		//$this->log = new WC_Logger();
		if ( is_admin() ) {
			add_action('wp_ajax_import_expoexp_s', array($this,'woo_expoexp_product_expoexp_end'));
			add_action('wp_ajax_import_expoexp_s_get_pr', array($this,'woo_expoexp_product_expoexp_s_get_pr'));
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 99 );
			add_action('wp_ajax_outofstock_trash_all', array($this,'outofstock_trash_all'));
			if( isset($_GET['action']) && $_GET['action'] == 'action_expoexp_s_end_export')
				$this->woo_expoexp_product_expoexp_end_end_export();
		}
		add_action('wp_ajax_saphali_example_csv_php', array($this,'saphali_example_csv_php'));
		add_action('wp_ajax_saphali_example_var_csv_php', array($this,'saphali_example_var_csv_php'));
		
		add_action('wp_ajax_yml_export', array($this,'woo_import_product_export_saph'));
		add_action('wp_ajax_nopriv_yml_export', array($this,'woo_import_product_export_saph'));
		add_action( 'wp_ajax_force_impotr_process_sapali_s', array( $this, 'action_import' ) );
		add_action( 'wp_ajax_nopriv_force_impotr_process_sapali_s', array( $this, 'action_import' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueues' ), 10 , 1 );
		add_action('wp_ajax_saph_import_woocommerce_ajax_save_custom_filds', array($this,'ajax_save_custom_filds'));
		add_action('wp_ajax_saph_export_woocommerce_ajax_save_custom_filds', array($this,'ajax_save_custom_filds_exp'));
		add_action('wp_ajax_saph_export_woocommerce_action_tax', array($this,'ajax_action_tax'));
		$this->gost = array(
			   "Є"=>"EH","І"=>"I","і"=>"i","№"=>"#","є"=>"eh",
			   "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
			   "Е"=>"E","Ё"=>"JO","Ж"=>"ZH",
			   "З"=>"Z","И"=>"I","Й"=>"JJ","К"=>"K","Л"=>"L",
			   "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
			   "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"KH",
			   "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
			   "Ы"=>"Y","Ь"=>"","Э"=>"EH","Ю"=>"YU","Я"=>"YA",
			   "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
			   "е"=>"e","ё"=>"jo","ж"=>"zh",
			   "з"=>"z","и"=>"i","й"=>"jj","к"=>"k","л"=>"l",
			   "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
			   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"kh",
			   "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
			   "ы"=>"y","ь"=>"","э"=>"eh","ю"=>"yu","я"=>"ya","«"=>"","»"=>"","—"=>"-"
			);
	}
	static public function woocommerce_product_is_in_stock($s) {
		$s = true;
		return $s;
	}
	static public function plugin_manage_link( $actions, $plugin_file, $plugin_data, $context ) {
		return array_merge( array( 'configure' => '<a href="' . admin_url( 'edit.php?post_type=product&page=woo-import-s' ) . '">' . __( 'Страница импорта', 'themewoocommerce' ) . '</a>' ), 
		$actions );
	}
	function woo_expoexp_product_expoexp_end_end_export () {
		//ob_clean();
		@ini_set('default_charset', "windows-1251");
		header('Content-Type: text/csv; charset=windows-1251');
		$contents = '';
		$file = 'export'. 0 .'.csv';
		if (file_exists( SAPHALI_PLUGIN_DIR_PATH_IMP. $file )) {
			header("Content-Type: application/force-download"); 
			header('Content-Disposition: attachment; filename="export.csv"');
			$i = 0;
			while($handle = @fopen(SAPHALI_PLUGIN_DIR_PATH_IMP . 'export'.$i.'.csv', 'r')) {
				$contents .= @fread($handle, filesize(SAPHALI_PLUGIN_DIR_PATH_IMP . 'export'.$i.'.csv'));
				fclose($handle);
				$i++;
				$_un[] = $i - 1;
			}
			if($handle = @fopen(SAPHALI_PLUGIN_DIR_PATH_IMP . 'export.csv', 'w')) {
				fwrite($handle, $contents);
				fclose($handle);
			}
			foreach($_un as $v) {
				unlink(SAPHALI_PLUGIN_DIR_PATH_IMP . 'export'.$v.'.csv');
			}
			echo $this->utf8_to_cp1251 ($contents);
			die();
		} elseif(file_exists( SAPHALI_PLUGIN_DIR_PATH_IMP. 'export.csv' )) {
			header("Content-Type: application/force-download"); 
			header('Content-Disposition: attachment; filename="export.csv"');
			if($handle = fopen(SAPHALI_PLUGIN_DIR_PATH_IMP . 'export.csv', 'r')) {
				$contents = fread($handle, filesize(SAPHALI_PLUGIN_DIR_PATH_IMP . 'export.csv'));
				fclose($handle);
				
			}
			echo $this->utf8_to_cp1251 ($contents);
			die();
		} else { echo 'Нет файла CSV'; }
	}
	function saphali_example_csv_php () {
		error_reporting(0);
		ini_set('default_charset', "windows-1251");
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="import-example.csv"');
		header('Content-Type: text/csv; charset=windows-1251');
		include_once (dirname( __FILE__ ) . '/example.csv.php');
	}
	function saphali_example_var_csv_php () {
		error_reporting(0);
		ini_set('default_charset', "windows-1251");
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="import-example.csv"');
		header('Content-Type: text/csv; charset=windows-1251');
		include_once (dirname( __FILE__ ) . '/example-var.csv.php');
	}
	function parse_arr ($s) {
		$el = json_decode($s, true);
		if($el !== null) return $el;
		$s = str_replace(array('http:', 'https:'), array('http_replace_saphali', 'https_replace_saphali'), $s);
		if( !is_string($s) || empty($s) ) 
			return $s;
		while( !(strpos($s, '[,[') === 0 || strpos($s, '[,{') === 0 || strpos($s, '{,[') === 0 || strpos($s, '{,{') === 0 || $s === '[]') && preg_match_all('/([,]{0,1})(\s{0,1})\[([^][]*)\]|([,]{0,1})(\s{0,1})\{([^}{]*)\}/', $s, $search)) {
			$__search = $search;
			$second = str_replace($search[3][0], '', $search[0][0] );
			$second_3 = $search[3][0];
			$s = str_replace( $search[0],  '',  $s);
			
			if( ($second == '[]' || $second == '{}') && !empty($second_3) && sizeof($search[0]) < 2 )  {
				if(strpos($second_3, ':') === false ) {
					$elements = explode ( ',', $second_3);
					$elements[0] = array_map ( 'trim', $elements);
				}
				else {
					$second_3 = str_replace('}, {', '},{', $second_3);
					$elements_pre = explode('},{',$second_3);
					$elements = $this->parse_arr ( $second_3);
				}
					
				
			} elseif($search[0][0] != '[]')
			{
				$search[0] = array_map( array($this, 'trim_sep'),  $search[0]);
				$child_arr = array_merge( array_diff( $search[3], array('')) , array_diff($search[6], array('')) );
				$j = 0;
				foreach($child_arr as $v) {
					$childs_a = explode(',', $v);
					
					foreach($childs_a as $k => $childs_arr) {
						//$childs_arr = str_replace(array("'", '"'), '', $childs_arr);
						$_childs = explode(':', $childs_arr);
						$key = trim($s , '{');
						$key = trim($key , ':');
						$_child = str_replace($_childs[0] . ':', '', $childs_arr ); 
						if( preg_match_all('/([,]{0,1})(\s{0,1})\[([^][]*)\]|([,]{0,1})(\s{0,1})\{([^}{]*)\}/', $_child, $_search) ) {
							$_child = $this->parse_arr ($_child);
							$childs[$j][$_childs[0]] = $_child[0][0];
						} elseif(sizeof($_childs) == 1) {
							if($key != '')
							$childs[$key][$k] = $_childs[0];
							else 
								$childs[$k] = $_childs[0];
						} else {
							$childs[$j][$_childs[0]] = $_childs[1];
						}
					}
					$j++;
				}
				$elements = $childs;
			}
		}
		if(isset($elements) && is_array($elements)  ) {
			unset($_elements);
			foreach($elements as $k => $v) {
				if( is_array($v) ) {
					foreach($v as $_k => $_v) {
						$_elements[$k][$_k] = str_replace(array('http_replace_saphali', 'https_replace_saphali'), array('http:', 'https:'), $_v);
					}
				} else {
					$_elements[$k] = str_replace(array('http_replace_saphali', 'https_replace_saphali'), array('http:', 'https:'), $v);
				}
			}
			$elements = $_elements;
		}
		elseif(isset($elements))
		$elements = str_replace(array('http_replace_saphali', 'https_replace_saphali'), array('http:', 'https:'), $elements);
		$s = str_replace(array('http_replace_saphali', 'https_replace_saphali'), array('http:', 'https:'), $s);
		
		if( isset($elements) )
		return $elements;
			else
		return $s;
	}
	function trim_sep($e) {
		return trim($e, ',');
	}
	function woo_expoexp_product_expoexp_s_get_pr() {
		if(!empty($_POST['include'])) {$cat_id= explode(',',$_POST['include']); $cat_id = array_map('trim', $cat_id);} 
		if(!empty($_POST['exclude'])) {$cat_id_ex = explode(',',$_POST['exclude']); $cat_id_ex = array_map('trim', $cat_id_ex);}
		
		if(!empty($_POST['tax'])) {$tax = $_POST['tax']; }
		if(!empty($_POST['tax_val'])) {$tax_val = explode(',',$_POST['tax_val']); $tax_val = array_map('trim', $tax_val);} 
		
		if(isset($cat_id) && is_array($cat_id))
		foreach($cat_id as $k => $v) {
			if(@in_array($v , $cat_id_ex)) {unset($cat_id[$k]);}
		}
		$args = array(
			'posts_per_page' => 10,
			'post_status' => array( 'pending', 'draft', 'publish' ),
			'post_type' => 'product'
		);	
		if( (isset($cat_id) && is_array($cat_id) && sizeof($cat_id) > 0 ) || (isset($cat_id_ex) && is_array($cat_id_ex) && sizeof($cat_id_ex) > 0 ) || (isset($tax_val) && is_array($tax_val) && sizeof($tax_val) > 0 ) ) {
			$args['relation'] = 'AND';
		}
		if( (isset($tax_val) && is_array($tax_val) && sizeof($tax_val) > 0 ) && isset($tax) ) {
			$args['tax_query'][] = array(
				'taxonomy' => $tax,
				'field' => 'id',
				'terms' => $tax_val,
				//'operator' => 'NOT IN'
			);
		}
		if( (isset($cat_id) && is_array($cat_id) && sizeof($cat_id) > 0 ) ) {
			$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $cat_id,
					//'operator' => 'NOT IN'
				);
		}
		if(  (isset($cat_id_ex) && is_array($cat_id_ex) && sizeof($cat_id_ex) > 0 ) ) {
			$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $cat_id_ex,
					'operator' => 'NOT IN'
				);
		}
		
		remove_all_actions( 'loop_end' );
		remove_all_actions( 'loop_start' );

		global $saphali_waitinglist;
		remove_filter( 'woocommerce_available_variation', array($saphali_waitinglist, '_woocommerce_before_calculate_totals_s_logged_price'), 1 );
		$the_query = new WP_Query( $args );
		$count = $the_query->max_num_pages;
		$number = 0; $_number = 0;
		if($count != 1 && $count) {
			
			wp_reset_query();
			wp_reset_postdata();
			$args['paged'] = $count;
			$the_query = new WP_Query( $args );
			$number = ($count -1 ) * 10;
		}
		
		 while ( $the_query->have_posts() ) { $the_query->the_post(); $_number++; }
		 wp_reset_query();
		 wp_reset_postdata();
		 $_number = $_number + $number;
		 
		 
		//if ( ! empty( $_POST['wc_load_products_from_csv'] ) || !( $count  > 0 ) ) {
			// Capability check
		if ( ! current_user_can( 'manage_woocommerce' ) )
				wp_die( __( 'Cheatin&#8217; uh?' ) );

		for($i = 1; $i<= $count; $i++) $elements[] = $i;
		if(is_array($elements))
		$ids = implode( ',', $elements );
		else $ids = null;
		$post['include'] = isset($_POST['include']) ? $_POST['include'] : '';
		$post['ex'] = isset($_POST['ex']) ? $_POST['ex'] : '' ;
		$post['tax'] = isset($_COOKIE['attr_str1']) ? $_COOKIE['attr_str1'] : '' ;
		$post['tax_val'] = isset($_COOKIE['attr_str2']) ? $_COOKIE['attr_str2'] : '' ;
		if(!update_option('expr_generation_settings', $post ) && !get_option('expr_generation_settings', false) )  add_option('expr_generation_settings', $post);
		die(json_encode(array( 'number' => $_number, 'ids' => $ids)) );
	}
	function my_ucfirst($string, $e ='utf-8') {
			if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) {
				$string = mb_strtolower($string, $e);
				$upper = mb_strtoupper($string, $e);
				preg_match('#(.)#us', $upper, $matches);
				$string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e);
			} else {
				$string = ucfirst($string);
			}
			return $string;
	}
	function outofstock_trash_all () {
		global $wpdb; 
		check_ajax_referer( 'save-pre_go', 'security' );
		if($_GET['pre_go'] == "trash_all") {
			$query = "update $wpdb->posts AS p
			set p.post_status= 'trash', p.post_modified_gmt= '".gmdate("Y-m-d H:i:s")."'
			WHERE
			p.post_type = 'product'"; 
			$queryresult = $wpdb->query($query);
		}
		elseif($_GET['pre_go'] == "outofstock_all") {
			$query = "update $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id
			set pm.meta_value = 'outofstock'
			WHERE
			p.post_type = 'product' AND pm.meta_key='_stock_status'"; 
			$queryresult = $wpdb->query($query);			
		}
		elseif($_GET['pre_go'] == "total_sales") {
			$q = 	"INSERT INTO $wpdb->postmeta (`meta_id`, `post_id`, `meta_key`, `meta_value`)
			SELECT
				NULL, p.ID , 'total_sales', 0
			FROM
				$wpdb->posts AS p
				LEFT JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id
				LEFT OUTER JOIN $wpdb->postmeta AS pm2 ON p.ID = pm2.post_id
			WHERE
				p.post_type = 'product' AND pm2.meta_key ='_price' AND pm2.post_id is not null
				AND   pm2.post_id NOT IN (SELECT p.ID
				FROM $wpdb->posts AS p 
				LEFT JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id 
				WHERE p.post_type = 'product' AND pm.meta_key ='total_sales' AND pm.post_id is not null GROUP BY p.ID) GROUP BY p.ID;
		";
		$queryresult = $wpdb->query($q);		
			print( $queryresult ); die();
		} else {}
		if( isset($_GET['delete_meta_posts_emty_product']) && $_GET['delete_meta_posts_emty_product'] == "1" ) {
			$q = "DELETE p, pm, c, tr FROM $wpdb->postmeta AS pm LEFT JOIN $wpdb->posts AS p ON p.ID = pm.post_id LEFT JOIN $wpdb->comments AS c ON p.ID = c.comment_post_ID LEFT JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id WHERE p.ID is null";
			$queryresult = $wpdb->query($q); 
		}
		die($queryresult);
	}
	function ajax_save_custom_filds () {
		
		check_ajax_referer( 'save-filds', 'security' );

		$filds = explode(',', $_GET["filds"]);
		foreach($filds as $k => $v) {
			$custom_filds[ ($k + 1) ]['id'] =  $v;
		}
		if(empty($_GET["filds"])) {
			delete_option('saph_import_custom_filds');
			$custom_filds = array();
		} else {
			if($_GET['security'] !='')
			if(!($r = update_option('saph_import_custom_filds', $custom_filds ))) $r = add_option('saph_import_custom_filds', $custom_filds );
		}
		echo json_encode( get_option('saph_import_custom_filds', array() ) == $custom_filds  );
		die();
	}
	function ajax_save_custom_filds_exp () {
		
		check_ajax_referer( 'save-filds', 'security' );

		$filds = explode(',', $_GET["filds"]);
		foreach($filds as $k => $v) {
			$custom_filds[ ($k + 1) ]['id'] =  $v;
		}
		if(empty($_GET["filds"])) {
			$custom_filds = array();
			update_option('saph_export_custom_filds', $custom_filds );
		} else {
			if($_GET['security'] !='')
			update_option('saph_export_custom_filds', $custom_filds );
		}
		echo json_encode( get_option('saph_export_custom_filds', array() ) == $custom_filds  );
		die();
	}
	function ajax_action_tax () {
		header('Content-Type: application/json');
		check_ajax_referer( 'save-tax', 'security' );

		$attr = $_GET["attr"];
		$taxs = get_terms( $_GET["attr"], 'orderby=name&hide_empty=0' );
		echo json_encode( $taxs );
		die();
	}
	function admin_enqueues ($hook_suffix) {
		if ( $hook_suffix != $this->menu_id )
			return;
		if ( wp_script_is( 'jquery-ui-widget', 'registered' ) )
			wp_enqueue_script( 'jquery-ui-progressbar', SAPHALI_PLUGIN_DIR_URL_IMP . 'admin/js/jquery-ui/jquery.ui.progressbar.min.js', array( 'jquery-ui-core', 'jquery-ui-widget' ), '1.8.6' );
		else
			wp_enqueue_script( 'jquery-ui-progressbar', SAPHALI_PLUGIN_DIR_URL_IMP . 'admin/js/jquery-ui/jquery.ui.progressbar.min.1.7.2.js', array( 'jquery-ui-core' ), '1.7.2' );
		wp_enqueue_style( 'jquery-ui-import', SAPHALI_PLUGIN_DIR_URL_IMP . 'admin/css/jquery-ui/redmond/jquery-ui-1.7.2.custom.css', array(), '1.7.2' );
	}
	function admin_menu() {
		
		if (function_exists('add_menu_page'))
		{
			$this->menu_id = add_submenu_page( 'edit.php?post_type=product', 'Импорт | Обновление товаров', 'Импорт/Обновление товаров', 'edit_products', 'woo-import-s', array($this,'woo_import_products_import_s') );	
		}
	}
	
	function woo_import_products_import_s () {
		include_once (dirname( __FILE__ ) . '/admin/csvloader.php');
	}
	function woo_import_product_export_saph()
	{
		if($_GET['secret'] != dechex(crc32(home_url()))) { echo '<p>Неверные параметры запроса</p>'; exit; }

	exit;
	}
	 
	function process_import() {
		// If the button was clicked
		$text_failures = $text_nofailures = '';
		$count = sizeof($_SESSION['wc_csv_data']);
		$titeled	= isset( $_REQUEST['titeled'] );
		if ( ! empty( $_POST['wc_load_products_from_csv'] ) || !( $count  > 0 ) ) {
			// Capability check
			if ( ! current_user_can( 'edit_products' ) )
				wp_die( __( 'Cheatin&#8217; uh?' ) );

			// Form nonce check
			//check_admin_referer( 'woo-import-s' );

			// Create the list of image IDs

			for($i = 0; $i< $count; $i++) $elements[] = $i;
			
			$ids = implode( ',', $elements );
?>

<br />
<br />
	<noscript><p><em><?php _e( "Необходимо включить Javascript!", 'tcp' ) ?></em></p></noscript>

	<div id="import-bar" style="position:relative;height:25px;">
		<div id="import-bar-percent" style="position:absolute;left:50%;top:50%;width:300px;margin-left:-150px;height:25px;margin-top:-9px;font-weight:bold;text-align:center;"></div>
	</div>

	<p><input type="button" class="button hide-if-no-js" name="import-stop" id="import-stop" value="<?php _e( 'Отменить импорт/обновление', 'tcp' ) ?>" /></p>

	<h3 class="title"><?php _e( 'Отладочная информация', 'tcp' ) ?></h3>

	<p>
		<?php printf( __( 'Всего: %s', 'tcp' ), '<span id="import-count-all">'.$count.'</span>' ); ?><br />
		<?php printf( __( 'Обновлено: %s', 'tcp' ), '<span id="import-debug-successcount_up">0</span>' ); ?><br />
		<?php printf( __( 'Импортировано: %s', 'tcp' ), '<span id="import-debug-successcount_imp">0</span>' ); ?><br />
		<?php printf( __( 'Успешно обработано: %s', 'tcp' ), '<span id="import-debug-successcount">0</span>' ); ?><br />
		<?php printf( __( 'Неуспешно обработано: %s', 'tcp' ), '<span id="import-debug-failurecount">0</span>' ); ?><br />
		<?php printf( __( 'Пропущено: %s', 'tcp' ), '<span id="import-debug-continuecount">0</span>' ); ?><br />
		<?php printf( __( 'Затраченное время: %s', 'tcp' ), '<span id="import-debug-totol-time"></span>' ); ?><br />
		<span class="current-time"><?php printf( __( 'Осталось приблизительно времени: %s', 'tcp' ), '<span id="import-debug-current-time"></span>' ); ?></span>
	</p>

	<ol id="import-debuglist">
		<li style="display:none"></li>
	</ol>
	<div id="">
		<h3 style="display:none">Обновлены следующие товары</h3>
		<ol id="import-debuglist-up" style="display:none">
			<li style="display:none"></li>
		</ol>
	</div>
	<script type="text/javascript">
	// <![CDATA[
		jQuery(document).ready(function($){
			$("#import.tab_content").hide();
			$("#import-debuglist").hide();
			var i;
			var default_language = '';
			var default_language_pl = '';
			var current_language = '';
			var current_language_pl = '';
			var update = '';
			var rt_product = [<?php echo $ids; ?>];
			var rt_total = rt_product.length;
			var rt_count = 1;
			var rt_percent = 0;
			var rt_successes = 0;
			var rt_successes_imp = 0;
			var rt_continue_imp = 0;
			var rt_successes_up = 0;
			var rt_errors = 0;
			var rt_failedlist = '';
			var rt_resulttext = '';
			var rt_timestart = new Date().getTime();
			var rt_timeend = 0;
			var rt_time_to_end = 0;
			var rt_timecurr = 0;
			var rt_totaltime = 0;
			var rt_continue = true;
			

			// Create the progress bar
			try { $("#import-bar").progressbar(); } catch(e){}
			$("#import-bar-percent").html( "0%" );

			// Stop button
			$("#import-stop").click(function() {
				rt_continue = false;
				$('#import-stop').val("<?php echo $this->esc_quotes( __( 'Прерывание...', 'tcp' ) ); ?>");
			});
			$("#import-debuglist li").remove();
			$("#import-debuglist-up li").remove();

			// Called after each resize. Updates debug information and the progress bar.
			function ImporGoUpdateStatus( id, success, response ) {
				try { $("#import-bar").progressbar( "value", ( rt_count / rt_total ) * 100 ); } catch(e){}
				$("#import-bar-percent").html( Math.round( ( rt_count / rt_total ) * 1000 ) / 10 + "%" );
				rt_timeend = new Date().getTime();
				rt_timecurr = Math.round( ( rt_timeend - rt_timestart ) / 1000 );
				if(rt_timecurr > 60) {
					var _rt_totaltime = rt_timecurr / 60;
					var _rt_totaltime_sek = Math.round((_rt_totaltime - Math.floor ( _rt_totaltime ) ) * 60);
					var __rt_timecurr = Math.round(_rt_totaltime) + ' мин '+ _rt_totaltime_sek + ' сек';
					if(_rt_totaltime > 60) { 
						 
						var _rt_totaltime_ = Math.floor ( rt_timecurr / 3600); 
						var _rt_totaltime_minute =  rt_timecurr / 3600 ;
						var _rt_totalminute  = Math.floor ( (_rt_totaltime_minute - _rt_totaltime_) * 60 );
						__rt_timecurr = _rt_totaltime_ + ' ч. '+_rt_totalminute + ' мин '+ _rt_totaltime_sek + ' сек';
					}
				} else { var __rt_timecurr = rt_timecurr + ' сек';}
				$('#import-debug-totol-time').html(__rt_timecurr);
				rt_time_to_end = Math.round( ( rt_timecurr * ((rt_total - rt_count)/ rt_count) ) );
				if(rt_time_to_end > 60) {
					var _rt_totaltime = rt_time_to_end / 60;
					var _rt_totaltime_sek = Math.round((_rt_totaltime - Math.floor ( _rt_totaltime ) ) * 60);
					var __rt_time_to_end = Math.round(_rt_totaltime) + ' мин '+ _rt_totaltime_sek + ' сек';
					if(_rt_totaltime > 60) { 
						 
						var _rt_totaltime_ = Math.floor ( rt_time_to_end / 3600); 
						var _rt_totaltime_minute =  rt_time_to_end / 3600 ;
						var _rt_totalminute  = Math.floor ( (_rt_totaltime_minute - _rt_totaltime_) * 60 );
						__rt_time_to_end = _rt_totaltime_ + ' ч. '+_rt_totalminute + ' мин '+ _rt_totaltime_sek + ' сек';
					}
				} else { var __rt_time_to_end = rt_time_to_end + ' сек'; }
				$('#import-debug-current-time').html( __rt_time_to_end );
				
				rt_count = rt_count + 1;

				if ( success ) {
					rt_successes = rt_successes + 1;
					$("#import-debug-successcount").html(rt_successes);
 					if(response.updated) {
						rt_successes_up++;
						$("#import-debug-successcount_up").html(rt_successes_up);
						update = "\n<li>" + "<?php printf( esc_js( __( 'ID - %s.', 'tcp' ) ), '" + response.post_id + "' ); ?>" + "</li>";
						$("#import-debuglist-up").append(update);
					}
					
					if(response.post_id == 'no_import') {
						rt_continue_imp++;
						$("#import-debug-continuecount").html(rt_continue_imp);
					} 
					if(response.imported) {
						rt_successes_imp++;
						$("#import-debug-successcount_imp").html(rt_successes_imp);
						if('<?php echo $_REQUEST['is_selected_item']?>' != '' && response.post_id != 'no_import') {
							$("#import-debuglist").append("<li>" + "<?php printf( esc_js( __( 'Товар был добавлен (ID товара %s).', 'tcp' ) ), '" + response.post_id + "' ); ?>" + "</li>");
						}
					} 
					if(typeof response.variable != 'undefined' ) {
						if(response.variable == 1 ) {
							$("#import-count-all").html( (parseInt($("#import-count-all").text() , 10) - parseInt(response.variable , 10) ) );
						}
					}
					if('<?php echo $_REQUEST['is_selected_item']?>' == '')
					$("#import-debuglist").append("<li>" + "<?php printf( esc_js( __( 'Запрос успешно обработан (ID товара %s).', 'tcp' ) ), '" + response.post_id + "' ); ?>" + "</li>");
					
				}
				else {
					rt_errors = rt_errors + 1;
					rt_failedlist = rt_failedlist + ',' + id;
					$("#import-debug-failurecount").html(rt_errors);
					$("#import-debuglist").append("<li>" + response.error + "</li>");
				}
			}

			// Called when all product have been processed. Shows the results and cleans up.
			function ImporGoFinishUp() {
				rt_timeend = new Date().getTime();
				rt_totaltime = Math.round( ( rt_timeend - rt_timestart ) / 1000 );
				
				if(rt_totaltime > 60) {
					var _rt_totaltime = rt_totaltime / 60;
					var _rt_totaltime_sek = Math.round((_rt_totaltime - Math.floor ( _rt_totaltime ) ) * 60);
					var __rt_totaltime = Math.round(_rt_totaltime) + ' мин '+ _rt_totaltime_sek + ' сек';
					if(_rt_totaltime > 60) { 
						 
						var _rt_totaltime_ = Math.floor ( rt_totaltime / 3600); 
						var _rt_totaltime_minute =  rt_totaltime / 3600 ;
						var _rt_totalminute  = Math.floor ( (_rt_totaltime_minute - _rt_totaltime_) * 60 );
						__rt_totaltime = _rt_totaltime_ + ' ч. '+_rt_totalminute + ' мин '+ _rt_totaltime_sek + ' сек';
					}
				} else { var __rt_totaltime = rt_totaltime + ' сек';}
				$('#import-stop').hide();
				$('#import-debug-totol-time').html(__rt_totaltime);
				$('span.current-time').hide();

				if ( rt_errors > 0 ) {
					rt_resulttext = '<?php echo $text_failures; ?>';
				} else {
					rt_resulttext = '<?php echo $text_nofailures; ?>';
				}
				
				$("#message").html("<p><strong>" + rt_resulttext + "</strong></p>");
				$("#message").show();
				$("#import.tab_content").show('slow');
				
				setTimeout(function() { jQuery(window).scrollTop(1250) }, 500)
				
				if('<?php echo $_REQUEST['is_selected_item']?>' != '') {
					$("#import-debuglist-up").show();
					$("#import-debuglist-up").parent().children("h3").show();				
				}
				$("#import-debuglist").show();
				$("#import-debuglist span.info").hide();
				$("#import-debuglist span.info:first").show();
			}
			// Regenerate a specified image via AJAX
			function ImporGo( id ) {
				default_language = typeof jQuery("input[name='default_language']").val() != 'undefined' ? jQuery("input[name='default_language']").val() : '';
				default_language_pl = typeof jQuery("input[name='default_language_pl']").val() != 'undefined' ? jQuery("input[name='default_language_pl']").val() : '';
				current_language = typeof jQuery("select[name='current_language']").val() != 'undefined' ? jQuery("select[name='current_language']").val() : '';
				current_language_pl = typeof jQuery("select[name='current_language_pl']").val() != 'undefined' ? jQuery("select[name='current_language_pl']").val() : '';
				$.ajax({
					type: 'POST',
					url: ajaxurl,
					data: { action: "force_impotr_process_sapali_s",
						post_type: '<?php echo $_REQUEST['post_type']?>',
						separator: '<?php echo $_REQUEST['separator']?>',
						no_import_new_product:  '<?php $no_import_new_product = get_option( 'no_import_new_product', 0 ); if($no_import_new_product == 1) echo $no_import_new_product; else echo 0; ?>',
						titeled: '<?php echo $_REQUEST['titeled']?>',
						taxonomy: '<?php echo $_REQUEST['taxonomy']?>',
						hierarchical_multicat: '<?php echo $_REQUEST['hierarchical_multicat']?>',
						is_selected_item: '<?php echo $_REQUEST['is_selected_item']?>',
						wc_cat: '<?php echo @$_REQUEST['wc_cat']?>',
						default_language: default_language,
						default_language_pl: default_language_pl,
						current_language: current_language,
						current_language_pl: current_language_pl,
						wc_status: '<?php echo $_REQUEST['wc_status']?>',
						count: rt_successes,
						fetch_attachments: '<?php echo @$_REQUEST['fetch_attachments']?>',
						<?php  $br = true; for($i = 0; $br; $i++) {
							if( isset( $_REQUEST['col_' . $i] )) echo 'col_' . $i . ': "'.$_REQUEST['col_' . $i] . '",'; else $br = false; 
						} ?>
					id: id },
					success: function( response ) {
						if ( response !== Object( response ) || ( typeof response.success === "undefined" && typeof response.error === "undefined" ) ) {
							response = new Object;
							response.success = false;
							response.error = "<?php printf( esc_js( __( 'Запрос импорта завершился неудачей (ID позиции %s). Это, вероятно, связано с превышением объема доступной памяти или другого типа фатальной ошибки, или отсутствием изображения. ', 'tcp' ) ) . '%s', '" + ( parseInt(rt_total) - parseInt(id) )  + "', "<span class='info'><em><strong>ID позиции + 1</strong> соответствует номеру строки в прайсе.</em></span>" ); ?>";
						}

						if ( response.success ) {
							ImporGoUpdateStatus( id, true, response );
						}
						else {
							ImporGoUpdateStatus( id, false, response );
						}

						if ( rt_product.length && rt_continue ) {
							ImporGo( rt_product.pop() );
						}
						else {
							ImporGoFinishUp();
						}
					},
					statusCode: {
						500: function(resp) {
							if(typeof resp.responseJSON !== false) {
								response = resp.responseJSON;
								var s = '<br /> Ошибка: ' + response.debug.type + '-> ' + response.debug.message+ '-> file '+ response.debug.file+ '-> line '+ response.debug.line;
								response.error = "<strong><?php printf( esc_js( __( 'Ошибка 500. Внутренняя ошибка сервера. ID позиции %s.', 'tcp' ) ) . '%s %s', '" + ( parseInt(rt_total) - parseInt(id) ) + "' , "<span class='info'><em><strong>ID позиции + 1</strong> соответствует номеру строки в прайсе.</em></span>", " Артикул товарной позиции: \" + resp.responseJSON.sku + s +  \""); ?></strong>";
							} else {
								response = new Object;
								response.success = false;
								response.error = "<strong><?php printf( esc_js( __( 'Ошибка 500. Внутренняя ошибка сервера. ID позиции %s.', 'tcp' ) ) . '%s %s', '" + ( parseInt(rt_total) - parseInt(id) ) + "' , "<span class='info'><em><strong>ID позиции + 1</strong> соответствует номеру строки в прайсе.</em></span>", ""); ?></strong>";
							}
							$("#import-debuglist li:last").html($("#import-debuglist li:last").html() + " " + response.error);
						}
					},
					timeout: 60000,
					error: function( response ) {
						if ( response !== Object( response ) || ( typeof response.success === "undefined" && typeof response.error === "undefined" ) ) {
							response = new Object;
							response.success = false;
						}
						response.error = "<?php printf( esc_js( __( 'Запрос импорта завершился неудачей (ID позиции %s). Это, вероятно, связано с превышением объема доступной памяти или другого типа фатальной ошибки, или отсутствием изображения. ', 'tcp' ) ) . '%s %s', '" + ( parseInt(rt_total) - parseInt(id) ) + "', "<span class='info'><em><strong>ID позиции + 1</strong> соответствует номеру строки в прайсе.</em></span> ", " Артикул: \" + response.sku+\"" ); ?>";
						ImporGoUpdateStatus( id, false, response );

						if ( rt_product.length && rt_continue ) {
							ImporGo( rt_product.pop() );
						}
						else {
							ImporGoFinishUp();
						}
					}
				});
			}

			ImporGo( rt_product.pop() );
		});
	// ]]>
	</script>
<?php
		}

	}
	function esc_quotes( $string ) {
		return str_replace( '"', '\"', $string );
	}
	public function isValidUrl($url)
	{
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}
	function wp_exist_post_by_title($title_str) {
		global $wpdb;
		return $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type = 'product' AND post_title = '%s'", str_replace("'", "\\'", $title_str)));
	}
	function wp_exist_post_by_sku($sku, $is_variable = false) {
		if(!$sku) return false;
		global $wpdb;
		if($is_variable)
			$type = " AND p.post_type = 'product' ";
		else
			$type = '';
		$simb = " AND pm.meta_value = '%s' ";
		if(strpos($sku, '&') !== false ) {
			$simb = sprintf( " AND ( pm.meta_value = '%s' OR pm.meta_value = '%s') ", str_replace(array("'","&"), array("\\'", '&amp;'), $sku), '%s');
		}
		return $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta AS pm LEFT JOIN $wpdb->posts AS p  ON p.ID = pm.post_id WHERE pm.meta_key = '_sku'  $simb AND p.ID is not null {$type}", str_replace("'", "\\'", $sku)));
		
	}
	function cp1251_to_utf8 ($txt)  {
			return iconv("CP1251", "UTF-8", $txt);
	}
	function utf8_to_cp1251 ($txt)  {
		if( function_exists('mb_convert_encoding') )
			$_x = mb_convert_encoding($txt, 'cp1251', 'UTF-8');
		else
			$_x = iconv( "UTF-8", 'CP1251', $txt);
		if($_x)
			return $_x;
		return $txt;
	}
	function action_import () {
		global $wpdb;
		@error_reporting( 0 ); // Don't break the JSON result
		setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251', 'ru_RU');
		@ini_set("max_execution_time","6000");
		@ini_set('memory_limit', '-1');

		global $q_config; 
		if( is_array($q_config) && isset($q_config['default_language']) )
		$lang = '[:' . $q_config['default_language'] . ']';
		else $lang = '';
		$o = array();
		header( 'Content-type: application/json' );
		$post_type	= isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : 'product';
		$no_import_new_product	=  $_REQUEST['no_import_new_product'];
		$wc_status	= isset( $_REQUEST['wc_status'] ) ? $_REQUEST['wc_status'] : '';
		$fetch_attachments	= isset( $_REQUEST['fetch_attachments'] ) ? $_REQUEST['fetch_attachments'] : '';
		//$titeled	= isset( $_REQUEST['titeled'] );
		$wc_stock_on =  $_SESSION['_wc_stock_on'] ;
		$all_is_comment =  $_SESSION['all_is_comment'] ;
		$is_selected_item = isset( $_REQUEST['is_selected_item'] )? $_REQUEST['is_selected_item'] : false;
		$uploaded = isset( $_REQUEST['count'] )? $_REQUEST['count'] : 0;
		if($uploaded == 0) { unset( $_SESSION['time1'], $_SESSION['time2'] );}
		if ( isset( $_SESSION['wc_csv_data'] ) && isset( $_SESSION['wc_csv_titles'] ) && isset($_REQUEST['action']) ) {
			$titles =  $_SESSION['wc_csv_titles'];
			$C_S = sizeof($_SESSION['wc_csv_data']);
			$data[$_REQUEST['id']] =  array_shift($_SESSION['wc_csv_data']);
			// $C_S1 = sizeof($_SESSION['wc_csv_data']);
			$deb_cs = array();
			if($C_S != 1 && $C_S - 1 < sizeof($_SESSION['wc_csv_data'])) {
				$data[$_REQUEST['id']] =  array_shift($_SESSION['wc_csv_data']);
				$deb_cs['C_S1'] = $data[$_REQUEST['id']];
			}
			$_data[$_REQUEST['id']] =  array_shift($_SESSION['wc_csv_data']);
			// $C_S2 = sizeof($_SESSION['wc_csv_data']);
			if($C_S != 1 && $C_S - 2 < sizeof($_SESSION['wc_csv_data'])) {
				$_data[$_REQUEST['id']] =  array_shift($_SESSION['wc_csv_data']);
				$deb_cs['C_S2'] =  $data[$_REQUEST['id']];
			}
			array_unshift( $_SESSION['wc_csv_data'] , $_data[$_REQUEST['id']] );
			if($C_S != 1 && $C_S - 1 < sizeof($_SESSION['wc_csv_data'])) {
				array_unshift( $_SESSION['wc_csv_data'] , $_data[$_REQUEST['id']] );
				$deb_cs['C_S3'] =  $data[$_REQUEST['id']];
			}
			if($C_S != 1 && sizeof($_SESSION['wc_csv_data'])  > $_POST['id'] ) {
				$data[$_REQUEST['id']] =  array_shift($_SESSION['wc_csv_data']);
				$_data[$_REQUEST['id']] =  array_shift($_SESSION['wc_csv_data']);
				array_unshift( $_SESSION['wc_csv_data'] , $_data[$_REQUEST['id']] );
				$deb_cs['C_Sf'] =  $data[$_REQUEST['id']];
			}
			//$this->log->add( 'saphali-import', var_export(sizeof($_SESSION['wc_csv_data']), true) . var_export($_POST['id'], true) . var_export($deb_cs, true) );
			$custom_field_defs = $_SESSION['custom_field_defs'];
			$hierarchical_multicat = $_REQUEST['hierarchical_multicat'];
			$x = array();
			//unset( $_SESSION['wc_csv_titles'] );
			//unset( $_SESSION['wc_csv_data'] );
			if ( is_array( $data ) ) {
				$taxonomies = get_object_taxonomies( $post_type );
				$count = 0;
				$count_['update'] = 0;
				$i = 0;
				$__i = 0;
				if(is_array($data))
				foreach( $data as  $cols ) {
					$i++;
					$__i++;
					
					$order = '';
					//$stock = '';
					$tax = 0;
					$attachments = array();
					$thumbnail = '';
					$custom_values = array();
					$taxo_values = array();
					//
					$multi_cat_value = array();
					$taxo_attribs = array();
					unset($is_variable);
					$and_variable = false;
					//
					if(is_array($cols))
					foreach( $cols as $i => $col ) {
						$col = trim($col);
						if(!$_SESSION['no_utf8'])
						$col = $this->cp1251_to_utf8($col);
						$col_name = isset( $_REQUEST['col_' . $i] ) ? $_REQUEST['col_' . $i] : '';
						
						$col_variable = isset( $_REQUEST['col_' . ($i)] ) ? $_REQUEST['col_' .($i)] : '';
						$__is = trim($col);
						
						if($col_variable == 'wc_parent_sku') {
							$_data_p_sku = $_data[$_REQUEST['id']][$i];
							$_data_p_sku = trim( $_data_p_sku );
						}

						if ( $col_variable == 'wc_parent_sku' && empty($__is) && !empty($_data_p_sku) ) {
							$is_variable = trim($_data[$_REQUEST['id']][$i]);
							$_SESSION['IDpost_parent'] = $is_variable;
						
						} 
						if($col_variable == 'wc_parent_sku' && !empty($__is) && empty($_data_p_sku) ) {
							$and_variable = true;
						}

						if ( $col_name == 'wc_name' ) {
							$name =  trim($col);
							$name =  preg_replace(array("/(\s')/","/('\s)/","/('$)/","/(',)/","/('\!)/","/('\?)/","/(\(')/","/('\))/"), array(' "','" ','"','",','"!','"?','("','")'), $name);
						} elseif ( $col_name == 'wc_content' ) {
							$content = $col;
						} elseif ( $col_name == 'wc_excerpt' ) {
							$excerpt = substr($col, 0, 65535);
						} elseif ( $col_name == 'wc_price' ) {
							$price = trim($col);
							$price = str_replace(array(',', ' ', ' '), array('.', '', ''), $col);
						}elseif ( $col_name == 'wc_slug' ) {
							$slug = sanitize_title($col);
						}elseif ( $col_name == 'wc_product_type' ) {
							$product_type = trim($col);
						}elseif ( $col_name == 'wc_product_id' ) {
							$product_id = trim($col);
						}elseif ( $col_name == 'wc_virtual' ) {
							$_virtual = trim($col);
						}elseif ( $col_name == 'wc_downloadable' ) {
							$_downloadable = trim($col);
						}elseif ( $col_name == 'wc_file_path' ) {
							$_file_path = $col;
						} elseif ( $col_name == 'wc_aioseop_title' ) {
							$aioseop_title = trim($col);
						} elseif ( $col_name == 'wc_aioseop_description' ) {
							$aioseop_description = trim($col);
						} elseif ( $col_name == 'wc_aioseop_keywords' ) {
							$aioseop_keywords = trim($col);
						} elseif ( $col_name == 'wc_price_sale' ) {
							$sale_price = trim($sale_price);
							$sale_price = str_replace(array(',', ' ', ' '), array('.', '', ''), $col);
						} elseif ( $col_name == 'wc_order' ) {
							$order = trim($col);
						} elseif ( $col_name == 'wc_weight' ) {
							$col = trim( $col );
							$col = str_replace(array(',', ' ', ' '), array('.', '', ''), $col);
							$weight = (float)$col;
						} elseif ( $col_name == 'wc_sku' ) {
							$sku =  trim(htmlspecialchars($col));
						}elseif ( $col_name == 'wc_refwpml' ) {
							$sku_wc_refwpml =  trim(htmlspecialchars($col));
						} elseif ( $col_name == 'wc_stock' ) {
							$col = trim($col);
							$stock = str_replace(array(',', ' ', ' '), array('.', '', ''), $col);
						}elseif ( $col_name == 'wc_upsell_ids' ) {
							$col = trim($col);
							$wc_upsell_ids = array();
							if(strpos($col, '||')!==false && !empty($col) ) $wc_upsell_ids = explode('||', $col); 
							elseif ( !empty($col) ) $wc_upsell_ids = explode(',',$col);
							
							if($wc_upsell_ids)
							$wc_upsell_ids = array_map('trim',$wc_upsell_ids);
						} elseif ( $col_name == 'wc_crosssell_ids' ) {
							$col = trim($col);
							$wc_crosssell_ids = array();
							if(strpos($col, '||')!==false && !empty($col) ) $wc_crosssell_ids = explode('||', $col); 
							elseif( !empty($col) ) $wc_crosssell_ids = explode(',',$col);
							if($wc_crosssell_ids)
							$wc_crosssell_ids = array_map('trim',$wc_crosssell_ids);
						} elseif ( $col_name == 'wc_attr_no_tax' ) {
							$_row[$i] = trim($col);
						} elseif ( $col_name == 'wc_status_item' ) {
							$wc_status_item = trim($col);
						} elseif ( $col_name == 'wc_tax' ) {
							$col = trim($col);
							$tax = (int)$col;
							//
						} elseif ( $col_name == 'multi_cat' ) {
							$multi_cat = $col;
							//
						} elseif ( $col_name == 'wc_brands' ) {
							$multi_cat_brands = $col;
							//
						} elseif ( $col_name == 'wc_attachment' ) {
							$col = trim($col);
							if(!empty($col)) $attachments[] = $col;
						}elseif ( $col_name == 'wc_parent_sku' ) {
							$parent_sku = trim($col);
						}elseif ( $col_name == 'wc_default_attr' ) {
							$wc_default_attr = trim($col);
							$wc_default_attr = explode ( '|', $wc_default_attr );
							$wc_default_attr = array_map ( 'trim', $wc_default_attr );
						} elseif ( $col_name == 'wc_thumbnail' ) {
							$col = trim($col);
							$thumbnail = $col;
							//
							} elseif ( $col_name == 'attribs' ) {
							$taxo_attribs = $col;
							//
						} else {
							$_break = $break = false;
							if ( is_array( $custom_field_defs ) && count( $custom_field_defs ) > 0 ) {
								foreach( $custom_field_defs as $custom_field_def ) {
									if ( $col_name == $custom_field_def['id'] ) {
										$custom_values[$col_name] = $col;
										$_break = true;
										break;
									}
								}
							}
							if ( ! $break && is_array( $taxonomies ) && count( $taxonomies ) > 0 ) {
								foreach( $taxonomies as $taxmy ) {
									if ( $col_name == 'wc_tax_' . $taxmy ) {
										if(!empty($col))
										$taxo_values[$taxmy] = $col;
										$break = true;
										break;
									}
								}
							}
						}
						
					}
					$errorController = new ErrorSupervisor($sku);
					if(!empty($is_variable)) { $is_visible_attr = 1; } else $is_visible_attr = 0;
					if(!empty($parent_sku)) { $parent_post = $this->wp_exist_post_by_sku($parent_sku, true); }
					
					if(empty($stock) && !$this->NO_EDIT_AUTO_STOCK ) { if(!$wc_stock_on && (isset($stock) && $stock !== '0' || !isset($stock)) ) $stock = 10; }
						
						
						if(isset($_row) && is_array($_row)) {
							$_product_attributes = array();
							$counts = 0;
							foreach($_row as $key=>$val) {
								if(empty($val)) continue;
								if(!$_SESSION['no_utf8']) {
									$_product_attributes = $_product_attributes+
									array(
									mb_strtolower(urlencode(mb_strtolower($this->cp1251_to_utf8 ($titles[$key]) , 'utf-8'))) =>
										array(
										  "name"=> $this->cp1251_to_utf8 ($titles[$key]),
										  "value"=>	 $val,
										  "position"=>	 $counts,
										  "is_visible"=> "1",
										  "is_variation"=> 0,
										  "is_taxonomy"=> "0"
										)
									);
								} else { $_product_attributes = $_product_attributes+
									array(
									mb_strtolower(urlencode(mb_strtolower($titles[$key] , 'utf-8'))) =>
										array(
										  "name"=> $titles[$key],
										  "value"=>	 $val,
										  "position"=>	 $counts,
										  "is_visible"=> "1",
										  "is_variation"=> 0,
										  "is_taxonomy"=> "0"
										)
									);
								}
								$counts++;
							}
						}
					unset($post);
					if($all_is_comment) { $comment_open = 'open'; } else {$comment_open = 'close';}
					if(isset($parent_post) && $parent_post > 0) {
						
						$_SESSION['variable_menu_order'][ $parent_post ] [] = $parent_post;
						$variable_menu_order = $_SESSION['variable_menu_order'];
						$variation_post_title = sprintf( __( 'Variation #%s of %s', 'woocommerce' ), absint( sizeof($variable_menu_order[ $parent_post] ) ), esc_html( get_the_title( $parent_post ) ) );
						$post = array(
							'comment_status'=> 'close',
							'post_type'		=> 'product_variation',
							'post_parent' 	=> $parent_post,
							'post_author' 	=> get_current_user_id(),
							'post_title' 	=> $variation_post_title,
							'post_content' 	=> '',
							'menu_order' 	=> (isset($custom_values['menu_order']) && $custom_values['menu_order'] > 0) ? $custom_values['menu_order'] : sizeof($variable_menu_order[ $parent_post])
						);

						$post ['post_status'] = 'publish';
						$upost = '';
						if(!( isset($product_id) && $product_id ) )
							if(empty($sku) || $sku == $parent_sku) $sku = $parent_sku . '_var-'. absint( sizeof($variable_menu_order[ $parent_post] ) );
						
						if($is_selected_item == 'sku' && !empty($sku) && ($upost = $this->wp_exist_post_by_sku($sku)) ) {
							
						} else { $upost = false; }
						if( isset($product_id) && $product_id ) {
							$upost = $product_id;
						}
						if($upost){
						//var_dump($upost);
							//ID for post we want update
							$idpost = $upost;
							//$wpdb->update( $wpdb->posts, array( 'post_status' => $post ['post_status'], 'post_title' => $variation_post_title, 'menu_order' => sizeof($variable_menu_order[ $parent_post])  ), array( 'ID' => $upost ) );
							$post['ID'] = $idpost; 

							$post_id = wp_update_post( $post );
							$count--; //For update count
							if(isset($sku)) {
								update_post_meta( $post_id, '_sku', $sku );
							}
							if(isset($price) || isset($sale_price)) {
								if ( $sale_price != '') {
									$child_price = $child_sale_price = $sale_price;
									update_post_meta( $post_id, '_price', $sale_price );
									update_post_meta( $post_id, '_sale_price', $sale_price );
								} else {
									$child_price = $price;
									
									update_post_meta( $post_id, '_price', $price );
									update_post_meta( $post_id, '_sale_price', '' );
									
								}
								$child_regular_price = $price;
								update_post_meta( $post_id, '_regular_price', $price );	
							}
						
							if(isset($stock)) {
								update_post_meta( $post_id, '_stock', $stock );
								if($stock <= 0) { update_post_meta( $post_id, '_stock_status', 'outofstock' ); }
								elseif($stock > 0) { update_post_meta( $post_id, '_stock_status', 'instock' );	$_SESSION['is_stock'][$parent_post] = isset($_SESSION['is_stock'][$parent_post]) ? $_SESSION['is_stock'][$parent_post] + $stock : $stock;	}		
							} //else { update_post_meta( $post_id, '_stock_status', 'instock' ); }

							if(is_array($wc_upsell_ids)) $upsell[$post_id] = $wc_upsell_ids; 
							if(is_array($wc_crosssell_ids)) $crosssell[$post_id] = $wc_crosssell_ids; 
							
							if($wc_stock_on) { update_post_meta($post_id, '_manage_stock' ,'yes'); } else { update_post_meta($post_id, '_manage_stock', ''); }
							//if(is_array($_product_attributes)) { update_post_meta($post_id, '_product_attributes' ,$_product_attributes); } //else update_post_meta($post_id, '_product_attributes' ,array());

						} else {
							if( empty($slug) ) {
								if(isset($variation_post_title))
									$post['post_name']	= sanitize_title( $variation_post_title );
							}
							$post_id = wp_insert_post( $post );
							if(isset($stock)) {
								update_post_meta( $post_id, '_stock', $stock );
								if($stock <= 0) { update_post_meta( $post_id, '_stock_status', 'outofstock' ); }
								elseif($stock > 0) { update_post_meta( $post_id, '_stock_status', 'instock' ); $_SESSION['is_stock'][$parent_post] = isset($_SESSION['is_stock'][$parent_post]) ? $_SESSION['is_stock'][$parent_post] + $stock : $stock; }				
							} else { update_post_meta( $post_id, '_stock_status', 'instock' ); }
						}
				
				if(isset($_downloadable)) {update_post_meta( $post_id, '_downloadable', $_downloadable );}

				if(isset($_virtual)) {update_post_meta( $post_id, '_virtual', $_virtual );} 
				
				$attributes = (array) maybe_unserialize( get_post_meta( $parent_post , '_product_attributes', true ) );
				$_tax_attr = array();
				foreach ( $attributes as $attribute ) {
					if ( isset( $attribute['is_variation']) &&  $attribute['is_variation'] ) {
						$_tax_attr[] = $attribute['name'];
					}
				}
				foreach ( $attributes as $attribute ) {
					if ( isset( $attribute['is_variation']) &&  $attribute['is_variation'] ) {
						// Don't use woocommerce_clean as it destroys sanitized characters
						if(!isset($wc_default_attr)) $wc_default_attr = array();
						if ( taxonomy_exists( $attribute['name'] ) ) {
							
							$args = $_wc_default_attr = array();
							
							$_wc_default_attr = explode('|', $taxo_attribs);
							$_wc_default_attr = array_map('trim', $_wc_default_attr);
							
							if(in_array($attribute['name'], $_tax_attr)) {
								$ind = array_search($attribute['name'], $_tax_attr);
								if($ind !== false && isset($_wc_default_attr[$ind])) {
									// $args['slug'] = sanitize_title($_wc_default_attr[$ind]);
									$_wc_default_attr = array ( $_wc_default_attr[$ind] ) ;
								}
							}
							$terms = get_terms( $attribute['name'], $args );
							
							
							//$_SESSION['varialble_attr']
							if(sizeof($terms) > 0) {
								$_terms =  explode('|', $attribute['value']);
								$_terms = array_map('trim', $_terms);
								if( version_compare( WOOCOMMERCE_VERSION, '2.4', '<' ))
								foreach($_terms as $key => $value) {
									foreach($terms as $t_) {
										$t_->name = trim($t_->name);
										if( $t_->name ==  $value) 
										$att_name[$key] = $t_->slug;
									}
								}
								else $att_name = $_terms;
								
								foreach ( $_terms as $v_k => $term ) {
									if( !version_compare( WOOCOMMERCE_VERSION, '2.4', '<' ))
										$_termss = get_term_by( 'slug', trim($term), $attribute['name'] )->name; else $_termss = $term;
									$_termss = sanitize_title($_termss);
									$_wc_default_attr = array_map('sanitize_title', $_wc_default_attr);
									$wc_default_attr = array_map('sanitize_title', $wc_default_attr);
									if ( !in_array( $_termss,  $_wc_default_attr )  ) {
										if(  in_array($_termss, $wc_default_attr ) ){ $_value = sanitize_title( trim( stripslashes( $term ) ) );  $_SESSION['default_attributes'][ sanitize_title( $attribute['name'] ) ] = $_value; }
										continue;
									}
									if(!empty( $att_name[$v_k] )) $value = $att_name[$v_k];
									else
									$value = sanitize_title( trim( stripslashes( $term ) ) );
									update_post_meta( $post_id, 'attribute_' . sanitize_title( $attribute['name'] ), $value );
									$_term[$attribute['name']] = 1;
									if(  in_array($_termss, $wc_default_attr ) )
									$_SESSION['default_attributes'][ sanitize_title( $attribute['name'] ) ] = $value;
								}
								
								if( !isset($_term[$attribute['name']]) ) {
									update_post_meta( $post_id, 'attribute_' . sanitize_title( $attribute['name'] ), '' );
								}
								
							} else {
								$terms =  explode('|', $attribute['value']);

								foreach ( $terms as $term ) {
									$term = sanitize_title($term);
									$_terme = $term;
									$_wc_default_attr = array_map('sanitize_title', $_wc_default_attr);
									$wc_default_attr = array_map('sanitize_title', $wc_default_attr);
									
									if ( !in_array( $term,  $_wc_default_attr )  ) {
										if(  in_array($term, $wc_default_attr ) ){ $_value = sanitize_title( trim( stripslashes( $_terme ) ) );  $_SESSION['default_attributes'][ sanitize_title( $attribute['name'] ) ] = $_value; }
										continue;
									}
									$value = sanitize_title( trim( stripslashes( $_terme ) ) );
									update_post_meta( $post_id, 'attribute_' . sanitize_title( $attribute['name'] ), $value );

									$_term[$attribute['name']] = 1;
									if(  in_array($term, $wc_default_attr ) )
									$_SESSION['default_attributes'][ sanitize_title( $attribute['name'] ) ] = $value;
								}
								
								if( !isset($_term[$attribute['name']]) ) {
									update_post_meta( $post_id, 'attribute_' . sanitize_title( $attribute['name'] ), '' );
								}
							}
							
						} else {
							$__wc_default_attr = $_wc_default_attr = array();
							$_wc_default_attr = explode('|', $attribute['value']);
							$__wc_default_attr = explode('|', $taxo_attribs);
							
							$_wc_default_attr = array_map('trim', $_wc_default_attr);
							$__wc_default_attr = array_map('trim', $__wc_default_attr);
							foreach ( $__wc_default_attr as $term ) {
								$term = strtolower( sanitize_title($term) );
								$_wc_default_attr = array_map('sanitize_title', $_wc_default_attr);
								$_wc_default_attr = array_map('strtolower', $_wc_default_attr);
								if ( !in_array( $term,  $_wc_default_attr )  ) {
									if(  in_array($term, $wc_default_attr ) ) { $_value = sanitize_title( trim( stripslashes( $term ) ) ); $_SESSION['default_attributes'][ sanitize_title( $attribute['name'] ) ] = $_value; }
									continue;
								}
								$value = sanitize_title( trim( stripslashes( $term ) ) );
								update_post_meta( $post_id, 'attribute_' . sanitize_title( $attribute['name'] ), $value );
								$_term[$attribute['name']] = 1;
								if(  in_array($term, $wc_default_attr ) )
								$_SESSION['default_attributes'][ sanitize_title( $attribute['name'] ) ] = $value;
							}
							if( !isset($_term[$attribute['name']]) ) {
								update_post_meta( $post_id, 'attribute_' . sanitize_title( $attribute['name'] ), '' );
							}
						}
					}
				}

				if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
					if (isset($_file_path)) {
						$file_paths = explode( "|", $_file_path );
						if(sizeof($file_paths) > 1) $_file_path = $file_paths[0];
						update_post_meta( $post_id, '_file_path', esc_attr($_file_path) );
					} 
				} else {
					if( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
						if ( isset( $_file_path ) ) {
							$_file_paths = array();
							if ( $_file_path ) {
								$file_paths = explode( "|", $_file_path );
								foreach ( $file_paths as $file_path ) {
									$file_path = trim( $file_path );
									$_file_paths[ md5( $file_path ) ] = $file_path;
								}
							}
							update_post_meta( $post_id, '_file_paths', $_file_paths );
						}						
					} else {
						if ( isset( $_file_path ) ) {
							$_file_paths = array();
							if ( $_file_path ) {
								$file_paths = explode( "|", $_file_path );
								foreach ( $file_paths as $file_path ) {
									$file_path = trim( $file_path );
									$_file_paths[ md5( $file_path ) ] = array('name' => basename( $file_path ), 'file' => $file_path);
								}
							}
							update_post_meta( $post_id, '_downloadable_files', $_file_paths );
						}						
					}
				}
						
						if(isset($sku)) {
							update_post_meta( $post_id, '_sku', $sku );
						}
						if(isset($price) || isset($sale_price)) {
							if ( $sale_price != '') {
								update_post_meta( $post_id, '_price', $sale_price );
								update_post_meta( $post_id, '_sale_price', $sale_price );
							} else {
								update_post_meta( $post_id, '_price', $price );
								update_post_meta( $post_id, '_sale_price', '' );
							}
						}
						if(isset($weight)) {
							update_post_meta( $post_id, '_weight', $weight );
						}
						

						
						if(isset($price)) { update_post_meta( $post_id, '_regular_price', $price ); }

						if($wc_stock_on) {
						update_post_meta($post_id, '_manage_stock' ,'yes'); } else { update_post_meta($post_id, '_manage_stock' ,'no'); }

						if($and_variable) {
						
							if( isset($_SESSION['IDpost_parent']) ) {
								if( is_array($_SESSION['variable_menu_order'] ) ){
									foreach( $_SESSION['variable_menu_order'] as $_id_p => $ar) {
										$post_parent = $_id_p;
										break;
									}
								}
								if(isset($_SESSION['default_attributes'])) {update_post_meta( $post_parent, '_default_attributes', $_SESSION['default_attributes'] );} 
								
								unset($_SESSION['IDpost_parent'] ,$_SESSION['variable_menu_order'] , $_SESSION['default_attributes']);
							}
							
							$children = get_posts( array(
								'post_parent' 	=> $post_parent,
								'posts_per_page'=> -1,
								'post_type' 	=> 'product_variation',
								'fields' 		=> 'ids',
								'post_status'	=> 'publish'
							) );

							$_max_sale_price_variation_id = $_min_sale_price_variation_id = $_min_price_variation_id = $_max_price_variation_id = $_min_regular_price_variation_id = $lowest_price = $lowest_regular_price = $lowest_sale_price = $highest_price = $highest_regular_price = $highest_sale_price = '';

								foreach ( $children as $child ) {

									$child_price 			= get_post_meta( $child, '_price', true );
									$child_regular_price 	= get_post_meta( $child, '_regular_price', true );
									$child_sale_price 		= get_post_meta( $child, '_sale_price', true );

									if ( $child_price === '' && $child_regular_price === '' )
										continue;

									// Regular prices
								if ( ! is_numeric( $lowest_regular_price ) || $child_regular_price < $lowest_regular_price ) {
										$lowest_regular_price = $child_regular_price;
									$_min_regular_price_variation_id = $child;
									$_min_price_variation_id = $child;
								}
									

								if ( ! is_numeric( $highest_regular_price ) || $child_regular_price > $highest_regular_price ) {
										$highest_regular_price = $child_regular_price;
									$_max_price_variation_id = $child;
								}
									

									// Sale prices
									if ( $child_price == $child_sale_price ) {
									if ( $child_sale_price !== '' && ( ! is_numeric( $lowest_sale_price ) || $child_sale_price < $lowest_sale_price ) ) {
											$lowest_sale_price = $child_sale_price;
										$_min_price_variation_id = $child;
										$_min_sale_price_variation_id = $child;
									}

									if ( $child_sale_price !== '' && ( ! is_numeric( $highest_sale_price ) || $child_sale_price > $highest_sale_price ) ) {
											$highest_sale_price = $child_sale_price;
										$_max_price_variation_id = $child;
										$_max_sale_price_variation_id = $child;
									}
										
									}
								}

								$lowest_price 	= $lowest_sale_price === '' || $lowest_regular_price < $lowest_sale_price ? $lowest_regular_price : $lowest_sale_price;
								$highest_price 	= $highest_sale_price === '' || $highest_regular_price > $highest_sale_price ? $highest_regular_price : $highest_sale_price;
								
							if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) && $children ) {
								if($_min_regular_price_variation_id) 
									update_post_meta( $post_parent, '_min_regular_price_variation_id', $_min_regular_price_variation_id );
								if($_max_price_variation_id) 
									update_post_meta( $post_parent, '_max_price_variation_id', $_max_price_variation_id );
								if($_min_price_variation_id) 
									update_post_meta( $post_parent, '_min_price_variation_id', $_min_price_variation_id );
								
								if($_min_sale_price_variation_id) 
									update_post_meta( $post_parent, '_min_sale_price_variation_id', $_min_sale_price_variation_id );
								if($_max_sale_price_variation_id) 
									update_post_meta( $post_parent, '_max_sale_price_variation_id', $_max_sale_price_variation_id );
							}
							
							if($lowest_sale_price)
								update_post_meta( $post_parent, '_min_variation_sale_price', $lowest_sale_price );
							if($highest_sale_price)
								update_post_meta( $post_parent, '_max_variation_sale_price', $highest_sale_price );
							
							if($lowest_price > 0)
							update_post_meta( $post_parent, '_price', $lowest_price );
						
							if($lowest_price != $highest_price) {
								update_post_meta( $post_parent, '_min_variation_price', $lowest_price );
								update_post_meta( $post_parent, '_max_variation_price', $highest_price );
							}
							if($lowest_regular_price != $highest_regular_price) {
								update_post_meta( $post_parent, '_min_variation_regular_price', $lowest_regular_price );
								update_post_meta( $post_parent, '_max_variation_regular_price', $highest_regular_price );
							}
							wp_set_object_terms($post_parent, 'variable', 'product_type');
							if( isset( $_SESSION['is_stock'][$parent_post] ) ) {
								/* if($wc_stock_on)
									update_post_meta($post_parent, '_manage_stock' ,'yes');
								else */
								update_post_meta($post_parent, '_manage_stock' ,'no');
								update_post_meta( $post_parent, '_stock_status', 'instock' );
								//update_post_meta( $post_parent, '_stock', $_SESSION['is_stock'][$parent_post] );
								unset( $_SESSION['is_stock'][$parent_post] );
							}
							if( ! version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
								if( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
									if( !is_object($woocommerce) ) global $woocommerce;
									if($post_parent) $woocommerce->clear_product_transients($post_parent);
								} else {
									if($post_parent) wc_delete_product_transients($post_parent);
								}
							}
						}
						$_attachments = explode(",", $thumbnail);
						if(!empty($_attachments[1])) {
							if(!is_array($attachments)) $attachments = array();
							unset($thumbnail);
							$thumbnail = array_shift($_attachments);
						}

						if ( strlen( $thumbnail ) > 0 ) {
							$thumbnail = explode('?', $thumbnail);
							$thumbnail = $thumbnail[0];
							$thumbnail = preg_replace('/( $|\s$)/', '', $thumbnail);
							$filename = urldecode( basename($thumbnail) );
							$post_title =   $post['post_title'];
							$__upload_dir = wp_upload_dir();
							$is_url_thumb = false;
							if ( $this->isValidUrl( $thumbnail ) ) {
								$image_data_ = @file_get_contents($thumbnail);
								$is_url_thumb = true;
								$already_there= $wpdb->get_row(
								$wpdb->prepare(
									"SELECT max(post_id) as maxid , COUNT(*) as amount  FROM $wpdb->postmeta where meta_key='_wp_attached_file_md5' and meta_value='%s'",
									md5($image_data_) ));
							} else {
								$basedir =  $thumbnail;
								$already_there= $wpdb->get_row(
								$wpdb->prepare(
									"SELECT max(post_id) as maxid , COUNT(*) as amount  FROM $wpdb->postmeta where meta_key='_wp_attached_file' and meta_value='%s'",
									$basedir));
							}
							$ri = true;
							if ( $already_there->amount > 0  ) {
								require_once(ABSPATH . "wp-admin" . '/includes/image.php');
								update_post_meta( $post_id, '_thumbnail_id', $already_there->maxid );
								
								$file = get_post_meta( $already_there->maxid, '_wp_attached_file', true );
								$upload_dir = $__upload_dir;
								$p = $upload_dir["baseurl"] . '/' . $file;
								if ( $this->isValidUrl( $p ) ) {
									$image_data_ = @file_get_contents($p);
									$is_url_thumb = true;
									$ri = !in_array( md5($image_data_), $this->image);
									if( $ri )
									$this->image[] = md5($image_data_);
								}
							} else {
								$return_process = true;
								if( $_SESSION['fetch_attachments'] ) {
									if(empty($_SESSION['time2'])) $_SESSION['time2'] = time();
									//$path = wp_upload_dir($upload['file']);

									
									if(empty($_SESSION['time2']))$_SESSION['time2'] = time();
										$date = date("Y/m", $_SESSION['time2']);
									
								
									if($this->iteration && $uploaded%75 == 0 && $uploaded > 0) {
										$_SESSION['time2'] = $_SESSION['time2'] - 24*3600*31;
										$this->iteration = 0;
									} 
									// fetch the remote url and write it to the placeholder file
									if ( $this->isValidUrl( $thumbnail ) ) {
										$image_data = @file_get_contents($thumbnail);
										$ri = !in_array( md5($image_data), $this->image);
										if( $ri )
										$this->image[] = md5($image_data); 
									}
									//$headers = wp_get_http( $thumbnail, $upload['file'] );

									// request failed
									if( $ri ) {
									if ( $image_data !== false ) {
										$upload_dir = $__upload_dir;
										if (isset($upload_dir['path']) && wp_mkdir_p($upload_dir['path']))
											$_url = $upload_dir['path'] . '/' . $filename;
										else
											$_url = $upload_dir['basedir'] . '/' . $filename;
										$_url = str_replace(date("Y/m", time() ), $date, $_url);
										if(file_exists($_url)) {
											$image_data2 = @file_get_contents($_url);
											while( file_exists($_url) && $image_data2 !== false ) {
												if( md5($image_data) !== md5($image_data2) ) {
													if( preg_match('/r_(\d)\.([^\.]*)$/i', $_url, $m) ) {
														$_url = preg_replace('/r_(\d)\.([^\.]*)$/i', "r_" . ( ( $m[1] ) + 1 ) . ".$2", $_url, 1);
													} else {
														$_url = preg_replace('/\.([^\.]*)$/i', 'r_1.$1', $_url, 1);
													}
													$image_data2 = @file_get_contents($_url);
												} else {
													break;
												}
											}
											if (@file_put_contents($_url, $image_data)) {
												
											} elseif ( is_dir(str_replace($filename, '', $_url)) || @mkdir(str_replace($filename, '', $_url), '0755', true) ) {
												if (@file_put_contents($_url, $image_data)) {
													
												} else $return_process = false;
											} else $return_process = false;
										} else {
										if (@file_put_contents($_url, $image_data)) {
											
											} elseif( is_dir(str_replace($filename, '', $_url)) || @mkdir(str_replace($filename, '', $_url), '0755', true)) {
											if (@file_put_contents($_url, $image_data)) {
												
											} else $return_process = false;
										} else $return_process = false;
										}

									} else $return_process = false;
								}
								}
								if( $ri ) {
								if($return_process) {
									if(!empty($_url)) {
										$dest = $_url;
									} else {
										$base =  $thumbnail;
										$path = $__upload_dir;
										$path = $path['basedir'];
										$dest = $path . '/' . $base;
									}

									//copy( $thumbnail, $dest );
									$the_image_run = true;

									if($the_image_run && file_exists($dest)) {
										$wp_filetype = wp_check_filetype( basename( $dest ), null );
										if(!is_array($attachments))$attachments = array();
										$attachment = array(
											'post_mime_type' => $wp_filetype['type'],
											'post_title' => $post_title,
											'post_status' => 'inherit',
										);
										$attach_id = wp_insert_attachment( $attachment, $dest, $post_id );
										
										// you must first include the image.php file for the function wp_generate_attachment_metadata() to work
										require_once(ABSPATH . "wp-admin" . '/includes/image.php');
										$attach_data = wp_generate_attachment_metadata( $attach_id, $dest );
										wp_update_attachment_metadata( $attach_id,  $attach_data );
										if($is_url_thumb) update_post_meta( $attach_id, '_wp_attached_file_md5', md5($image_data_) );
										update_post_meta( $post_id, '_thumbnail_id', $attach_id );
										set_post_thumbnail( $post_id, $attach_id );
									}
								} else { delete_post_thumbnail( $post_id ); }
							}
						}
						}
						if(is_array($custom_values)) {
							foreach( $custom_values as $id => $custom_value ) {
								if($id == 'menu_order') continue;
								if(in_array($id, array("_opt_price", '_opt_count')) ) {
									$custom_value = explode("||",  str_replace(array(',', ' ', ' '), array('.', '', ''), $custom_value) );
									$custom_value = array_map("trim", $custom_value);
									if(is_array($custom_value) && $custom_value[0] === '')
										$custom_value = null;
								} elseif(in_array($id, array("_sale_price_dates_to", '_sale_price_dates_from')) ) {
									$custom_value = strtotime ($custom_value);
								}
								if(strpos($id, '_price') !== false ) {
									$custom_value = str_replace(array(',', ' ', ' '), array('.', '', ''), $custom_value);
									update_post_meta( $post_id, $id, $custom_value );
								} 
								else {
									if(strpos($custom_value, '[') !== false || strpos($custom_value, '{') !== false )
										update_post_meta( $post_id, $id, $this->parse_arr ($custom_value) );
									else 
										update_post_meta( $post_id, $id, $custom_value );
								}
							}
						}
						if(!empty($_POST['default_language']))
						if(!empty($sku_wc_refwpml) && !empty($_POST['current_language'])) {
							$id_general = $this->wp_exist_post_by_sku($sku_wc_refwpml);
							if($id_general) {
								$trid = $wpdb->get_var($wpdb->prepare("SELECT tr.trid FROM {$wpdb->prefix}icl_translations as tr WHERE element_id = '%s' ORDER BY element_type DESC", $id_general));
								$translation_id = $wpdb->get_var($wpdb->prepare("SELECT tr.translation_id FROM {$wpdb->prefix}icl_translations as tr WHERE element_id = '%s' ORDER BY element_type DESC", $post_id));
							}
							
							
							if(!empty( $translation_id ) && !empty( $trid ) ) {
								// $translation_id = $translation[0]->translation_id;
								// $trid = $translation[0]->trid;
								// var_dump($translation_id, $trid);
								update_option('saphali_current_language_wpml', $_POST['current_language']);
								
								if($translation_id && $translation_id > 0) {
									$q = "UPDATE {$wpdb->prefix}icl_translations SET language_code = '{$_POST['current_language']}s', trid = $trid, source_language_code = '{$_POST['default_language']}s' WHERE {$wpdb->prefix}icl_translations.translation_id = $translation_id; ";
									$wpdb->query($q);
									$q = "UPDATE {$wpdb->prefix}icl_translations SET language_code = '{$_POST['current_language']}', trid = $trid, source_language_code = '{$_POST['default_language']}' WHERE {$wpdb->prefix}icl_translations.translation_id = $translation_id; ";
									$wpdb->query($q);
								}
							}
						} else {
							$translation = $wpdb->get_results($wpdb->prepare("SELECT tr.translation_id, tr.trid FROM {$wpdb->prefix}icl_translations as tr WHERE element_id = '%s' ORDER BY element_type DESC", $post_id), OBJECT);
							if(!empty( $translation ) ) {
								$translation_id = $translation[0]->translation_id;
								$trid = $translation[0]->trid;
								if($translation_id && $translation_id > 0) {
									$q = "UPDATE {$wpdb->prefix}icl_translations SET language_code = '{$_POST['default_language']}', trid = $trid, source_language_code = NULL WHERE {$wpdb->prefix}icl_translations.translation_id = $translation_id; ";
									$wpdb->query($q);
								}
							}
						}
						if(!empty($_POST['default_language_pl'])) {
							$id_general = false;
							if( !empty($sku_wc_refwpml) ) {
								$id_general = $this->wp_exist_post_by_sku($sku_wc_refwpml);
							} 
							if(!empty($_POST['current_language_pl'])) {
								pll_set_post_language( $post_id, $_POST['current_language_pl'] );
								if($id_general) {
									$translations = pll_get_post_translations( $id_general );
								} else {
									$translations = pll_get_post_translations( $post_id );
								}
								PLL()->model->post->save_translations( $post_id, $translations );
							} else {
								$lng = pll_get_post_language($post_id);
								if($lng === false)
									pll_set_post_language( $post_id, $_POST['default_language_pl'] );
								
								if($id_general) {
									$translations = pll_get_post_translations( $id_general );
								} else {
									$translations = pll_get_post_translations( $post_id );
								}
								PLL()->model->post->save_translations( $post_id, $translations );
							}
						}
					} else {

						$post = array(
							'comment_status'=> $comment_open,
							'post_type'		=> $post_type,
						);
						if(isset($content)) {
							$post['post_content'] = $content;
						}
						if(isset($slug) && !empty($slug) ) {
							$post['post_name']	= $slug;
						}
						if(isset($custom_values['menu_order']) && $custom_values['menu_order'] > 0) {
							$post['menu_order'] = $custom_values['menu_order'];
						}
						if(isset($name)) {
							$post['post_title']	= $name;
						}
						if(isset($excerpt)) {
							$post['post_excerpt'] = $excerpt;
						}
						if( empty($wc_status_item) )
						$post ['post_status'] = $wc_status ;
						else $post ['post_status'] = $wc_status_item ;
						//
						$upost = '';
						if($is_selected_item == 'sku' && ($upost = $this->wp_exist_post_by_sku($sku)) ) {
							
						} elseif($is_selected_item == 'title' &&  ($upost = $this->wp_exist_post_by_title($name))) {

						} else { $upost = false; }
						if( isset($product_id) && $product_id ) {
							$upost = $product_id;
						}
						if($upost){
							if( !$taxo_attribs && 0 ) {
								$r_product_attributes = $cur_taxo_values = array();
								$r_product_attributes = get_post_meta($upost, '_product_attributes', true);
								if($r_product_attributes)
								foreach($r_product_attributes as $v) {
									$name_terms = array();
									foreach( wp_get_object_terms($upost, $v['name']) as $trm) {
										$name_terms[] = $trm->name;
									}
									$cur_taxo_values[ $v['name'] ] = implode(',', $name_terms);
								}
								$taxo_values += $cur_taxo_values;	
							}
							if($is_visible_attr) {
							
								$children = get_posts( array(
									'post_parent' 	=> $upost,
									'posts_per_page'=> -1,
									'post_type' 	=> 'product_variation',
									'fields' 		=> 'ids'
								) );
								
								if($children) {
									foreach ( $children as $child ) {
									//wp_delete_post( $child, true );
									$query = "update {$wpdb->posts} AS p
										set p.post_status= 'trash', p.post_modified_gmt= '".gmdate("Y-m-d H:i:s")."'
										WHERE
										p.ID='{$child}'"; 
										$queryresult = $wpdb->query($query);
									}
								}
								
							}

							//ID for post we want update
							$idpost = $upost;
							
							$post['ID'] = $idpost; 

						if(isset($content)) {
							$content = str_replace("'", "\\'", $content);
							$set_content = ", post_content = '$content'";
						} else $set_content = "";
						if(isset($slug) && !empty($slug) ) {
							$slug = str_replace("'", "\\'", $slug);
							$set_slug = ", post_name = '$slug'";
						}  else {
							$slug = str_replace("'", "\\'", get_post( $upost )->post_name );
							$set_slug = "";
							if(empty($slug)) {
								$slug = str_replace("'", "\\'", sanitize_title( get_post( $upost )->post_title ) );
								$set_slug = ", post_name = '$slug'";
							}
						}
						if(isset($name)) {
							$name = str_replace("'", "\\'", $name);
							$set_name = ", post_title = '$name'";
						}  else $set_name = "";
						if(isset($custom_values['menu_order']) && $custom_values['menu_order'] > 0) {
							$set_name .= ", menu_order = '" . $custom_values['menu_order'] . "'";
						}
						if(isset($excerpt)) {
							$excerpt = str_replace("'", "\\'", $excerpt);
							$set_excerpt = ", post_excerpt = '$excerpt'";
						}  else $set_excerpt = "";
						if( empty($wc_status_item) )
						$set_status = ", post_status = '$wc_status'";
						else $set_status = ", post_status = '$wc_status_item'";;
						
							$q = "update $wpdb->posts 
									set comment_status = '$comment_open' $set_content $set_slug $set_name $set_excerpt $set_status
									WHERE
									ID = '$upost'
									";
							$queryresult2 = $wpdb->query($q);
							$post_id = $upost;
							$count--; //For update count
							if(isset($sku)) {
								update_post_meta( $post_id, '_sku', $sku );
							}
							if(isset($price) || isset($sale_price)) {
								if ( $sale_price != '') {
									update_post_meta( $post_id, '_price', $sale_price );
									update_post_meta( $post_id, '_sale_price', $sale_price );
								} else {
									update_post_meta( $post_id, '_price', $price );
									update_post_meta( $post_id, '_sale_price', '' );
								}
								update_post_meta( $post_id, '_regular_price', $price );	
							}
						
							if(isset($stock)) {
								update_post_meta( $post_id, '_stock', $stock );
								if($stock <= 0) { 
									update_post_meta( $post_id, '_stock_status', 'outofstock' ); 
									if( !version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
										wp_set_object_terms($post_id, 'outofstock', 'product_visibility');
									}
								}
								elseif($stock > 0) {
									update_post_meta( $post_id, '_stock_status', 'instock' );
									if( !version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
										wp_remove_object_terms($post_id, 'outofstock', 'product_visibility');
									}
								}
							} //else { update_post_meta( $post_id, '_stock_status', 'instock' ); }

							if(isset($wc_upsell_ids) && is_array($wc_upsell_ids)) $upsell[$post_id] = $wc_upsell_ids; 
							if(isset($wc_crosssell_ids) && is_array($wc_crosssell_ids)) $crosssell[$post_id] = $wc_crosssell_ids; 
							
							if($wc_stock_on) { update_post_meta($post_id, '_manage_stock' ,'yes'); } else { update_post_meta($post_id, '_manage_stock' ,''); }
							if(isset($_product_attributes) && is_array($_product_attributes)) { update_post_meta($post_id, '_product_attributes' ,$_product_attributes); } //else update_post_meta($post_id, '_product_attributes' ,array());
							$count_['update']++;
							$count_['id'][] = $post['ID'];
						} else {
							if($no_import_new_product)
							die(json_encode(array('imported' => $count,'count' =>  $uploaded, 'updated' => $count_['update'], 'post_id' => 'no_import', 'success' => true, 'error' => false, 'variable' => 0 )));
							if( ! isset($slug) || empty($slug) ) {
								if(isset($name))
									$post['post_name']	= sanitize_title( $name );
							}
							$post_id = wp_insert_post( $post );
							
							if(isset($stock)) {
								update_post_meta( $post_id, '_stock', $stock );
								if($stock <= 0) { 
									update_post_meta( $post_id, '_stock_status', 'outofstock' ); 
									if( !version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
										wp_set_object_terms($post_id, 'outofstock', 'product_visibility');
									}
								}
								elseif($stock > 0) { 
									update_post_meta( $post_id, '_stock_status', 'instock' ); 
									if( !version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
										wp_remove_object_terms($post_id, 'outofstock', 'product_visibility');
									}
								}				
							} else { update_post_meta( $post_id, '_stock_status', 'instock' ); }
							
							if(isset($price) || isset($sale_price)) {
								if ( $sale_price != '') {
									update_post_meta( $post_id, '_price', $sale_price );
									update_post_meta( $post_id, '_sale_price', $sale_price );
								} else {
									update_post_meta( $post_id, '_price', $price );
									update_post_meta( $post_id, '_sale_price', '' );
									
							}
							}
							update_post_meta( $post_id, 'total_sales', 0 );
							if(isset($price)) { update_post_meta( $post_id, '_regular_price', $price ); }
							

							if($wc_stock_on) {
							update_post_meta($post_id, '_manage_stock' ,'yes'); } else { update_post_meta($post_id, '_manage_stock' ,''); }
							

							if(is_array($_product_attributes)) { update_post_meta($post_id, '_product_attributes' ,$_product_attributes); } // else update_post_meta($post_id, '_product_attributes' 
						}
						if ( isset($cat) && $cat != '' ) {
							if(!is_array($cat)) explode(',',$cat);
							$x[] =  wp_set_object_terms( $post_id, $cat, $taxonomy, false );
						}
				

				if( ! isset($product_type) ) $product_type = 'simple';

				wp_set_object_terms($post_id, $product_type, 'product_type');
				
				if(isset($_downloadable)) {update_post_meta( $post_id, '_downloadable', $_downloadable );}
				//wp_set_object_terms($post_id, $_downloadable, '_downloadable');
				if(isset($_virtual)) {update_post_meta( $post_id, '_virtual', $_virtual );} 
				
				//wp_set_object_terms($post_id, $_virtual, '_virtual');
				if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
					if (isset($_file_path)) {
						$file_paths = explode( "|", $_file_path );
						if(sizeof($file_paths) > 1) $_file_path = $file_paths[0];
						update_post_meta( $post_id, '_file_path', esc_attr($_file_path) );
					} 
				} else {
					if( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
						if ( isset( $_file_path ) ) {
							$_file_paths = array();
							if ( $_file_path ) {
								$file_paths = explode( "|", $_file_path );
								foreach ( $file_paths as $file_path ) {
									$file_path = trim( $file_path );
									$_file_paths[ md5( $file_path ) ] = $file_path;
								}
							}
							update_post_meta( $post_id, '_file_paths', $_file_paths );
						}						
					} else {
						if ( isset( $_file_path ) ) {
							$_file_paths = array();
							if ( $_file_path ) {
								$file_paths = explode( "|", $_file_path );
								foreach ( $file_paths as $file_path ) {
									$file_path = trim( $file_path );
									$_file_paths[ md5( $file_path ) ] = array('name' => basename( $file_path ), 'file' => $file_path);
								}
							}
							update_post_meta( $post_id, '_downloadable_files', $_file_paths );
						}						
					}
				}
						update_post_meta( $post_id, '_visibility', 'visible' );
						if(isset($sku)) {
							update_post_meta( $post_id, '_sku', $sku );
						}

						if(isset($weight)) {
							update_post_meta( $post_id, '_weight', $weight );
						}
						
						if(isset($aioseop_title))
						update_post_meta( $post_id, '_aioseop_title', stripslashes( $aioseop_title ) );
						if(isset($aioseop_description))
						update_post_meta( $post_id, '_aioseop_description', stripslashes( $aioseop_description ) );
						if(isset($aioseop_keywords))
						update_post_meta( $post_id, '_aioseop_keywords', stripslashes( $aioseop_keywords ) );
						

						if(isset($wc_upsell_ids) && is_array($wc_upsell_ids)) $upsell[$post_id] = $wc_upsell_ids;  //update_post_meta( $post_id, '_upsell_ids', $wc_upsell_ids );
						if(isset($wc_crosssell_ids) && is_array($wc_crosssell_ids)) $crosssell[$post_id] = $wc_crosssell_ids;  //update_post_meta( $post_id, '_upsell_ids', $wc_upsell_ids );
						



			//
			$field= $taxo_attribs;
			
			if((!empty($field)) && (explode(',',$field) !=FALSE))
			{
				$datas=explode(',',$field);
				$attrib=array();
				if(!isset($counts)) $counts = 0;
				for($i=0;$i<count($datas);++$i)
				{
					$datas[$i] = trim($datas[$i]);
					if(empty($datas[$i]) ) continue;
					
					$value=explode(':',$datas[$i]);
					$first_value = array_shift($value);

					$_is_visible_attr = 0;
					if (!empty($first_value))
					{
						$lang_where = '';
						if( strpos($first_value, "*") !== false && strpos($first_value, "*") === 0 ) { $_is_visible_attr = $is_visible_attr; $first_value = substr($first_value, 1); }
						$_first_value = strtolower(strtr($first_value, $this->gost));
						if( !empty($lang) ) {
							$lang_where_value = "%{$lang}" . $first_value . '%';
							$lang_where = sprintf("OR attribute_label like %s", $lang_where_value);
						}
					$taxonomy_exists = $wpdb->get_var($wpdb->prepare("SELECT attribute_name  FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_label = %s {$lang_where}", $first_value ));
						$nicename = sanitize_title(str_replace('pa_', '', $_first_value));
						if(strlen ($nicename) > 28) $nicename = substr( $nicename, 0, 28) ;
						$_nicename = 'pa_'.$nicename;
						if ( $taxonomy_exists )
						{
							if($taxonomy_exists != $nicename) $_nicename = sanitize_title( 'pa_'.$taxonomy_exists);
							
						} else
						{
							if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
								register_taxonomy( $_nicename, 'product', array( 'hierarchical' => false, 'label' => $first_value ) );
								 $wpdb->insert( $wpdb->prefix . "woocommerce_attribute_taxonomies", array( 'attribute_name' => $nicename,'attribute_label' => $first_value, 'attribute_type' => 'select' ), array( '%s', '%s', '%s' ) );
							} else {
								global $woocommerce;
								
								// Create the taxonomy
								$wpdb->insert( $wpdb->prefix . "woocommerce_attribute_taxonomies", array( 'attribute_name' => $nicename, 'attribute_type' => 'select', 'attribute_orderby' => 'menu_order', 'attribute_label' => $first_value ), array( '%s', '%s', '%s', '%s' ) );
								
								register_taxonomy( $_nicename,
								   'product',
									apply_filters( 'woocommerce_taxonomy_args_' . $_nicename, array(
										'hierarchical' => true,
										'show_ui' => false,
										'query_var' => true,
										'rewrite' => false,
									) )
								);
								
								if( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
									$woocommerce->clear_product_transients($post_id);
								} else {
									wc_delete_product_transients($post_id);
								}
								delete_transient( 'wc_attribute_taxonomies' );
							}
						}
						$_count = 0;
						foreach( $value as $tax ) {
							if(empty($tax)) continue;
							
							if($hierarchical_multicat ) {
								if(strpos($tax, '>') !== 0)
								$cat_taxs = explode( '>', $tax );
								else
								$cat_taxs = array( $tax );
								$parent = false;
								$_count_cat = 0;
								foreach ( $cat_taxs as $cat_tax)
								{
									$cat_tax = trim($cat_tax);
									if(empty($cat_tax)) continue;
									$_nicename = urldecode($_nicename);
									$new_cat = term_exists( strtolower(strtr($cat_tax, $this->gost)), $_nicename ); 
									if ( ( !  is_array( $new_cat ) ) || is_wp_error( $new_cat ) ) {
										$new_cat = term_exists( $cat_tax, $_nicename ); 
										if ( ( !  is_array( $new_cat ) ) || is_wp_error( $new_cat ) )
										$new_cat = wp_insert_term(	$cat_tax, $_nicename, array( 'slug' => $cat_tax, 'parent'=> $parent) );
									}
									
									if($_count_cat === 0 && $_count == 0 && (! is_wp_error( $new_cat )) )
									$x[] = wp_set_object_terms( $post_id, (int)$new_cat['term_id'], $_nicename, false );
									elseif( (!is_wp_error( $new_cat )) ) {
									$x[] = wp_set_object_terms( $post_id, (int)$new_cat['term_id'], $_nicename, true );
									$parent = $new_cat['term_id'];
									}									
									$_count_cat++;
								}
								$_count++;
								unset($parent);
							} else {
								$_nicename = urldecode($_nicename);
								$new_cat = term_exists( strtolower(strtr($tax, $this->gost)), $_nicename);
								if ( ! is_array( $new_cat ) || is_wp_error( $new_cat ) ) {
									$new_cat = term_exists( $tax, $_nicename);
									if ( ! is_array( $new_cat ) || is_wp_error( $new_cat ) )
									wp_insert_term(	$tax, $_nicename, array( 'slug' => $tax ) );
								}
								if($_count == 0)
								$x[] = wp_set_object_terms( $post_id, $tax, $_nicename, false );
								else
								$x[] = wp_set_object_terms( $post_id, $tax, $_nicename, true );
								$_count++;							
							}
						}
						$value_sanitized = $_nicename;
						
						if( !version_compare( WOOCOMMERCE_VERSION, '2.4', '<' )) {
							$_name_atrr_arr = array();
							foreach($value as $_name_atrr) {
								$_name_atrr_arr [] = @get_term_by( 'name', trim($_name_atrr), $_nicename )->slug;
							} 
							$name_atrr = implode("|",array_map('trim',$_name_atrr_arr));
						} else $name_atrr = implode("|",array_map('trim',$value));
						$_is_visible = 1;
						// $_is_visible = $_is_visible_attr ? 0 : 1;
						$attrib[strtolower(urlencode($value_sanitized))]=
						array('name' =>  $value_sanitized,
							'value' => $name_atrr,
							'position' => $counts,
							"is_visible"=> $_is_visible,
							"is_variation"=> $_is_visible_attr,
							'is_taxonomy' => 1
						);
						$counts++;
					}
					unset($value);
				}
				
			}
			// if( !version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
				// wp_set_object_terms($post_id, 'rated-5', 'product_visibility');
			// }
			if(is_array($custom_values)) {
				foreach( $custom_values as $id => $custom_value ) {
					if($id == 'menu_order') continue;
					if($id == '_visibility') {
						if( !version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
							if($custom_value == 'hidden') {
								wp_set_object_terms($post_id, array('exclude-from-catalog', 'exclude-from-search'), 'product_visibility');
							} elseif($custom_value == 'search') {
								wp_set_object_terms($post_id, 'exclude-from-catalog', 'product_visibility');
							} elseif($custom_value == 'catalog') {
								wp_set_object_terms($post_id, 'exclude-from-search', 'product_visibility');
							} elseif($custom_value == 'visible') {
								wp_remove_object_terms($post_id, array('exclude-from-catalog', 'exclude-from-search'), 'product_visibility');
							}
						}
					} elseif($id == '_children') {
						if( strpos($custom_value, '[') !== false || strpos($custom_value, '{') !== false )
							$custom_value = $this->parse_arr($custom_value);
						else {
							$custom_value = explode(",", $custom_value);
							$custom_value = array_map("trim", $custom_value);
						}
						if(is_array($custom_value))
							foreach( $custom_value as $k => $v ) {
								$_v = $this->wp_exist_post_by_sku($v);
								$custom_value[$k] = !empty($_v) ? $_v : $v;
							}
						update_post_meta( $post_id, $id, $custom_value );
					}
					
					if(in_array($id, array("_opt_price", '_opt_count')) ) {
						$custom_value = explode("||", $custom_value);
						$custom_value = array_map("trim", $custom_value);
						if(is_array($custom_value) && $custom_value[0] === '')
							$custom_value = null;
					} elseif(in_array($id, array("_sale_price_dates_to", '_sale_price_dates_from')) ) {
						$custom_value = strtotime($custom_value);
					}
					if(strpos($id, '_price') !== false ) {
						$custom_value = str_replace(array(',', ' ', ' '), array('.', '', ''), $custom_value);
						update_post_meta( $post_id, $id, $custom_value );
					} 					
					else {
						if(strpos($custom_value, '[') !== false || strpos($custom_value, '{') !== false ) 
							update_post_meta( $post_id, $id, $this->parse_arr ($custom_value) );
						else 
							update_post_meta( $post_id, $id, $custom_value );
					}
					
				}
			}
			/////// explode field multicat ',' separator

			$prod_cats = explode(',',$multi_cat);
			if(isset($prod_cats[0]) && !empty($prod_cats[0])) {
				for($i=0;$i<count($prod_cats);++$i) {
					$parent = false;
					$prod_cats[$i] = trim($prod_cats[$i]);
					if(empty($prod_cats[$i])) continue;
					if( false !== strpos($prod_cats[$i], "Brands>") ) {
						$prod_cats[$i] = str_replace("Brands>", "", $prod_cats[$i]);
						
						if($hierarchical_multicat) {
							
							$cat_taxs = explode( '>', $prod_cats[$i] );
							
							foreach ( $cat_taxs as $cat_tax)
							{
								$new_cat = term_exists( $cat_tax, 'brends' );
								if ( ! is_array( $new_cat ) || is_wp_error( $new_cat ) ) {
									$new_cat = wp_insert_term(	$cat_tax, 'brends', array( 'slug' => $cat_tax, 'parent'=> $parent) );
								}
								$x[] = wp_set_object_terms( $post_id, (int)$new_cat['term_id'], 'brends', true );
								$parent = $new_cat['term_id'];
								
								//check out http://wordpress.stackexchange.com/questions/24498/wp-insert-term-parent-child-problem
								delete_option("product_cat_children");
							}
							unset($parent);	
						} else {
							$new_cat = term_exists( $prod_cats[$i], 'brends' );
							if ( ! is_array( $new_cat ) || is_wp_error( $new_cat ) ) {
								wp_insert_term(	$prod_cats[$i], 'brends', array( 'slug' => $prod_cats[$i], 'parent'=> $parent) );
								$new_cat = term_exists( $prod_cats[$i], 'brends' );
							}
							$x[] = wp_set_object_terms( $post_id, (int)$new_cat['term_id'], 'brends', true );
						}
						delete_option("brends_children"); 
						unset($prod_cats[$i]);
					}
					if($hierarchical_multicat) {
						$cat_taxs = explode( '>', $prod_cats[$i] );
						$parent = false;
						$_count_cat = 0;
						foreach ( $cat_taxs as $cat_tax)
						{
							$cat_tax = trim($cat_tax);
							if(empty($cat_tax)) continue;
							if( is_numeric($cat_tax) ) {
								$cat_id = (int)$cat_tax;
							} else {
								$new_cat = term_exists( $cat_tax, 'product_cat' );
								if ( ! is_array( $new_cat ) || is_wp_error( $new_cat ) ) {
									$new_cat = wp_insert_term(	$cat_tax, 'product_cat', array( 'slug' => $cat_tax, 'parent'=> $parent) );
								}
								if( isset( $new_cat['term_id']) )
								$cat_id = (int)$new_cat['term_id'];
								else $cat_id = null;	
							}
							
							
							if($_count_cat === 0 && $i == 0) 
							$x[] = wp_set_object_terms( $post_id, $cat_id , 'product_cat' );
							else 
							$x[] =  wp_set_object_terms( $post_id, $cat_id , 'product_cat', true );
							
							$parent = $cat_id;

							delete_option("product_cat_children");
							$_count_cat++;
							
						}
						unset($parent);	
					} else {
						if( is_numeric($prod_cats[$i]) ) {
								$new_cat['term_id'] = (int)$prod_cats[$i];
						} else {
							$new_cat = term_exists( $prod_cats[$i], 'product_cat' );
							if ( ! is_array( $new_cat ) || is_wp_error( $new_cat ) ) {
								$new_cat = wp_insert_term(	$prod_cats[$i], 'product_cat', array( 'slug' => $prod_cats[$i], 'parent'=> $parent) );
							}
						}
						
						if($i == 0)
						$x[] =  wp_set_object_terms( $post_id, (int)$new_cat['term_id'], 'product_cat', false );
						else $x[] =  wp_set_object_terms( $post_id, (int)$new_cat['term_id'], 'product_cat', true );
					}
					delete_option("product_cat_children"); 
				}
				unset($parent);
			}
			if(isset($multi_cat_brands))
			$_prod_cats = explode(',',$multi_cat_brands);
			if(isset($_prod_cats[0]) && !empty($_prod_cats[0])) {
				$i=0;
				
				foreach($_prod_cats as $v) {
				
					$_prod_cats[$i] = trim($_prod_cats[$i]);
					if(empty($_prod_cats[$i])) continue;
					if(taxonomy_exists('brends')) {
					if($hierarchical_multicat) {
						$cat_taxs = explode( '>', $_prod_cats[$i] );
						$parent = false;
						$_count_cat = 0;
						foreach ( $cat_taxs as $cat_tax)
						{
							$cat_tax = trim($cat_tax);
							if(empty($cat_tax)) continue;
							$new_cat = term_exists( $cat_tax, 'brends' );
							if ( ! is_array( $new_cat ) || is_wp_error( $new_cat ) ) {
								$new_cat = wp_insert_term(	$cat_tax, 'brends', array( 'slug' => $cat_tax, 'parent'=> $parent) );
							}
							if($_count_cat === 0 && $i == 0)
							$x[] =  wp_set_object_terms( $post_id, (int)$new_cat['term_id'], 'brends', false );
							else 
							$x[] =  wp_set_object_terms( $post_id, (int)$new_cat['term_id'], 'brends', true );
							$parent = $new_cat['term_id'];
							
							delete_option("brends_children");
							$_count_cat++;
						}
						unset($parent);
					} else {
						$_new_cat = term_exists( $_prod_cats[$i], 'brends' );
						if ( ! is_array( $_new_cat ) ) {
							$_new_cat = wp_insert_term(	$_prod_cats[$i], 'brends', array( 'slug' => $_prod_cats[$i], 'parent'=> $_parent) );
						}
						if($i == 0)
						$x[] =  wp_set_object_terms( $post_id, (int)$_new_cat['term_id'], 'brends', false );
						else
						$x[] =  wp_set_object_terms( $post_id, (int)$_new_cat['term_id'], 'brends', true );

						delete_option("brends_children"); 
					}
					} elseif(taxonomy_exists('brands')) {
					if($hierarchical_multicat) {
						$cat_taxs = explode( '>', $_prod_cats[$i] );
						$parent = false;
						$_count_cat = 0;
						
						foreach ( $cat_taxs as $cat_tax)
						{
							$cat_tax = trim($cat_tax);
							
							if(empty($cat_tax)) continue;
							$new_cat = term_exists( $cat_tax, 'brands' );
							
							if ( ! is_array( $new_cat ) || is_wp_error( $new_cat ) ) {
								$new_cat = wp_insert_term(	$cat_tax, 'brands', array( 'slug' => $cat_tax, 'parent'=> $parent) );
							}
							
							if($_count_cat === 0 && $i == 0)
							$x[] = wp_set_object_terms( $post_id, (int)$new_cat['term_id'], 'brands', false );
							else
							$x[] = wp_set_object_terms( $post_id, (int)$new_cat['term_id'], 'brands', true );
							$parent = $new_cat['term_id'];
							$_count_cat++;
							delete_option("brands_children");
						}
						unset($parent);
					} else {
						$_new_cat = term_exists( $_prod_cats[$i], 'brands' );
						if ( ! is_array( $_new_cat ) ) {
							$_new_cat = wp_insert_term(	$_prod_cats[$i], 'brands', array( 'slug' => $_prod_cats[$i], 'parent'=> $_parent) );
						}
						if($i == 0)
						$x[] =  wp_set_object_terms( $post_id, (int)$_new_cat['term_id'], 'brands', false );
						else $x[] =  wp_set_object_terms( $post_id, (int)$_new_cat['term_id'], 'brands', true );

						delete_option("brands_children"); 
					}
					}
					unset($_prod_cats[$i]);
					$i++;
				}
				unset($_parent);
			}
				//echo '<pre>'; var_dump($taxo_values); echo '</pre>'; 
				foreach( $taxo_values as $tax => $term ) {
					unset($_term);
					$term = trim($term);
					if( empty( $term ) ) continue;
					if($tax == "product_tag") {
						$_term = explode("," , $term);
						$_term = array_map("trim" , $_term);		
					} elseif(strpos($tax, 'pa_') === 0) {
						$_term = explode("," , $term);
						if(sizeof($_term) == 1) $_term = explode("|" , $term);
						$_term = array_map("trim" , $_term);
					} else {
						$_term = explode("," , $term);
						if(sizeof($_term) == 1) $_term = explode("|" , $term);
						$_term = array_map("trim" , $_term);
					}
				 
					if(is_array($_term) && sizeof($_term) > 1) {
						$tax = urldecode($tax);
						$_count = 0;
						$_new_term = array();
						foreach($_term as $term_ ) {
							if($hierarchical_multicat) {
									if(strpos($term_, '>') !== 0)
									$cat_taxs = explode( '>', $term_ );
									else
									$cat_taxs = array( $term_ );
									$parent = false;
									$_count_cat = 0;
									foreach ( $cat_taxs as $cat_tax)
									{
										$cat_tax = trim($cat_tax);
										if(empty($cat_tax)) continue;
										
										$new_cat = term_exists( $cat_tax, $tax );
										
										if ( ! is_array( $new_cat ) || is_wp_error( $new_cat ) ) {
											$new_cat = wp_insert_term(	$cat_tax, $tax, array( 'slug' => $cat_tax, 'parent'=> $parent) );
										}
										if($_count_cat === 0 && $_count == 0)
										$x[] = wp_set_object_terms( $post_id, (int)$new_cat['term_id'], $tax, false );
										else
										$x[] = wp_set_object_terms( $post_id, (int)$new_cat['term_id'], $tax, true );
										$parent = $new_cat['term_id'];
										$_count_cat++; $_count++;
									}
									unset($parent);
									$_term_[] = implode("|", $cat_taxs);
							} else {
								$new_term = term_exists( $term_, $tax );
								if ( $new_term === 0 || $new_term === null )
								$new_term = wp_insert_term(	$term_, $tax, array( 'slug' => $term_ ) );
								$_new_term[] = $new_term['term_id'];
								$_term_[] = $term_;		
								
							}

						}
						if(strpos($tax, 'pa_') === 0) {
							$attribs[ strtolower(urlencode($tax)) ] =
								array('name' => $tax,
									'value' => implode("|", $_term_),
									'position' => $counts,
									"is_visible"=> 1,
									"is_variation"=> 0,
									'is_taxonomy' => 1
								);
							$counts++;
						}
						
						foreach($_new_term as $k => $v) {
							$v = trim($v);
							if(empty($v)) continue;
							if($k == 0)
							$x[] = wp_set_object_terms( $post_id, (int)$v, $tax, false );
							else $x[] = wp_set_object_terms( $post_id, (int)$v, $tax, true );
						}
						unset($_new_term,$_term_);
					} else {
						$tax = urldecode($tax);
						if($hierarchical_multicat ) {
							if(strpos($term, '>') !== 0)
							$cat_taxs = explode( '>', $term );
							else
							$cat_taxs = array( $term );
							$parent = false;
							$_count_cat = 0;
							foreach ( $cat_taxs as $cat_tax)
							{
								$cat_tax = trim($cat_tax);
								if(empty($cat_tax)) continue;
								$new_cat = term_exists( $cat_tax, $tax );
								if ( ! is_array( $new_cat ) || is_wp_error( $new_cat ) ) {
									$new_cat = wp_insert_term(	$cat_tax, $tax, array( 'slug' => $cat_tax, 'parent'=> $parent) );
								}
								if($_count_cat === 0)
								$x[] = wp_set_object_terms( $post_id, (int)$new_cat['term_id'], $tax, false );
								else
								$x[] = wp_set_object_terms( $post_id, (int)$new_cat['term_id'], $tax, true );
								$parent = $new_cat['term_id'];
								$_count_cat++;
							}
							unset($parent);
							$term = implode("|", $cat_taxs);
						} else {
							$new_term = term_exists( $term, $tax );
							//$_count_cat = 0;
							/* if(strpos($tax, 'pa_') === 0) {
								$tax = strtolower( rawurlencode ($tax) );
							} */
							if ( $new_term === 0 || $new_term === null )
							$new_term = wp_insert_term(	$term, $tax, array( 'slug' => $term ) );
							//if($_count_cat === 0)
							$x[] = wp_set_object_terms( $post_id, (int)$new_term['term_id'], $tax, false );
							/* else
							wp_set_object_terms( $post_id, (int)$new_term['term_id'], $tax, true ); */
						}
						if(strpos($tax, 'pa_') === 0) {
							$attribs[ strtolower(urlencode($tax)) ]=
							array('name' => $tax,
								'value' => $term,
								'position' => $counts,
								"is_visible"=> 1,
								"is_variation"=> 0,
								'is_taxonomy' => 1
							);
							$counts++;					
						}
					}
				}
				
				if( (!empty($attribs) && is_array($attribs) ) || (!empty($attrib) && is_array($attrib) )) {
					if(empty($_product_attributes)) $_product_attributes = array();
					if(!is_array($attrib)) $attrib = array();
					if(!is_array($attribs)) $attribs = array();
					$attrib = $_product_attributes+$attrib+$attribs;
					foreach($x as $v_x)
						$xxx[] = $v_x[0];
					if(isset($xxx) && 0) {
						$f = "SELECT * FROM `{$wpdb->prefix}term_relationships` as tt 
						LEFT JOIN `{$wpdb->prefix}term_taxonomy` AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
						LEFT JOIN `{$wpdb->prefix}terms` AS trt ON trt.term_id = tt.term_taxonomy_id
						WHERE tt.object_id = $post_id && tt.term_taxonomy_id NOT IN ('" . implode("', '", $xxx). "') && tr.taxonomy LIKE 'pa_%'";
						$resultss = (array) $wpdb->get_results( $f );
						$f = array();
						foreach($resultss as $term_remove) {
							$term_remove = (array) $term_remove;
							$f[] = wp_remove_object_terms($post_id, $term_remove['slug'], $term_remove['taxonomy']);
						}	
					}
					update_post_meta($post_id, '_product_attributes', $attrib);
				}

						$_attachments = explode(",", $thumbnail);
						if(!empty($_attachments[1])) {
							if(!is_array($attachments)) $attachments = array();
							unset($thumbnail);
							$thumbnail = array_shift($_attachments);
							
							$attachments = array_merge($attachments , $_attachments);
						}
						$is_gallery = false;
						if(is_array($attachments) && !empty($attachments)) {
						$c_attach = 0;
						foreach( $attachments as $url ) {
							$c_attach++;
							$url = explode('?', $url);
							$url = $url[0];
							$url = preg_replace('/( $|\s$)/', '', $url);
							$filename = urldecode( basename($url) );
							$post_title =  $post['post_title'] . " (attach$c_attach $post_id)";
							$already_there= $wpdb->get_row(
							$wpdb->prepare(
								"SELECT max(ID) as maxid , COUNT(*) as amount FROM $wpdb->posts where post_type='attachment' and post_title=%s",
								$post_title));

							if ( $already_there->amount > 0  ) {
								require_once(ABSPATH . "wp-admin" . '/includes/image.php');
										$is_gallery = true;
										$gallery_attach_id[] = $already_there->maxid;
										update_post_meta( $already_there->maxid, '_woocommerce_exclude_image', 0 );
								
								$file = get_post_meta( $already_there->maxid, '_wp_attached_file', true );
								$upload_dir = wp_upload_dir();
								$p = $upload_dir["baseurl"] . '/' . $file;
								if ( $this->isValidUrl( $p ) ) {
									$image_data_ = @file_get_contents($p);
									$ri = !in_array( md5($image_data_), $this->image);
									if( $ri )
									$this->image[] = md5($image_data_);
								}
							} else {
								$return_process = true;
								$ri = true;
								if( $_SESSION['fetch_attachments'] ) {
									if(empty($_SESSION['time2'])) $_SESSION['time2'] = time();
									//$path = wp_upload_dir($upload['file']);

									
									if(empty($_SESSION['time2']))$_SESSION['time2'] = time();
										$date = date("Y/m", $_SESSION['time2']);
									
								
									if($this->iteration && $uploaded%75 == 0 && $uploaded > 0) {
										$_SESSION['time2'] = $_SESSION['time2'] - 24*3600*31;
										$this->iteration = 0;
									} 
									// fetch the remote url and write it to the placeholder file
									if ( $this->isValidUrl( $url ) ) {
										$image_data = @file_get_contents($url);
										$ri = !in_array( md5($image_data), $this->image);
										if( $ri )
										$this->image[] = md5($image_data);
									}
									//$headers = wp_get_http( $url, $upload['file'] );

									// request failed
									if( $ri ) {
									if ( $image_data !== false ) {
										$upload_dir = wp_upload_dir();
										if (isset($upload_dir['path']) && wp_mkdir_p($upload_dir['path']))
											$_url = $upload_dir['path'] . '/' . $filename;
										else
											$_url = $upload_dir['basedir'] . '/' . $filename;
										$_url = str_replace(date("Y/m", time() ), $date, $_url);
										
										
										if(file_exists($_url)) {
											$image_data2 = @file_get_contents($_url);
											while( file_exists($_url) && $image_data2 !== false ) {
												if( md5($image_data) !== md5($image_data2) ) {
													if( preg_match('/r_(\d)\.([^\.]*)$/i', $_url, $m) ) {
														$_url = preg_replace('/r_(\d)\.([^\.]*)$/i', "r_" . ( ( $m[1] ) + 1 ) . ".$2", $_url, 1);
													} else {
														$_url = preg_replace('/\.([^\.]*)$/i', 'r_1.$1', $_url, 1);
													}
													$image_data2 = @file_get_contents($_url);
												} else {
													break;
												}
											}
											if (@file_put_contents($_url, $image_data)) {
												
											} elseif ( is_dir(str_replace($filename, '', $_url)) || @mkdir(str_replace($filename, '', $_url), '0755', true) ) {
												if (@file_put_contents($_url, $image_data)) {
													
												} else $return_process = false;
											} else $return_process = false;
										} else {
										if (@file_put_contents($_url, $image_data)) {
											
											} elseif( is_dir(str_replace($filename, '', $_url)) || @mkdir(str_replace($filename, '', $_url), '0755', true)) {
											if (@file_put_contents($_url, $image_data)) {
												
											} else $return_process = false;
										} else $return_process = false;
										}
									} else $return_process = false;
								}
								}
								
								if($return_process && $ri) {
									if(!empty($_url)) {
										$dest = $_url;
									} else {
										$base =  $url;
										$path = wp_upload_dir();
										$path = $path['basedir'];
										$dest = $path . '/' . $base;
									}

									//copy( $url, $dest );
									$the_image_run = true;

									if($the_image_run && file_exists($dest)) {
										$wp_filetype = wp_check_filetype( basename( $dest ), null );
										if(!is_array($attachments))$attachments = array();
										$attachment = array(
											'post_mime_type' => $wp_filetype['type'],
											'post_title' => $post_title,
											'post_status' => 'inherit',
										);
										$attach_id = wp_insert_attachment( $attachment, $dest, $post_id );
										// you must first include the image.php file for the function wp_generate_attachment_metadata() to work
										require_once(ABSPATH . "wp-admin" . '/includes/image.php');
										$attach_data = wp_generate_attachment_metadata( $attach_id, $dest );
										wp_update_attachment_metadata( $attach_id,  $attach_data );
										$is_gallery = true;
										$gallery_attach_id[] = $attach_id;
										update_post_meta( $attach_id, '_woocommerce_exclude_image', 0 );
									}
								} else {
									/* $att =  get_posts( array('post_parent' => $post_id, 'posts_per_page' => -1, 'post_type'=>'attachment', 'fields' => 'ids') );
									foreach($att as $attach_id) {
										if(get_post_meta($attach_id, '_woocommerce_exclude_image', true) === 0) delete_post_meta($attach_id, '_woocommerce_exclude_image');
									} */
									$is_gallery = true;
									$gallery_attach_id[] = 0;
								}
							}
						}

						}
						if ( strlen( $thumbnail ) > 0 ) {
							$thumbnail = explode('?', $thumbnail);
							$thumbnail = $thumbnail[0];
							$thumbnail = preg_replace('/( $|\s$)/', '', $thumbnail);
							$filename = urldecode( basename($thumbnail) );
							$post_title =   $post['post_title'];
							$__upload_dir = wp_upload_dir();
							$is_url_thumb = false;
							if ( $this->isValidUrl( $thumbnail ) ) {
								$image_data_ = @file_get_contents($thumbnail);
								$is_url_thumb = true;
								$already_there= $wpdb->get_row(
								$wpdb->prepare(
									"SELECT max(post_id) as maxid , COUNT(*) as amount  FROM $wpdb->postmeta where meta_key='_wp_attached_file_md5' and meta_value='%s'",
									md5($image_data_) ));
							} else {
								$basedir =  $thumbnail;
								$already_there= $wpdb->get_row(
								$wpdb->prepare(
									"SELECT max(post_id) as maxid , COUNT(*) as amount  FROM $wpdb->postmeta where meta_key='_wp_attached_file' and meta_value='%s'",
									$basedir));
							}
								
							if ( $already_there->amount > 0 ) {
								require_once(ABSPATH . "wp-admin" . '/includes/image.php');
								$tn_ad = get_post_meta( $post_id, '_thumbnail_id', true );
								if( !$tn_ad || $tn_ad != $already_there->maxid ) {
									set_post_thumbnail( $post_id, $already_there->maxid );
									update_post_meta( $post_id, '_thumbnail_id', $already_there->maxid );
								}
								$file = get_post_meta( $already_there->maxid, '_wp_attached_file', true );
								$upload_dir = $__upload_dir;
								$p = $upload_dir["baseurl"] . '/' . $file;
								if ( $this->isValidUrl( $p ) ) {
									$image_data_ = @file_get_contents($p);
									$is_url_thumb = true;
									$ri = !in_array( md5($image_data_), $this->image);
									if( $ri )
									$this->image[] = md5($image_data_);
								}
							} else {

								$return_process = true;
								$ri = true;
								if( $_SESSION['fetch_attachments'] ) {
									if(empty($_SESSION['time2'])) $_SESSION['time2'] = time();
									//$path = wp_upload_dir($upload['file']);

									
									if(empty($_SESSION['time2']))$_SESSION['time2'] = time();
										$date = date("Y/m", $_SESSION['time2']);
									
								
									if($this->iteration && $uploaded%75 == 0 && $uploaded > 0) {
										$_SESSION['time2'] = $_SESSION['time2'] - 24*3600*31;
										$this->iteration = 0;
									} 
									// fetch the remote url and write it to the placeholder file
									if ( $isValidUrl = $this->isValidUrl( $thumbnail ) ) {
										$image_data = @file_get_contents($thumbnail);
										$ri = !in_array( md5($image_data), $this->image);
										if( $ri )
										$this->image[] = md5($image_data); 
									}

									//$headers = wp_get_http( $thumbnail, $upload['file'] );

									// request failed
									if( $ri ) {
									if ( $image_data !== false ) {
										$upload_dir = $__upload_dir;
										if (isset($upload_dir['path']) && wp_mkdir_p($upload_dir['path']))
											$_url = $upload_dir['path'] . '/' . $filename;
										else
											$_url = $upload_dir['basedir'] . '/' . $filename;
										$_url = str_replace(date("Y/m", time() ), $date, $_url);
										if(file_exists($_url)) {
											$image_data2 = @file_get_contents($_url);
											while( file_exists($_url) && $image_data2 !== false ) {
												if( md5($image_data) !== md5($image_data2) ) {
													if( preg_match('/r_(\d)\.([^\.]*)$/i', $_url, $m) ) {
														$_url = preg_replace('/r_(\d)\.([^\.]*)$/i', "r_" . ( ( $m[1] ) + 1 ) . ".$2", $_url, 1);
													} else {
														$_url = preg_replace('/\.([^\.]*)$/i', 'r_1.$1', $_url, 1);
													}
													$image_data2 = @file_get_contents($_url);
												} else {
													break;
												}
											}
											if (@file_put_contents($_url, $image_data)) {
												
											} elseif ( is_dir(str_replace($filename, '', $_url)) || @mkdir(str_replace($filename, '', $_url), '0755', true) ) {
												if (@file_put_contents($_url, $image_data)) {
													
												} else $return_process = false;
											} else $return_process = false;
										} else {
										if (@file_put_contents($_url, $image_data)) {
											
											} elseif(is_dir(str_replace($filename, '', $_url)) || @mkdir(str_replace($filename, '', $_url), '0755', true)) {
											if (@file_put_contents($_url, $image_data)) {
												
											} else $return_process = false;
										} else $return_process = false;
										}
									} else $return_process = false;
								}
								}
								if( $ri ) {
								if($return_process) {
									if(!empty($_url)) {
										$dest = $_url;
									} else {
										$base =  $thumbnail;
										$path = $__upload_dir;
										$path = $path['basedir'];
										$dest = $path . '/' . $base;
									}

									//copy( $thumbnail, $dest );
									$the_image_run = true;

									if($the_image_run && file_exists($dest)) {
										$wp_filetype = wp_check_filetype( basename( $dest ), null );
										if(!is_array($attachments))$attachments = array();
										$attachment = array(
											'post_mime_type' => $wp_filetype['type'],
											'post_title' => $post_title,
											'post_content ' => '',
											'post_status' => 'inherit',
											'post_parent' => $post_id,
										);
										$attach_id = wp_insert_attachment( $attachment, $dest, $post_id );
										// you must first include the image.php file for the function wp_generate_attachment_metadata() to work
										require_once(ABSPATH . "wp-admin" . '/includes/image.php');
										wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $dest) );
										$s_thumb  = set_post_thumbnail( $post_id, $attach_id );
										if($is_url_thumb) update_post_meta( $attach_id, '_wp_attached_file_md5', md5($image_data_) );
										if(!$s_thumb)
										update_post_meta( $post_id, '_thumbnail_id', $attach_id );
									} 
								} else { delete_post_thumbnail( $post_id ); }
							}
						}
						}
						if($is_gallery) {
							if(is_array($gallery_attach_id))
							update_post_meta( $post_id, '_product_image_gallery', implode( ',', $gallery_attach_id ) );
						}
						$count++;
						$uploaded++;
						
						if(!empty($_POST['default_language']))
						if(!empty($sku_wc_refwpml) && !empty($_POST['current_language'])) {
							$id_general = $this->wp_exist_post_by_sku($sku_wc_refwpml);
							if($id_general) {
								$trid = $wpdb->get_var($wpdb->prepare("SELECT tr.trid FROM {$wpdb->prefix}icl_translations as tr WHERE element_id = '%s' ORDER BY element_type DESC", $id_general));
								$translation_id = $wpdb->get_var($wpdb->prepare("SELECT tr.translation_id FROM {$wpdb->prefix}icl_translations as tr WHERE element_id = '%s' ORDER BY element_type DESC", $post_id));
							}
							
							
							if(!empty( $translation_id ) && !empty( $trid ) ) {
								// $translation_id = $translation[0]->translation_id;
								// $trid = $translation[0]->trid;
								// var_dump($translation_id, $trid);
								update_option('saphali_current_language_wpml', $_POST['current_language']);
								
								if($translation_id && $translation_id > 0) {
									$q = "UPDATE {$wpdb->prefix}icl_translations SET language_code = '{$_POST['current_language']}s', trid = $trid, source_language_code = '{$_POST['default_language']}s' WHERE {$wpdb->prefix}icl_translations.translation_id = $translation_id; ";
									$wpdb->query($q);
									$q = "UPDATE {$wpdb->prefix}icl_translations SET language_code = '{$_POST['current_language']}', trid = $trid, source_language_code = '{$_POST['default_language']}' WHERE {$wpdb->prefix}icl_translations.translation_id = $translation_id; ";
									$wpdb->query($q);
								}
							}
						} else {
							$translation = $wpdb->get_results($wpdb->prepare("SELECT tr.translation_id, tr.trid FROM {$wpdb->prefix}icl_translations as tr WHERE element_id = '%s' ORDER BY element_type DESC", $post_id), OBJECT);
							if(!empty( $translation ) ) {
								$translation_id = $translation[0]->translation_id;
								$trid = $translation[0]->trid;
								if($translation_id && $translation_id > 0) {
									$q = "UPDATE {$wpdb->prefix}icl_translations SET language_code = '{$_POST['default_language']}', trid = $trid, source_language_code = NULL WHERE {$wpdb->prefix}icl_translations.translation_id = $translation_id; ";
									$wpdb->query($q);
								}
							}
						}
						if(!empty($_POST['default_language_pl'])) {
							$id_general = false;
							if( !empty($sku_wc_refwpml) ) {
								$id_general = $this->wp_exist_post_by_sku($sku_wc_refwpml);
							} 
							if(!empty($_POST['current_language_pl'])) {
								pll_set_post_language( $post_id, $_POST['current_language_pl'] );
								if($id_general) {
									$translations = pll_get_post_translations( $id_general );
								} else {
									$translations = pll_get_post_translations( $post_id );
								}
								PLL()->model->post->save_translations( $post_id, $translations );
							} else {
								$lng = pll_get_post_language($post_id);
								// if($lng === false)
									pll_set_post_language( $post_id, $_POST['default_language_pl'] );
								
								if($id_general) {
									$translations = pll_get_post_translations( $id_general );
								} else {
									$translations = pll_get_post_translations( $post_id );
								}
								PLL()->model->post->save_translations( $post_id, $translations );
							}
						}
					}
				}
				if(isset($upsell) && sizeof($upsell) > 0) {
					foreach($upsell as $_post_id => $_wc_upsell_ids) {
						$_wc_upsell = array();
						foreach($_wc_upsell_ids as $value) {
							if($up_post = $this->wp_exist_post_by_sku(trim($value)))
								$_wc_upsell[] = $up_post;
						}
						update_post_meta( $_post_id, '_upsell_ids', $_wc_upsell );
					}
				}
				if(isset($crosssell) && sizeof($crosssell) > 0) {
					foreach($crosssell as $_post_id => $_wc_crosssell_ids) {
						$_wc_crosssell = array();
						foreach($_wc_crosssell_ids as $value) {
							if($up_post = $this->wp_exist_post_by_sku(trim($value)))
								$_wc_crosssell[] = $up_post;
						}
						update_post_meta( $_post_id, '_crosssell_ids', $_wc_crosssell );
					}
				}
				if(isset($parent_post) && $parent_post > 0) {
					if($post_id)
					die(json_encode(array('imported' => 0,'count' =>  0, 'updated' => 0, 'post_id' => $post_id, 'success' => true, 'error' => false, 'variable' => 1)));
					else die(json_encode(array(0, 0, 0, 'post_id' => $post_id, 'success' => false, 'error' => true, 'variable' => 1, 'sku' => $sku)));

				} else {
					if( !empty($parent_sku) && empty( $parent_post) ) {
						$error = 'Не найдет ID главной позиции';
					} else $error = '';
					if($post_id)
					die(json_encode(array('imported' => $count,'count' =>  $uploaded, 'updated' => $count_['update'], 'post_id' => $post_id, 'success' => true, 'error' => false, 'variable' => 0, 'debug' => $error, $x )));
					
					
					else die(json_encode(array($count, $uploaded, $count_['update'], 'post_id' => $post_id, 'success' => false, 'error' => true, 'variable' => 0, 'debug' => $error, 'sku' => $sku, $x)));
				}
				if( ! version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
					if( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
						if( !is_object($woocommerce) ) global $woocommerce;
						if($post_id) $woocommerce->clear_product_transients($post_id);
					} else {
						if($post_id) wc_delete_product_transients($post_id);
					}
				}
			} else { 
				die(json_encode(array($count, $uploaded, $count_['update'], 'success' => false, 'post_id' => 'no', 'sku' => $sku)));
			}
		}
	}
	function exp_csv_var ($variation, $product, $search, $replace, $default_attributes, $saph_import_custom_filds) {
		if($_POST['for_excel'])
			$for_excel = '="';
		else $for_excel = '"';
		global $woocommerce;
		$this->xml .= "\n";
		$this->xml .= ';';
		$this->xml .= ';';
		$_sku = get_post_meta($variation["variation_id"],'_sku', true);
		$this->xml .= ( (strpos($_sku , '"') !== false || strpos( $_sku, ';') !== false) ? '"' : $for_excel) . $_sku . '";';
		if( $_POST['post_slug'] )
		$this->xml .= ';';
		if( $_POST['post_id'] )
		$this->xml .= $variation["variation_id"] . ';';
		$this->xml .= ';';
		if(!$_POST['no_post_content'])
		$this->xml .= ';';
		$this->xml .= ';';
		$this->xml .= ';';
		if(isset($this->cunent_valute)) {
			$_price = '';
			$price = get_post_meta( $variation["variation_id"], '_price', true);
			if(!$price) {
				foreach($this->cunent_valute as $code => $kurs) {
					${'_price_' . $code . $variation["variation_id"]} = '';
					$new_price = get_post_meta( $variation["variation_id"], '_price_' . $code, true);
					if($new_price > 0) {
						$_price = $new_price;
						${'_price_' . $code . $variation["variation_id"]} = $_price;
					} else {
						${'_price_' . $code . $variation["variation_id"]} = '';
					}
				}
				if($_price) {
					$price = $oldprice = '';
					
				}
			} else {
				$oldprice = get_post_meta($variation["variation_id"], '_sale_price' , true);
				$oldprice = empty($oldprice) ? '' : get_post_meta($variation["variation_id"], '_regular_price' , true);
				if( method_exists($product->get_child( $variation["variation_id"] ), 'get_price') )
				$price = $product->get_child( $variation["variation_id"] )->get_price();
				else {
					$price = get_post_meta($variation["variation_id"], '_price' , true);
				}
			}
		} else {
			$oldprice = get_post_meta($variation["variation_id"], '_sale_price' , true);
			$oldprice = empty($oldprice) ? '' : get_post_meta($variation["variation_id"], '_regular_price' , true);
			if( method_exists($product->get_child( $variation["variation_id"] ), 'get_price') )
			$price = $product->get_child( $variation["variation_id"] )->get_price();
			else {
				$price = get_post_meta($variation["variation_id"], '_price' , true);
			}
		}
		$oldprice = round($oldprice, 2);
		$price = round($price, 2);
		$this->xml .= $for_excel . (empty($oldprice) ? $price : $oldprice) . '";';
		$this->xml .= $for_excel . (empty($oldprice) ? '' : $price) . '";';

		$this->xml .= $for_excel . get_post_meta( $variation["variation_id"], '_stock', true ) . '";';
		$V_A='';
		foreach($variation["attributes"] as $tax => $term) {
			if($term)
			$V_A .='|' . get_term_by( 'slug', $term, str_replace('attribute_', '', urldecode($tax)) )->name;
		}
		$V_A = trim($V_A, '|');
		$_V_A = str_replace( array(':', ',', '"'), array( '', '.', '""'), $V_A );
		$this->xml .= ( (strpos($V_A , '"') !== false || strpos( $V_A, ';') !== false) ? '"' : $for_excel) . $_V_A . '";' ;
		$this->xml .= ';';
		if(!$_POST['no_post_image']) {
			$picture_xml = '';
			if ( has_post_thumbnail($variation["variation_id"])) {
				$picture = wp_get_attachment_image_src( get_post_thumbnail_id($variation["variation_id"]), 'full');
				if($_POST['exp_path_image'] && !empty($picture[0]) ) {
					$tmp_p = explode('uploads/', $picture[0]);
					$picture[0] = $tmp_p[ sizeof($tmp_p) - 1 ];
				}
				$picture_xml .= $picture[0];
				$this->xml .= $picture_xml . ';';
			} else $this->xml .= ';';	
		}
		
		$this->xml .= ';';
		$this->xml .= get_post_meta( $__ID, '_virtual', true ) . ';';
		$this->xml .= get_post_meta( $__ID, '_downloadable', true ) . ';';
		if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
			$this->xml .= get_post_meta( $__ID, '_file_path', true ) . ';';
		} else {
			$file_paths = get_post_meta( $__ID, '_file_paths', true );
			$this->xml .= @implode( "|", $file_paths ) . ';';
		}
		$id_tovara = method_exists($product, 'get_id') ? $product->get_id() : $product->id;
		$sku = get_post_meta($id_tovara,'_sku', true);
		if( $_POST['post_id'] )
		$this->xml .= '"' . $id_tovara . '";';
		else
		$this->xml .= ( (strpos($sku , '"') !== false || strpos( $sku, ';') !== false) ? '"' : $for_excel) . $sku . '";';
		if($default_attributes) {
			foreach($default_attributes as $k => $attr) {
				$_default_attrs = get_term_by( 'slug', $attr, urldecode($k) )->name;
				if($_default_attrs)
					$default_attrs[] = $_default_attrs;
			}
			@$this->xml .= $for_excel . implode('|', str_replace(array( ':', ','), array( '', '.'), $default_attrs)) . '"';
		}
		if(isset($this->cunent_valute)) {
			$xml = ';';
			$c = 0;
			foreach($this->cunent_valute as $code => $kurs) {
				$c++;
				if(sizeof($this->cunent_valute) > $c )
				$xml .= $for_excel . (isset(${'_price_' . $code . $variation["variation_id"]}) ? ${'_price_' . $code . $variation["variation_id"]} : '' )  . '";';
				else
				$xml .= $for_excel . (isset(${'_price_' . $code . $variation["variation_id"]}) ? ${'_price_' . $code . $variation["variation_id"]} : '' ). '"';
			}
			$this->xml .= $xml;
		}
		$cf_xml = '';
		foreach( $saph_import_custom_filds as $cf) {
			$_for_excel = '"';
			if(!empty($cf)) {
				$cfv = get_post_meta( $variation["variation_id"], $cf, true );
				if( is_numeric($cfv) ) 
					$_for_excel = $for_excel; 
			}
			$cf_xml .= empty($cf) ? ';' : $_for_excel . str_replace( $search, $replace, $cfv ) . '";';
		}

		$this->xml .= $cf_xml;
	}
	function exp_csv ($post, $product, $search, $replace, $is_simple = true, $saph_import_custom_filds, $post_variable_count = false) {
		global $woocommerce;
		if($_POST['for_excel'])
			$for_excel = '="';
		else $for_excel = '"';
		$this->xml .= "\n";
		
		$stock_status = get_post_meta( $post->ID, '_stock_status', true );
		
		$_backorders = get_post_meta( $post->ID, '_backorders', true );
		
		$product_type_a = wp_get_post_terms( $post->ID, 'product_type' );
		$product_type = isset($product_type_a[0]->slug) ? $product_type_a[0]->slug : '';
		foreach(array('product_cat', 'product_tag') as $taxonomy) {
			$product_category = wp_get_post_terms( $post->ID, $taxonomy );
			if(is_array($product_category) && !empty($product_category) ) {
				$cat = array();
				foreach($product_category as $category) {
					if($category->parent) {
						$cat[$category->parent][$category->term_id] = $category->name;
					} else {
						$cat['p'][$category->term_id] = $category->name;
					}
				}
				$x = '';
				if(isset($cat['p']))
				foreach($cat['p'] as $k => $v ) {
					if( isset($cat[$k]) )
						foreach($cat[$k] as $key => $ch_v) {
							if( isset($cat[$key]) ) {
								foreach($cat[$key] as $_key => $_ch_v)
									if( isset($cat[$_key]) ) 
										foreach($cat[$_key] as $__key => $__ch_v)
											$x .= $v . '>' . $ch_v . '>' . $_ch_v . '>' . $__ch_v . ',';
									else 
										$x .= $v . '>' . $ch_v . '>' . $_ch_v . ',';
							}						
							else
							$x .= $v . '>' . $ch_v . ',';
						}
					else $x .= $v. ',';
				}
				else {
					foreach($cat as $_cat) {
						foreach($_cat as $v ) {
							$x .= $v. ',';
						}
					}
				}
				$x = trim($x, ',');
				$this->xml .= '"' . str_replace(  $search, $replace, $x) . '";';
			} else $this->xml .= ';';
		}
		$sku = str_replace( $search, $replace, get_post_meta($post->ID,'_sku', true) );
		$this->xml .= ( (strpos($sku , '"') !== false || strpos( $sku, ';') !== false) ? '"' : $for_excel) . $sku . '";';
		if( $_POST['post_slug'] )
		$this->xml .= '"' . str_replace( $search, $replace, $post->post_name ) . '";';
		if( $_POST['post_id'] )
		$this->xml .= '"' . $post->ID . '";';
		$this->xml .= '"' . str_replace( $search, $replace, $post->post_title ) . '";';
		if(!$_POST['no_post_content'])
		$this->xml .= '"' . str_replace( $search, $replace, $post->post_content ) . '";';
		$this->xml .= '"' . str_replace( $search, $replace, $post->post_excerpt ) . '";';
		$this->xml .= '"' . $product_type . '";';
		if(isset($this->cunent_valute)) {
			$_price = '';
			$price = get_post_meta( $post->ID, '_price', true);
			if(!$price) {
				foreach($this->cunent_valute as $code => $kurs) {
					${'_price_' . $code . $post->ID} = '';
					$new_price = get_post_meta( $post->ID, '_price_' . $code, true);
					if($new_price > 0) {
						$_price = $new_price;
						${'_price_' . $code . $post->ID} = $_price;
					} else {
						${'_price_' . $code . $post->ID} = '';
					}
				}
				if($_price) {
					$price = $oldprice = '';
					
				}
			} else {
				$oldprice = $product->sale_price;
				$oldprice = empty($oldprice) ? '' : $product->regular_price;
				$price = $product->price;
			}
		} else {
			$oldprice = $product->sale_price;
			$oldprice = empty($oldprice) ? '' : $product->regular_price;
			$price = $product->price;
		}
		$oldprice = round($oldprice, 2);
		$price = round($price, 2);
		$this->xml .= $for_excel . (empty($oldprice) ? $price : $oldprice) . '";';
		$this->xml .= $for_excel . (empty($oldprice) ? '' : $price) . '";';

		$this->xml .= $for_excel .get_post_meta( $post->ID, '_stock', true ) . '";';
		
		$attrib = '';
		foreach ($product->get_attributes() as $attribute) {
			if ( $attribute['is_variation'] ) $_l = '*'; else $_l = ''; 
			
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) )
				$label = $_l . $woocommerce->attribute_label( $attribute['name'] ); 
			else 
				$label = $_l . wc_attribute_label( $attribute['name'] );
			if( str_replace(array( ':', ','), array( '', '.'), $label) != $label) {
				$this->no_comp_attr[] = $label;
			}
				
			if ( $attribute['is_taxonomy'] ) {
				$id_tovara = method_exists($product, 'get_id') ? $product->get_id() : $product->id;
				$values = woocommerce_get_product_terms( $id_tovara, $attribute['name'], 'names' );
				if( str_replace(array( ':', ','), array( '', '.'), $values) != $values) {
					if( is_array($this->no_comp_attr) && !in_array($label, $this->no_comp_attr) || !is_array($this->no_comp_attr) ) $this->no_comp_attr[] = $label;
				}
				$values = str_replace(array( ':', ','), array( '', '.') ,$values);
				$_values = @implode( ':', $values );
			} else {
					if(isset($attribute["options"])) {
						$attribute_value = implode( '|', $attribute["options"] );
					} else $attribute_value = $attribute['value'];
						
					if( str_replace(array( ':', ','), array( '', '.'), $attribute_value ) != $attribute_value ) {
					if( is_array($this->no_comp_attr) && !in_array($label, $this->no_comp_attr) || !is_array($this->no_comp_attr) ) $this->no_comp_attr[] = $label;
				}
					$attribute_value = str_replace(array( ':', ','), array( '', '.') ,$attribute_value);
					$values = explode( '|', $attribute_value );
				$_values = @implode( ':', $values );
			}
			$label = str_replace(array( ':', ','), array( '', '.') ,$label);
			if(!empty($_values)) {
				$attrib .= $label . ':' . $_values . ',';
			}
		}
		$attrib = trim($attrib, ',');
		$this->xml .= '"' . str_replace( array('"'), array('""'), $attrib ) . '";';	
		$upsell = get_post_meta( $post->ID, '_upsell_ids', true );
		$__upsell = array();
		foreach($upsell as $_upsell) {
			$u_s = get_post_meta( $_upsell, '_sku', true );
			if($u_s)
			$__upsell[] = $u_s;
		}
		@$this->xml .= $for_excel . implode( ',', $__upsell ) . '";';
		if(!$_POST['no_post_image']) {
			$picture_xml = '';
			if ( has_post_thumbnail()) {
				if(method_exists($product,'get_gallery_attachment_ids') )
					$attachment_ids = $product->get_gallery_attachment_ids();
				$picture = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
				if($_POST['exp_path_image'] && !empty($picture[0])) {
					$tmp_p = explode('uploads/', $picture[0]);
					$picture[0] = $tmp_p[ sizeof($tmp_p) - 1 ];
				}
				$picture_xml .= $picture[0] . ',';
				
				foreach ($attachment_ids as $_thumb) {
					$picture = wp_get_attachment_image_src( $_thumb, 'full');
					if( empty($picture[0]) ) continue;
					if($_POST['exp_path_image'] && !empty($picture[0])) {
						$tmp_p = explode('uploads/', $picture[0]);
						$picture[0] = $tmp_p[ sizeof($tmp_p) - 1 ];
					}
					$picture_xml .= $picture[0] . ',';				
				}
				$picture_xml = trim($picture_xml, ',');
				$picture_xml_a = explode(',', $picture_xml);
				$picture_xml_a = array_unique($picture_xml_a);
				$picture_xml = implode(',', $picture_xml_a);
				$this->xml .= $picture_xml . ';';
			} else $this->xml .= ';';
		}
		$this->xml .= $post->post_status . ';';
		$this->xml .= get_post_meta( $post->ID, '_virtual', true ) . ';';
		$this->xml .= get_post_meta( $post->ID, '_downloadable', true ) . ';';
		if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
			$this->xml .= get_post_meta( $post->ID, '_file_path', true ) . ';';
		} else {
			$file_paths = get_post_meta( $post->ID, '_file_paths', true );
			$this->xml .= @implode( "|", $file_paths ) . ';';
		}
		if($is_simple)
		$this->xml .= ';';
		if(isset($this->cunent_valute)) {
			if( $post_variable_count > 1 ) $xml = ';';
			$c = 0;
			foreach($this->cunent_valute as $code => $kurs) {
				$c++;
				if(sizeof($this->cunent_valute) > $c )
				$xml .= $for_excel . (isset(${'_price_' . $code . $post->ID}) ? ${'_price_' . $code . $post->ID} : '' )  . '";';
				else
				$xml .= $for_excel . (isset(${'_price_' . $code . $post->ID}) ? ${'_price_' . $code . $post->ID} : '' ) . '"';
			}
			$this->xml .= $xml;
		}
		$cf_xml = '';
		foreach( $saph_import_custom_filds as $cf) {
			$_for_excel = '"';
			if(!empty($cf)) {
				$cfv = get_post_meta( $post->ID, $cf, true );
				if( is_numeric($cfv) ) 
					$_for_excel = $for_excel; 
			}
			$cf_xml .= empty($cf) ? ';' : $_for_excel . str_replace( $search, $replace, $cfv ) . '";';
		}

		$this->xml .= $cf_xml;
	}
	function woo_expoexp_product_expoexp_end($e_ = false) {	
		if( !isset($_GET['debug']) ) error_reporting(0);
		global $woocommerce;
		$e_ = isset($e_) ? $e_ : false;
		@session_start();
		$search = array('º',
			'©',
			'¦',
			'³',
			'²',
			'​',
			'≤',
			'≥',
			'∆',
			'×',
			'Ø',
			'й',
			'―',
			'±',
			'∆',
			'"'
		);
		$replace = array('&#xBA;',
			'&copy;',
			'&#xA6;',
			'&#xB3;',
			'&#xB2;',
			'&#x200B;',
			'&#x2264;',
			'&#x2265;',
			'&#x2206;',
			'&#xD7;',
			'&#xD8;',
			'й',
			'&ndash;',
			'&#xB1;',
			'&#x2206;',
			'""'
		);

		$_search = array ("'&(amp);'i" ,
                 "'&([^;]*);'i", 
				 "'&([^amp;]?)'i", 
                 "'&p='i",
					 );
		$_replace = array ("&" ,
					  "&amp;\\1;",
					  "&amp;",
					  "&amp;p=",
					  );
	 $url = home_url() . '/wp-admin/admin-ajax.php?secret='. dechex(crc32(home_url())) . '&action=yml_expoexp_pre&no_download=1';
	remove_all_actions( 'loop_end' );
	remove_all_actions( 'loop_start' );
	
	
	global $saphali_waitinglist;
	remove_filter( 'woocommerce_available_variation', array($saphali_waitinglist, '_woocommerce_before_calculate_totals_s_logged_price'), 1 );
	$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1 ;
	$_SESSION['num'] = ( ( $paged / 5 ) - 1) > 0 ?  ceil( ( $paged / 5 ) - 1) : 0;


	if(empty($paged)) $paged = 1; 
	if( $paged == 1 ) {
	 $_SESSION['num'] = 0;
	 $_SESSION['save_post_id'] = array();
	}
		$args = array(
			'posts_per_page' => 10,
			'post_status' => array( 'pending', 'draft', 'publish' ),
			'paged' => $paged,
			'post_type' => 'product'
		);
		if(!empty($_POST['include'])) {$cat_id= explode(',',$_POST['include']); $cat_id = array_map('trim', $cat_id);} 
		if(!empty($_POST['exclude'])) {$cat_id_ex = explode(',',$_POST['exclude']); $cat_id_ex = array_map('trim', $cat_id_ex);}
		
		if(!empty($_POST['tax'])) {$tax = $_POST['tax']; }
		if(!empty($_POST['tax_val'])) {$tax_val = explode(',',$_POST['tax_val']); $tax_val = array_map('trim', $tax_val);}
		// if(!empty($_POST['product_ex_select'])) {$product_ex_select = explode(',',$_POST['product_ex_select']); $product_ex_select = array_map('trim', $product_ex_select);}
		if( isset($cat_id) && is_array($cat_id))
		foreach($cat_id as $k => $v) {
			if(@in_array($v , $cat_id_ex)) {unset($cat_id[$k]);}
		}
	
		if( (isset($cat_id) && is_array($cat_id) && sizeof($cat_id) > 0 ) || (isset($cat_id_ex) && is_array($cat_id_ex) && sizeof($cat_id_ex) > 0 ) || (isset($tax_val) && is_array($tax_val) && sizeof($tax_val) > 0 ) ) {
			$args['relation'] = 'AND';
		}
		if( (isset($cat_id) && is_array($cat_id) && sizeof($cat_id) > 0 ) ) {
			$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $cat_id,
					//'operator' => 'NOT IN'
				);
		}
		if( (isset($tax_val) && is_array($tax_val) && sizeof($tax_val) > 0 ) && isset($tax) ) {
			$args['tax_query'][] = array(
				'taxonomy' => $tax,
				'field' => 'id',
				'terms' => $tax_val,
				//'operator' => 'NOT IN'
			);
		}
		/* if( (isset($product_ex_select) && is_array($product_ex_select) && sizeof($product_ex_select) > 0 ) ) {
			$args['post__not_in'] = $product_ex_select;
		} */
		if(  (isset($cat_id_ex) && is_array($cat_id_ex) && sizeof($cat_id_ex) > 0 ) ) {
			$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $cat_id_ex,
					'operator' => 'NOT IN'
				);
		}
		$the_query = new WP_Query( $args );

	$no_price = 0;
	$notify_no_stock_amount = get_option('woocommerce_notify_no_stock_amount', 0);
	$woocommerce_weight_unit = get_option('woocommerce_weight_unit');
	$woocommerce_dimension_unit = get_option('woocommerce_dimension_unit');
	$array_meta_key = array(
		'_sku',
		
		'_manage_stock',
		'_stock_status',
		'_stock',
		
		'_backorders',
		
		'_weight',
		'_height',
		'_width',
		'_length',				
	);
	$saph_export_custom_filds = get_option('saph_export_custom_filds');
	$saph_import_custom_filds = array();
	$saph_export_custom_filds = empty($saph_export_custom_filds) ? array() : $saph_export_custom_filds;
	foreach($saph_export_custom_filds as $v) {
		if( !empty( $v['id'] ) )
		 $saph_import_custom_filds[] = $v['id'];
	}
	global $wpdb;
	
	$post_variable_count =  $wpdb->get_var($wpdb->prepare("SELECT count(p.ID) FROM $wpdb->posts AS p LEFT JOIN $wpdb->posts AS pp  ON pp.ID = p.post_parent  WHERE  p.post_type = '%s' AND p.ID is not null AND p.post_status = 'publish' AND pp.post_status IN ('pending', 'draft', 'publish') GROUP BY pp.ID", 'product_variation' ) );
	$this_code_rurrency_xml = '';
	if(isset($this->cunent_valute)) {
		$c = 0;
		$this_code_rurrency_xml .= ';';
		foreach($this->cunent_valute as $code => $kurs) {
			$c++;
			if(sizeof($this->cunent_valute) > $c )
			$this_code_rurrency_xml .= '_price_' . $code . ';';
			else
			$this_code_rurrency_xml .= '_price_' . $code;
		}
	}
	if(!$_POST['no_post_content']) {
		$p_content = '"Подробное описание";';
	} else {
		$p_content = "";
	}
	if(!$_POST['no_post_image']) {
		$p_image = '"Картинка";';
	} else {
		$p_image = "";
	}
	if( $_POST['post_slug'] ) {
		$p_slug = '"Slug";';
	} else {
		$p_slug = "";
	}
	if( $_POST['post_id'] ) {
		$p_slug .= '"ID";';
	} 
	if( $post_variable_count > 1 ) {
		$uploaded = 0;

		if($paged == 1) {
			$title_cf = implode(';', $saph_import_custom_filds);
			$title_cf = !empty( $title_cf ) ? ';' . $title_cf : '';
			$this->xml = '"Категория";"Метки товара";"Артикул";'.$p_slug.'"Наименование";'.$p_content.'"Краткое описание";"Тип товара";"Цена";"Цена со скидкой";"Кол-во на складе";"Attribs";"Перекрестные товары";'.$p_image.'"Статус товара";virtual;downloadable;file_paths;sku_parent;default_attr' . $this_code_rurrency_xml . $title_cf;
			
		}
		
		while ( $the_query->have_posts() ) { $the_query->the_post();  global $post,$product; $uploaded++; 
		
		if( in_array( $product->product_type,  array( 'simple', 'external') ) || $product->product_type == 'variable' ) {
			$this->exp_csv ($post, $product, $search, $replace, true, $saph_import_custom_filds, $post_variable_count);
		if( 1 ) {
			if($product->product_type != 'variable' ){
				
			} elseif($product->product_type == 'variable') {

			$c__ = 0;
				$_variation = array();
				foreach ( $product->get_available_variations() as $_var) {
					$menu_order =  $wpdb->get_var($wpdb->prepare("SELECT p.menu_order FROM $wpdb->posts AS p WHERE  p.ID = '%s' ",  $_var["variation_id"]) );
					if($menu_order > 0 && !isset($_variation[$menu_order]))
					$_variation[$menu_order] = $_var;
					else
					$_variation[] = $_var;
				}
				foreach ( $_variation as $variation) {
						if($c__ === 0) 
							$default_attributes = get_post_meta($post->ID, '_default_attributes', true);
						else $default_attributes = '';
						$c__++;
					$this->exp_csv_var( $variation, $product, $search, $replace, $default_attributes, $saph_import_custom_filds);
				}

					unset($variable_attr, $variable_title, $variable_name);
			  }
			  }
			 } else {
			  $no_price++;
			  }
			}
			//$yml_expoexp_session = ob_get_contents();
			$nums = $_SESSION['num'];
			if($paged%5 == 0) $_SESSION['num'] = $_SESSION['num'] + 1;
				$file = 'export'.$_SESSION['num'].'.csv';
				if($paged == 1) {
					$contents = '';
				} else {
					if($handle = @fopen(SAPHALI_PLUGIN_DIR_PATH_IMP . $file, 'r')) {
						/* $n = filesize(SAPHALI_PLUGIN_DIR_PATH_IMP . $file);
						$contents = fread($handle, $n); */
						$contents = '';
						while (!feof($handle)) {
						  $contents .= fread($handle, 8192);
						}
						fclose($handle);
					} else $contents = '';
				}
				if($handle = fopen(SAPHALI_PLUGIN_DIR_PATH_IMP . $file, 'w')) {
					if( $contents !== false) {
						$contents = $contents . $this->xml;
					}
					if (fwrite($handle, $contents) === FALSE) {
						echo "Не могу произвести запись в файл ($file)";
						exit;
					}
					fclose($handle);
				} else echo "Файл $file недоступен для записи";
			if($paged == $the_query->max_num_pages)
			$end = true;
			else
			$end = false;
		} else {
		$uploaded = 0;
		if($paged == 1) {
			$title_cf = implode(';', $saph_import_custom_filds);
			$title_cf = !empty( $title_cf ) ? ';' . $title_cf : '';
			$this->xml = '"Категория";"Метки товара";"Артикул";'.$p_slug.'"Наименование";'.$p_content.'"Краткое описание";"Тип товара";"Цена";"Цена со скидкой";"Кол-во на складе";"Attribs";"Перекрестные товары";'.$p_image.'"Статус товара";virtual;downloadable;file_paths' . $this_code_rurrency_xml. $title_cf;
		}
		while ( $the_query->have_posts() ) { $the_query->the_post();  global $post,$product; $uploaded++; 
		
		if( in_array( $product->product_type,  array( 'simple', 'external') ) || $product->product_type == 'variable' ) {
				$this->exp_csv ($post, $product, $search, $replace, false, $saph_import_custom_filds, $post_variable_count);
			 } else {
			  $no_price++;
			  }
			}
			//$yml_expoexp_session = ob_get_contents();
			$nums = $_SESSION['num'];
			if($paged%5 == 0) $_SESSION['num'] = $_SESSION['num'] + 1;
				$file = 'export'.$_SESSION['num'].'.csv';
				if($paged == 1) {
					$contents = '';
				} else {
					if($handle = @fopen(SAPHALI_PLUGIN_DIR_PATH_IMP . $file, 'r')) {
						/* $n = filesize(SAPHALI_PLUGIN_DIR_PATH_IMP . $file);
						$contents = fread($handle, $n); */
						$contents = '';
						while (!feof($handle)) {
						  $contents .= fread($handle, 8192);
						}
						fclose($handle);
					} else $contents = '';
				}
				if($handle = fopen(SAPHALI_PLUGIN_DIR_PATH_IMP . $file, 'w')) {
					if( $contents !== false) {
						$contents = $contents . $this->xml;
					}
					if (fwrite($handle, $contents) === FALSE) {
						echo "Не могу произвести запись в файл ($file)";
						exit;
					}
					fclose($handle);
				} else echo "Файл $file недоступен для записи";
			if($paged == $the_query->max_num_pages)
			$end = true;
			else $end = false;
		}
		if(!$e_) {
		if($end)
			die(json_encode(array('exported' => $uploaded,'count' =>  $paged,  'success' => true, 'error' => false, 'end' => true , 'url' => home_url() . '/wp-admin/admin-ajax.php?action=action_expoexp_s_end_export&' . time(), 'no_price' => $no_price, 'no_comp_attr' => $this->no_comp_attr )));
			else die(json_encode(array('exported' =>$uploaded, 'count' =>$paged,  'success' => true, 'error' => false, 'end' => false, 'no_price' => $no_price, 'no_comp_attr' => $this->no_comp_attr)));
		} else {
			if($end)
			return (json_encode(array('exported' => $uploaded,'count' =>  $paged,  'success' => true, 'error' => false, 'end' => true , 'url' => home_url() . '/wp-admin/admin-ajax.php?action=action_expoexp_s_end_export&' . time(), 'no_price' => $no_price, 'no_comp_attr' => $this->no_comp_attr )));
			else return (json_encode(array('exported' =>$uploaded, 'count' =>$paged,  'success' => true, 'error' => false, 'end' => false, 'no_price' => $no_price, 'no_comp_attr' => $this->no_comp_attr)));		
		}
	}
	function process_export() {
		// If the button was clicked
		$the_query = new WP_Query(  array('post_type' => 'product', 'posts_per_page' => 10)  );

	//var_dump($wp_query->max_num_pages);
		$count = $the_query->max_num_pages;
	$number = 0;
	if($count != 1) {
		wp_reset_query();
		wp_reset_postdata();
		$the_query = new WP_Query( array('post_type' => 'product', 'post_status' => array( 'pending', 'draft', 'publish' ), 'posts_per_page' => 10, 'paged' => $count) );
		$number = ($count -1 ) * 10;
	} 
	 $_number = 0;
	 while ( $the_query->have_posts() ) { $the_query->the_post(); $_number++; }
	 wp_reset_query(); wp_reset_postdata();
	 $_number = $_number + $number;
		//if ( ! empty( $_POST['wc_load_products_from_csv'] ) || !( $count  > 0 ) ) {
			// Capability check
			if ( ! current_user_can( 'manage_woocommerce' ) )
				wp_die( __( 'Cheatin&#8217; uh?' ) );

			// Form nonce check
			//check_admin_referer( 'woo-export-s' );

			// Create the list of image IDs
			$elements = array();
			for($i = 1; $i<= $count; $i++) $elements[] = $i;
			
			$ids = implode( ',', $elements );
?>
	<noscript><p><em><?php _e( "Необходимо включить Javascript!", 'tcp' ) ?></em></p></noscript>

	<div id="export-bar" style="position:relative;height:25px;">
		<div id="export-bar-percent" style="position:absolute;left:50%;top:50%;width:300px;margin-left:-150px;height:25px;margin-top:-9px;font-weight:bold;text-align:center;"></div>
	</div>

	<h3 class="title"><?php _e( 'Информация', 'tcp' ) ?></h3>

	<p>
		<?php printf( __( 'Всего: %s', 'tcp' ), '<span id="export-debug-total_pr">'.$_number.'</span>'  ); ?><br />
		<?php printf( __( 'Успешно обработано: %s', 'tcp' ), '<span id="export-debug-successcount">0</span>' ); ?><br />
	</p>

	<ol id="export-debuglist">
		<li style="display:none"></li>
	</ol>

	<script type="text/javascript">
	// <![CDATA[
		jQuery(document).ready(function($){
			Array.prototype.uniq = function(){
				return this.filter(function (elem, pos, arr) {
					return arr.indexOf(elem) == pos;
				});
			};
			//$("#export.tab_content").hide();
			$("#export-debuglist").hide();
			var i;
			var exp_product = [<?php echo $ids; ?>];
			var exp_total = exp_product.length;
			var ajaxTime;
			var totalTime;
			var exp_count = 1;
			var exp_percent = 0;
			var exp_successes = 0;
			var exp_errorcountprice = 0;
			var exp_successes_export = 0;
			var exp_errors = 0;
			var exp_failedlist = '';
			var exp_resulttext = '';
			var exp_timestart = new Date().getTime();
			var exp_timeend = 0;
			var exp_totaltime = 0;
			var exp_continue = true;
			
			var incl = '';
			var ex = '';
			var tax = '';
			var tax_value = '';
			var no_post_content = false;
			var no_post_image = false;
			var exp_path_image = true;
			var post_slug = false;
			var post_id = false;
			var for_excel = false;

			// Create the progress bar
			jQuery("#export-bar").progressbar();
			$("#export-bar-percent").html( "0%" );

			// Stop button
			$("#export-stop").click(function(event) {
				event.preventDefault();
				exp_continue = false;
				$('#export-stop').text("<?php echo $this->esc_quotes( __( 'Прерывание...', 'tcp' ) ); ?>");
			});
			//$("#mainforfm .single_select_page select#product_category_shortcode").
			//$("#mainforfm .single_select_page select#product_category_shortcode_ex").
			// Clear out the empty list element that's there for HTML validation purposes
			$("#export-debuglist li").remove();
			var _no_comp_attr = [];

			// Called after each resize. Updates debug information and the progress bar.
			function ExportGoUpdateStatus( id, success, response ) {
				jQuery("#export-bar").progressbar( "value", ( exp_count / exp_total ) * 100 );
				$("#export-bar-percent").html( Math.round( ( exp_count / exp_total ) * 1000 ) / 10 + "%" );
				exp_count = exp_count + 1;

				if ( success ) {
					exp_successes = exp_successes + response.exported;
					$("#export-debug-successcount").html(exp_successes);
					$("#export-debug-total").html( (exp_successes + exp_errors) );
					
					if(response.exported) {
						exp_successes_export++;
					} 
				}
				else {
					exp_errors = exp_errors + response.exported;
					exp_failedlist = exp_failedlist + ',' + id;
					$("#export-debug-failurecount").html(exp_errors);
					$("#export-debuglist").append("<li>" + response.error + "</li>");
				}
			}

			// Called when all product have been processed. Shows the results and cleans up.
			function ExportGoFinishUp() {
				totalTime = new Date().getTime() - ajaxTime;
				setClockdebuglist();
				$('.action_export.button').attr('disabled', false);
				exp_timeend = new Date().getTime();
				exp_totaltime = Math.round( ( exp_timeend - exp_timestart ) / 1000 );
				
				$('#export-stop').hide();

				if ( exp_errors > 0 ) {
					exp_resulttext = '<?php if(isset($text_failures)) echo $text_failures; ?>';
				} else {
					exp_resulttext = '<?php if(isset($text_nofailures)) echo $text_nofailures; ?>';
				}
				
				$("#message").html("<p><strong>" + exp_resulttext + "</strong></p>");
				$("#message").show();
				$("#export_csv.tab_content").show('slow');
				
				setTimeout(function() { jQuery(window).scrollTop(1250) }, 500);
				
				if('<?php if(isset($_REQUEST['is_selected_item'])) echo $_REQUEST['is_selected_item']; ?>' != '' || $("#export-debuglist-up").text() != '') {
					$("#export-debuglist-up").show();
					$("#export-debuglist-up").parent().children("h3").show();				
				}
				$("#export-debuglist").show();
				
				$(location).attr('href',ajaxurl+'<?php echo '?action=action_expoexp_s_end_export&' . time(); ?>');
				
			}
			
			// Regenerate a specified image via AJAX
			function ExportGo( id ) {
				
				$.ajax({
					type: 'POST',
					url: ajaxurl,
					data: { action: "import_expoexp_s",
						paged: id,
						exclude: ex,
						include: incl,
						tax: tax,
						tax_val: tax_value,
						no_post_content: no_post_content,
						no_post_image: no_post_image,
						exp_path_image: exp_path_image,
						post_slug: post_slug,
						post_id: post_id,
						for_excel: for_excel,
					},
					success: function( response ) {
						if ( response !== Object( response ) || ( typeof response.success === "undefined" && typeof response.error === "undefined" ) ) {
							response = new Object;
							response.success = false;
							response.error = "<?php printf( esc_js( __( 'Запрос экспорта завершился неудачей (paged %s). Это, вероятно, связано с превышением объема доступной памяти или другого типа фатальной ошибки.', 'tcp' ) ), '" + id + "' ); ?>";
						}
						if(response.end === true) {
							$(".download_csv").html('<a href="'+response.url+'">Cоздать и скачать CSV</a>');
						}
						if(response.no_price > 0) {
							if(exp_errorcountprice == 0)
							$("#export-debug-errorcountprice").after("<br />ID товара: " + id);
							exp_errorcountprice = exp_errorcountprice + response.no_price;
							$("#export-debug-errorcountprice").html(exp_errorcountprice);
							if(exp_errorcountprice != 0)
							$("#export-debug-errorcountprice").after(", ID товара: " + id);
						}
						if ( response.success ) {
							ExportGoUpdateStatus( id, true, response );
						}
						else {
							ExportGoUpdateStatus( id, false, response );
						}

						if ( exp_product.length && exp_continue ) {
							if(response.no_comp_attr) {
								jQuery.each(response.no_comp_attr, function(i, e){
								  if(typeof e != 'undefined') {
									response.no_comp_attr[i] = e.replace(/^\*/, '');
								  }
								});
								_no_comp_attr = $.merge(_no_comp_attr, response.no_comp_attr.uniq());
							}
							ExportGo( exp_product.shift() );
						}
						else {
							if( response.no_comp_attr || _no_comp_attr) {
								if(response.no_comp_attr) {
									jQuery.each(response.no_comp_attr, function(i, e){
									  if(typeof e != 'undefined') {
										response.no_comp_attr[i] = e.replace(/^\*/, '');
									  }
									});
									_no_comp_attr = $.merge(_no_comp_attr, response.no_comp_attr.uniq());
								}
								var t = _no_comp_attr.uniq();
								$("#export-debuglist li.attrib").remove();
								if(t != '')
								$("#export-debuglist").html( '<li style="color: red;" class="attrib">Обратите внимание, на то, что атрибуты (свойства) имеют запрещенные символы (запятая и двоеточие), потому на данном сайте данный прайс использовать нельзя. Атрибуты: '+ t.join('; ') +'.</li>' + $("#export-debuglist").html());
							}
							ExportGoFinishUp();
						}
					},
					error: function( response ) {
						ExportGoUpdateStatus( id, false, response );

						if ( exp_product.length && exp_continue ) {
							ExportGo( exp_product.shift() );
						}
						else {
							ExportGoFinishUp();
						}
					},
					dataType: 'json'
				});
			}
			function setClockdebuglist() {
				var totalSec =  totalTime / 1000;
				var d = parseInt( totalSec / 86400 );
				var h = parseInt( totalSec / 3600 ) % 24;
				var m = parseInt( totalSec / 60 ) % 60;
				var s = parseInt(totalSec % 60, 10);
				var h_t = (h%10==1 && h%100!=11) ? ' час ' : ((h%10>=2 && h%10<=4 && (h%100<10 || h%100>=20)) ? ' часа ' : ' часов ' );
				var result = (d > 0 ? d+ " день " : '') + (h >0 ? h + h_t : '') + (m > 0 ? m + " мин и " : '') + s + " сек";
				$('#export-debuglist-up').html('Затраченное время: ' + result);
			}
			$("#export_csv").delegate('.action_export.button', 'click',function(event) {
				ajaxTime = new Date().getTime();
				$('#export-debuglist-up').hide();
				event.preventDefault();
				$('#export-stop').show();
				$('#export-stop').text("<?php echo $this->esc_quotes( __( 'Прервать', 'tcp' ) ); ?>");
				 if($(this).attr('disabled') == 'disabled') return false;
				 $(this).attr('disabled','disabled');
				 exp_product = [<?php echo $ids; ?>];
				 exp_total = exp_product.length;
				 exp_count = 1;
				 exp_percent = 0;
				 exp_successes = 0;
				 exp_errorcountprice = 0;
				 exp_successes_export = 0;
				 exp_errors = 0;
				 exp_failedlist = '';
				 exp_resulttext = '';
				 exp_timestart = new Date().getTime();
				 exp_timeend = 0;
				 exp_totaltime = 0;
				 exp_continue = true;
				 incl = getCookie('str1');
				 tax = getCookie('attr_str1');
				 tax_value = getCookie('attr_str2');
				 ex = getCookie('str');
				 no_post_content = ( $("#no_post_content").is(":checked") ? 1 : 0 );
				 no_post_image = ( $("#no_post_image").is(":checked") ? 1 : 0 );
				 exp_path_image = ( $("#exp_path_image").is(":checked") ? 1 : 0 );
				 post_slug = ( $("#post_slug").is(":checked") ? 1 : 0 );
				 post_id = ( $("#post_id").is(":checked") ? 1 : 0 );
				 for_excel = ( $("#for_excel").is(":checked") ? 1 : 0 );

				$(".saphali-export-process").show('slow'); 
				$.ajax({
					type: 'POST',
					url: ajaxurl,
					data: { action: "import_expoexp_s_get_pr",
						exclude: ex,
						include: incl,
						tax: tax,
						tax_val: tax_value,
					},
					success: function( response ) {
						if ( response !== Object( response ) ) {
							response = new Object;
							response.success = false;
							response.error = "Ошибка";
						} else {
							if(response.number > 0) { $("#export-debug-total_pr").html(response.number);}
							if(response.ids != '') {
								eval('exp_product = ['+response.ids+']; ');
								exp_total = exp_product.length;
							}
						}
						ExportGo( exp_product.shift()); 
					},
					error: function( response ) {
						
					},
					dataType: 'json'
				});
				
			});
			
		});
	// ]]>
	</script>
<?php
		//}

	}
}
class ErrorSupervisor
{
	var $sku;
	public function __construct($sku)
	{
		// регистрация ошибок
		$this->sku = $sku;
		
		set_error_handler(array($this, 'OtherErrorCatcher'));
		
		// перехват критических ошибок
		register_shutdown_function(array($this, 'FatalErrorCatcher'));
		
		// создание буфера вывода
		ob_start();
	}
	
	public function OtherErrorCatcher($errno, $errstr)
	{
		// контроль ошибок:
		// - записать в лог
		return false;
	}
	
	public function FatalErrorCatcher()
	{
		$error = error_get_last();
		if (isset($error))
			if($error['type'] == E_ERROR
				|| $error['type'] == E_PARSE
				|| $error['type'] == E_COMPILE_ERROR
				|| $error['type'] == E_CORE_ERROR)
			{
				switch( $error['type'] ) {
					case E_ERROR: $error['type'] = 'E_ERROR'; break;
					case E_PARSE: $error['type'] = 'E_PARSE'; break;
					case E_COMPILE_ERROR: $error['type'] = 'E_COMPILE_ERROR'; break;
					case E_CORE_ERROR: $error['type'] = 'E_CORE_ERROR'; break;
				}
				ob_end_clean();	// сбросить буфер, завершить работу буфера
				die(json_encode(array($count, $uploaded, $count_['update'], 'success' => false, 'post_id' => 'no', 'debug'=> $error, 'sku' => $this->sku ) ) );
				// контроль критических ошибок:
				// - записать в лог
				// - вернуть заголовок 500
				// - вернуть после заголовка данные для пользователя
			}
			else
			{
				ob_end_flush();	// вывод буфера, завершить работу буфера
			}
		else
		{
			ob_end_flush();	// вывод буфера, завершить работу буфера
		}
	}
}

if(!class_exists("File_FGetCSV"))
include_once (dirname( __FILE__ ) . '/fgetcsv.php');
new CSVLoaderForWoocommerceImp();

?>

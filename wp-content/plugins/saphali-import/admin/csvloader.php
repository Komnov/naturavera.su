<?php

/* Modified for Woocommerce */

//Does this post exist? v. 0.2
//For future should check also slug existance
//set_time_limit(6000);
@ini_set('auto_detect_line_endings', true);
@ini_set("upload_max_filesize","50M");
@ini_set("post_max_size","50M");
@ini_set("max_execution_time","6000");
@ini_set('memory_limit', '-1');

if( isset( $_FILES["upload_file"]["error"] ) && $_FILES["upload_file"]["error"] == 6 ) {
	$r = ini_get('upload_tmp_dir');
	if( !( is_dir($r) || file_exists($r) ) ) {
		$inipath = php_ini_loaded_file();
		echo '<h3 style="color: red; font-weight: normal;">Недоступна временная папка <strong>' . $r . '</strong>, указанная в php.ini';
		if ($inipath) {
			echo ': <strong>' . $inipath . '</strong></h3>';
		}		
	}
}

$local = setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251', 'ru_RU');
$expr_generation_settings = get_option('expr_generation_settings', array());
if( isset( $_REQUEST['wc_load_csv'] ) ) {
	$post['taxonomy'] = isset($_POST['taxonomy']) ? $_POST['taxonomy'] : 'product_cat' ;
	$post['separator'] = isset($_POST['separator']) ? $_POST['separator'] : ';' ;
	$post['titeled'] = isset($_POST['titeled']) ? isset( $_POST['titeled'] ) : 1 ;
	$post['_wc_stock_on'] = isset($_POST['_wc_stock_on']) ? $_POST['_wc_stock_on']: '';
	$post['is_selected_item'] = $_POST['is_selected_item'];
	$post['pre_go'] = $_POST['pre_go'];
	$post['fetch_attachments'] = $_POST['fetch_attachments'];
	if(!update_option('imp_generation_settings', $post ) && !get_option('imp_generation_settings', false) )  add_option('imp_generation_settings', $post);
}
$_expr_generation_settings = get_option('imp_generation_settings', array());

if($_expr_generation_settings) {
		if(isset($_expr_generation_settings['taxonomy']) && 
	   !empty($_expr_generation_settings['taxonomy']) ) $_REQUEST['taxonomy'] = $_expr_generation_settings['taxonomy'];
	   
	if(isset($_expr_generation_settings['separator']) && 
	   !empty($_expr_generation_settings['separator']) ) $_REQUEST['separator'] = $_expr_generation_settings['separator'];
	   
	if(isset($_expr_generation_settings['titeled']) && 
	   !empty($_expr_generation_settings['titeled']) ) $_REQUEST['titeled'] = $_expr_generation_settings['titeled'];
	   
	if(isset($_expr_generation_settings['_wc_stock_on']) && 
	   !empty($_expr_generation_settings['_wc_stock_on']) ) $_REQUEST['_wc_stock_on'] = $_expr_generation_settings['_wc_stock_on'];
	   
   if(isset($_expr_generation_settings['is_selected_item']) && 
	   !empty($_expr_generation_settings['is_selected_item']) ) $_REQUEST['is_selected_item'] = $_expr_generation_settings['is_selected_item'];
	   
   if(isset($_expr_generation_settings['pre_go']) && 
	   !empty($_expr_generation_settings['pre_go']) ) $_POST['pre_go'] = $_expr_generation_settings['pre_go'];
	   
	if(isset($_expr_generation_settings['fetch_attachments']) && 
	   !empty($_expr_generation_settings['fetch_attachments']) ) $_REQUEST['fetch_attachments'] = $_expr_generation_settings['fetch_attachments'];
}
if($expr_generation_settings) {
	if(isset($expr_generation_settings['include']) && 
	   !empty($expr_generation_settings['include']) ) $_COOKIE['str1'] = $expr_generation_settings['include'];
	if(isset($expr_generation_settings['ex']) && 
	   !empty($expr_generation_settings['ex']) ) $_COOKIE['str'] = $expr_generation_settings['ex'];
	if(isset($expr_generation_settings['tax']) && 
	   !empty($expr_generation_settings['tax']) ) $_COOKIE['attr_str1'] = $expr_generation_settings['tax'];
	   if(isset($expr_generation_settings['tax_val']) && 
	   !empty($expr_generation_settings['tax_val']) ) $_COOKIE['attr_str2'] = $expr_generation_settings['tax_val'];
	   
}
function cp1251_to_utf8 ($txt)  {
    return iconv("CP1251", "UTF-8", $txt);
}

class _zip_file_ {
	public $return;
	public $dir;
    public function __construct() {
		if( stripos($_FILES['upload_file']['name'], '.zip') !== false  ) {
			 try {
			if ( isset( $_REQUEST['wc_load_csv'] ) && isset( $_FILES['upload_file'] ) )
			$upload_dir = wp_upload_dir();
			$this->dir = $upload_dir['basedir'] .'/' . time();
			$dir = $this->dir . '/';
			mkdir($dir, 0755, true);
			$file = $_FILES['upload_file']['name'];
			move_uploaded_file( $_FILES['upload_file']['tmp_name'], $dir.$file );
			$zip = new ZipArchive;
			if ($zip->open($dir.$file) === true) {
				$zip->extractTo($dir);
				$zip->close();
			} else throw new Exception(__('Невозможно извлечь файл'));
			$csv_files = glob($dir.'*.csv');
			if (sizeof($csv_files) == 1) $this->return = $csv_files[0];
			else throw new Exception(__('Не найдено csv файла или файлов с таким расширением в каталоге "'. $dir . '" больше одного.'));
        } catch (Exception $e) {
            $this->return = $e->getMessage();
        }
		} else {
			$this->return = $_FILES['upload_file']['tmp_name'];
		}
    }
}
if ( ! function_exists( 'wp_e'.'xist'.'_post_by'.'_title' ) ) {
function wp_exist_post_by_title($title_str) {
	global $wpdb;
	return $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = '%s'", $title_str));
}
}
if ( ! function_exists( 'wp_ex'.'ist_'.'post_b'.'y_sku' ) ) {
function wp_exist_post_by_sku($sku) {
	global $wpdb;
	return $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_sku' AND meta_value = '%s'", $sku));
}
}

$post_type	= isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : 'product';
$separator	= isset( $_REQUEST['separator'] ) ? $_REQUEST['separator'] : ';';
(isset( $_REQUEST['no_utf']  )|| isset( $_REQUEST['wc_load_csv'] )) ? $_SESSION['no_utf8']	= isset( $_REQUEST['no_utf'] ) ? true : false : '';
$titeled	= isset( $_REQUEST['titeled'] );
$taxonomy	= isset( $_REQUEST['taxonomy'] ) ? $_REQUEST['taxonomy'] : 'product_cat';
$hierarchical_multicat = isset( $_REQUEST['hierarchical_multicat'] );
if(isset( $_REQUEST['_wc_stock_on']  )|| isset( $_REQUEST['wc_load_csv'] )) if(isset( $_REQUEST['_wc_stock_on'] )) $_SESSION['_wc_stock_on'] = true; else $_SESSION['_wc_stock_on'] = false;

$wc_stock_on = isset( $_REQUEST['_wc_stock_on'] ) ?  $_SESSION['_wc_stock_on'] : '' ;
$is_selected_item = isset( $_REQUEST['is_selected_item'] )? $_REQUEST['is_selected_item'] : false;

$cat		= isset( $_REQUEST['wc_cat'] ) ? $_REQUEST['wc_cat'] : '';
$wc_status	= isset( $_REQUEST['wc_status'] ) ? $_REQUEST['wc_status'] : '';
$fetch_attachments	= isset( $_REQUEST['fetch_attachments'] ) ? $_REQUEST['fetch_attachments'] : '';
$all_in_one_attr	= isset( $_POST['all_in_one_attr'] ) ? $_POST['all_in_one_attr'] : '';
$all_is_comment	= isset( $_POST['all_is_comment'] ) ? $_POST['all_is_comment'] : '';
$data	= array();
$titles	= array();
if ( isset( $_REQUEST['wc_load_csv'] ) && isset( $_FILES['upload_file'] ) ) {
	
	$is_csv = explode('.', $_FILES['upload_file']['name']);
	preg_match("/csv/i", $is_csv[sizeof($is_csv) - 1] , $_is_csv);

	if(!$_is_csv) {
		preg_match("/zip/i", $is_csv[sizeof($is_csv) - 1] , $is_csv_);
		if($is_csv_) {
			$obj_class = new _zip_file_();
			$_FILES['upload_file']['tmp_name'] = $obj_class->return;
		}
	}
	
	if ( ( $handle = fopen( $_FILES['upload_file']['tmp_name'], 'r' ) ) !== FALSE ) {
		while ( ( $line = fgetcsv($handle, 65536, $separator) ) !== FALSE ) {
			$data[] = $line;	
		}
		
		fclose( $handle );
		if ( $titeled ) {
			$titles = array_shift ( $data );
		} else { 
			for( $i = 0; $i < count( $data[0] ); $i++ )
				$titles[] = 'col_' . $i;
		}
	}
	
	$_SESSION['wc_csv_titles'] = $titles;
	$_SESSION['wc_csv_data'] = $data;
	$_SESSION['fetch_attachments'] = $fetch_attachments;
	$_SESSION['all_in_one_attr'] = $all_in_one_attr;
	$_SESSION['all_is_comment'] = $all_is_comment;
	$_SESSION['custom_field_defs'] = isset($_POST['custom_fild']) ? $_POST['custom_fild'] : '';
	
	if(isset($obj_class) && is_object($obj_class) ) {
		 $files = glob($obj_class->dir.'/*.*');
		 array_map( "unlink", $files );
		 rmdir($obj_class->dir);
	}
	if( isset($_POST['no_import_new_product']) ) {
		update_option( 'no_import_new_product', 1 );
	} else delete_option( 'no_import_new_product' );

}
if( isset( $_POST['current_language_pl'] ) ) {
	$_SESSION['current_language_pl'] = $_REQUEST['current_language_pl'];
}
$no_import_new_product = get_option( 'no_import_new_product', 0 );

?>

<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-product"><br /></div>
	<h2 class="nav-tab-wrapper">
		<a class='nav-tab im nav-tab-active' style="cursor:pointer;" onclick="jQuery('.nav-tab-wrapper a').removeClass('nav-tab-active').filter(this).addClass('nav-tab-active');jQuery('.tab_content').hide();jQuery('#import').show()">Import (Woocommerce)</a>
		<a class='nav-tab ex' style="cursor:pointer;" onclick="jQuery('.nav-tab-wrapper a').removeClass('nav-tab-active').filter(this).addClass('nav-tab-active');jQuery('.tab_content').hide();jQuery('#process_import').hide();jQuery('#export_csv').show()">Export in CSV</a>
	</h2>
	
<div id="import" class="tab_content">
<div style="font-family: 'Times New Roman';font-size: 16px;"><p  style="line-height: 31px;">Колонка "Attachment (Attachment)" и "Thumbnail (Thumbnail)" состоит из пути от <span style="color: red;font-size:18px;">/wp-content/uploads/</span>. 
Например, если ваши картинки находятся в корневой папке IMG, то ее нужно поместить в папку uploads и таким образом URL к картинкам будет таким http://ваш_сайт/wp-content/uploads/IMG/картинка.jpg или http://ваш_сайт/wp-content/uploads/IMG/serg/new/картинка.jpg. Главное корневую папку поместить в нужное место.</p>
<p> Можно также поместить в корень сайта, но для этого нужно в админке вашего сайта <a href="<?php echo site_url();?>/wp-admin/options-media.php">Настройки медиафайлов</a> в параметре "Сохранять файлы в этой папке" указать <span style="color: red;font-size:18px;">/</span> .
</p>
<p>
В том случае, если в шаблоне указан URL к картинкам (не IMG/картинка.jpg, а http://какой-то-сайт/wp-content/uploads/IMG/картинка.jpg), то нужно просто отметить "Скачать и импортировать изображения из вне".
</p>
<hr />
<h3>Технические моменты касательно шаблона</h3>
<p>Скачать шаблон для <a href="<?php echo admin_url('admin-ajax.php'); $sep = strpos(admin_url('admin-ajax.php'), "?") === false ? '?' : '&'; echo $sep; ?>action=saphali_example_csv_php">обычных товаров</a></p>
<p>Скачать шаблон для <a href="<?php echo admin_url('admin-ajax.php'); echo $sep; ?>action=saphali_example_var_csv_php">вариативных товаров</a></p>
<p>Скачать шаблон для <a href="<?php echo admin_url('admin-ajax.php'); echo $sep; ?>action=saphali_example_var_csv_php&variant_static_price">вариативных товаров (цена и изображения для всех вариаций одинаковые)</a></p>

<?php 

global $sitepress;
if(is_object($sitepress)) {
	$this->wpml = $sitepress;
	$default_language = $this->wpml->get_default_language();
	$current_language = $this->wpml->get_current_language();
	$get_active_languages = $this->wpml->get_active_languages();
	echo '<p>Язык по умолчанию: ' . $default_language . ' (' . $get_active_languages[$default_language]["native_name"] . ')' . '</p>';
	//echo '<p>Текущий язык: ' . $current_language . '</p>';
} elseif(class_exists('Polylang')) {
	$default_language = pll_default_language();
	$current_language = pll_current_language();
	if($current_language === false ) {
		$current_language = $default_language;
	}
	$get_active_languages = PLL()->model->get_languages_list();
	echo '<p>Язык по умолчанию: ' . $default_language . ' (' . pll_default_language( 'name' ) . ')' . '</p>';
	// echo '<pre>'; var_dump($polylang->filters->options["default_lang"], $current_language ); echo '</pre>';
}
?>
<?php if (@ini_get('allow_url_fopen') == 1) {
    echo '<p style="color: #0A0;">Директива <strong>allow_url_fopen</strong> включена на этом хосте. Загрузку изображений из вне можно производить.</p>';
} else {
    echo '<p style="color: #A00;">Директива <strong>allow_url_fopen</strong> отключена на этом хосте.  Загрузку изображений из вне <strong>нельзя</strong> производить.</p>';
} 

if( !function_exists("iconv") && !$_SESSION['no_utf8'] ) {
    echo '<p style="color: #A00;">На сервере отсутствует функция <strong>iconv</strong>. Или загружайте файл в кодировке UTF-8 или сделайте функцию iconv доступной.</p>';
}
?>

<?php if(!empty($local)) { ?><p>Используется русская локаль: <?php echo $local;?>.</p> <?php } else { ?>Отсутствует русская локаль. Если тект русский, то он будет зарезаться.<?php } ?>
<p>Обратите внимание, если у вас есть колонка с каким-либо свойством, которое должно отображаться в табе "Характеристики", то на втором этапе импорта (указание типа импотрируемой колонки), выбрать в "Колонки Woocommerce" тип &laquo;Attrib Not Taxonomy (<?php _e( 'Attribs', 'attribs' );?>)&raquo;.</p>
</div>

<ul class="subsubsub">
</ul><!-- subsubsub -->

<div class="clear"></div>

<form method="post" enctype="multipart/form-data">
	<table class="form-table">
	<tbody>
	<tr valign="top">
	<th scope="row">
	</th>
	<td>
		<input name="post_type" id="post_type" type="hidden" value="product" />
	</td>
	</tr>
	<tr valign="top">
	<th scope="row">
		<label for="taxonomy">Таксономия по умолчанию (по умолчанию, Категории товара):</label>
	</th>
	<td>
		<select name="taxonomy" id="taxonomy">
		<?php foreach( get_object_taxonomies( $post_type ) as $taxmy ) : $tax = get_taxonomy( $taxmy );
if (preg_match('/pa_/', $taxmy) == 0)  {?>
		<option value="<?php echo esc_attr( $taxmy );?>"<?php selected( $taxmy, $taxonomy ); ?>><?php echo $tax->labels->name;?></option>
		<?php } ?>
		<?php endforeach;?>
		</select>
	</td>
	</tr>
	<tr valign="top">
	<th scope="row">
		<label for="separator">Разделитель:</label>
	</th>
	<td>
		<input type="text" name="separator" id="separator" value="<?php echo $separator;?>" size="2" maxlenght="4"/>
		<label for="titeled">Титульные колонки в первой строке:</label>
		<input type="checkbox" name="titeled" id="titeled" <?php checked($titeled);?> size="2" maxlenght="4" checked />
	</td>
	</tr>
	<tr valign="top">
	<th scope="row">
		<label for="no_utf">Кодировка UTF-8:</label>
	</th>
	<td>
		<label for="no_utf">Кодировка файла UTF-8:</label>
		<input type="checkbox" name="no_utf" id="no_utf" value="true" <?php if(isset($_SESSION['no_utf8'])) checked($_SESSION['no_utf8'], true);?> size="2" maxlenght="4" />
	</td>
	</tr>

	<tr valign="top">
	<th scope="row">
		<label for="hierarchical_multicat">Иерархические категории:</label>
	</th>
	<td>
		<label for="hierarchical_multicat">Иерархические категории:</label>
		<input type="checkbox" name="hierarchical_multicat" id="hierarchical_multicat" <?php if( isset($_COOKIE['hierarchical_multicat_is_c']) && $_COOKIE['hierarchical_multicat_is_c'] ) { ?> checked="checked"<?php } else checked($hierarchical_multicat); ?> size="2" maxlenght="4"  />
	</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="_wc_stock_on">Учет товара на складе:</label>
		</th>
		<td>
			<label for="_wc_stock_on">Учет товара:</label>
			<input type="checkbox" name="_wc_stock_on" id="_wc_stock_on" <?php  if( isset($_SESSION['_wc_stock_on']) ) checked($_SESSION['_wc_stock_on'], true);?> value="true" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="is_selected_item">Сравнение с текущим товаром (при совпадении будет обновлять товар):</label>
		</th>
		<td>
			<label for="is_selected_item">Сравнивать по:</label>
			<select  name="is_selected_item" id="is_selected_item">
				<option value="" <?php  if($is_selected_item =='' && $is_selected_item !== false) echo 'selected="selected"'; ?>>нет сравнения (импорт)</option>
				<option value="title" <?php  selected( $is_selected_item, 'title' ); ?>>заголовкам</option>
				<option value="sku" <?php if($is_selected_item === false) echo 'selected="selected"'; else selected( $is_selected_item, 'sku' ); ?>>артикулам</option>
			</select>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="is_selected_item">Предварительная обработка перед импортом:</label>
		</th>
		<td>
			<label for="is_selected_item_trash_all_no">
			<input type="radio" name="pre_go" value="" id="is_selected_item_trash_all_no" <?php if(isset($_POST['pre_go']) && $_POST['pre_go'] == '' || !isset($_POST['pre_go'])) echo 'checked="checked"'; ?> /> Ничего не предпринимать</label><br />
			<label for="is_selected_item_trash_all">
			<input type="radio" name="pre_go" value="trash_all" id="is_selected_item_trash_all" <?php if(isset($_POST['pre_go']) && $_POST['pre_go'] == 'trash_all' ) echo 'checked="checked"'; ?> /> Поместить все товары в корзину</label><br />
			<label for="is_selected_item_outofstock_all">
			<input type="radio" name="pre_go" value="outofstock_all" id="is_selected_item_outofstock_all" <?php if(isset($_POST['pre_go']) && $_POST['pre_go'] == 'outofstock_all' ) echo 'checked="checked"'; ?> /> Пометить все товары "Нет в наличии"</label><br /><br />
			<label for="delete_meta_posts_emty_product">
			<input type="checkbox" name="delete_meta_posts_emty_product" value="1" id="delete_meta_posts_emty_product" <?php if(isset($_POST['delete_meta_posts_emty_product']) && $_POST['delete_meta_posts_emty_product'] == '1' ) echo 'checked="checked"'; ?> /> Удалить все мета-поля несвязанные с товарами (осиротевшие мета-поля товара).</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="total_sales_import_new_product">Обработка после импорта</label>
		</th>
		<td>
			<button class="button total_sales_import_new_product">Обработать</button>
			<div class="description"><em>Решить проблему отображения при сортировке по популярности</em></div>
		</td>
	</tr>
	<?php if( isset($get_active_languages) ) { ?>
	<tr valign="top">
		<th scope="row">
			<label for="total_sales_import_new_product">WPML (Язык импортируемых товаров)</label>
		</th>
		<td>
				<?php 
					if(class_exists('Polylang') ) {
						echo '<select name="current_language_pl">';
						echo '<option value=""> По умолчанию </option>';
					foreach($get_active_languages as $code => $value) {
							// if($default_language == $value->slug) continue;
							if($default_language == $value->slug) {
								echo '<option value=""'; selected(
							($current_language != $default_language) && $value->slug == $current_language || isset($_SESSION['current_language_pl']) && $value->slug == $_SESSION['current_language_pl'] , true ); echo '>'. $value->name . '&nbsp;' . $value->flag . '</option>';
							} else {
								$get_active_languages[$default_language]["native_name"] = $value->name;
							echo '<option value="'.$value->slug.'"'; selected(
							($current_language != $default_language) && $value->slug == $current_language || isset($_SESSION['current_language_pl']) && $value->slug == $_SESSION['current_language_pl'] , true ); echo '>'. $value->name . '&nbsp;' . $value->flag . '</option>';
						}	
							
						}	
					}  else {
						echo '<select name="current_language">';
						$current_language = get_option('saphali_current_language_wpml', $current_language);
						 
						foreach($get_active_languages as $code => $value) {
						echo '<option value="'.$code.'"'; selected(
						($current_language != $default_language) && $code == $current_language , true ); echo '>'.$value["native_name"] .' ('. $value["english_name"] .')</option>';
					}
					}
				?>
				
			</select>
			<input name="<?php if( class_exists('Polylang') ) echo 'default_language_pl'; else echo 'default_language'; ?>" type="hidden" value="<?php echo $default_language; ?>" /> 
			<div class="description"><em>Язык, который нужно использовать для импорта товаров, которые нужно связать с уже импортированными товарами, у которых язык по умолчанию (<?php if( class_exists('Polylang') ) echo pll_default_language( 'name' ); else echo $get_active_languages[$default_language]["native_name"]; ?>)</em></div>
			<div class="">Обратите внимание, что данная опция учитывается, если импортируется колонка <strong>RefWpml</strong>, иначе будет импортироваться товар, применяя язык по умолчанию. <br /> 
			Создайте колонку <strong>RefWpml</strong> в файле для импорта для товара имеющий язык, отличный от языка по умолчанию, и укажите в нем <strong>артикул</strong> товара, у которого язык по умолчанию.
			</div>
		</td>
	</tr>
	<?php } ?>
	<tr valign="top">
		<th scope="row">
			<label for="no_import_new_product">Не импортировать новые товары</label>
		</th>
		<td>
			<label for="no_import_new_product">
			<input type="checkbox" value="1" name="no_import_new_product" value="" id="no_import_new_product" <?php if(isset($_POST['no_import_new_product']) && $_POST['no_import_new_product'] == '1' || $no_import_new_product ) echo 'checked="checked"'; ?> /> Товары, которых нет в магазине, но есть в прайсе не импортировать</label>
		</td>
	</tr>
<?php if ( apply_filters( 'import_allow_fetch_attachments', true ) ) : ?>
	<tr valign="top">
		<th scope="row">
			<label for="fetch_attachments"><?php _e( 'Импорт изображений из вне', 'saphali-importer' ); ?>:</label>
		</th>
		<td>
			<p>
				<input type="checkbox" value="1" name="fetch_attachments" id="fetch_attachments" <?php if( isset($_COOKIE['fetch_attachments_is_c']) && $_COOKIE['fetch_attachments_is_c'] ) { ?> checked="checked"<?php } elseif(isset($_SESSION['fetch_attachments'])) checked($_SESSION['fetch_attachments'], 1); ?> />
				<label for="fetch_attachments"><?php _e( 'Скачать и импортировать изображения из вне', 'saphali-importer' ); ?></label>
			</p>
		</td>
	</tr>
<?php endif; ?>

	<tr valign="top">
		<th scope="row">
			<label for="all_in_one_attr"><?php _e( 'Автозаполнение таксономических свойств', 'saphali-importer' ); ?>:</label>
		</th>
		<td>
			<p>
				<input type="checkbox" value="1" name="all_in_one_attr" id="all_in_one_attr" <?php if(isset($_SESSION['all_in_one_attr'])) checked($_SESSION['all_in_one_attr'], 1); if(!isset($_SESSION['all_in_one_attr'])) checked(1, 1); ?> />
				<label for="all_in_one_attr"><?php _e( 'Использовать автозаполнение таксономических свойств', 'saphali-importer' ); ?></label>
			</p>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<label for="all_is_comment"><?php _e( 'Комментарии к товару', 'saphali-importer' ); ?>:</label>
		</th>
		<td>
			<p>
				<input type="checkbox" value="1" name="all_is_comment" id="all_is_comment" <?php if(isset($_SESSION['all_is_comment'])) checked($_SESSION['all_is_comment'], 1); if(!isset($_SESSION['all_is_comment'])) checked(1, 1); ?> />
				<label for="all_is_comment"><?php _e( 'Включить отзывы', 'saphali-importer' ); ?></label>
			</p>
		</td>
	</tr>

	<tr valign="top">
	<th scope="row">
		<label for="upload_file" value=""><?php _e( 'Произвольные поля', 'wc_csvl' );?>:</label>
	</th>
	<td>
		<div id="wc_custum_fild" class="button-secondary">Добавить произвольное поле</div>
		<div id="wc_custum_fild_added"><?php
		
		if(isset($_SESSION['custom_field_defs']) && is_array($_SESSION['custom_field_defs']) ) {
			foreach($_SESSION['custom_field_defs'] as $key => $custom_field ) {
				echo '<div class="item" style="padding:3px"><input name="custom_fild['.$key.'][id]" value="'.$custom_field['id'].'" rel="'.$key.'" /> <span style="color: red" class="button">Удалить</span></div>';
			}
		} else {
			$saph_import_custom_filds = get_option('saph_import_custom_filds');
			if(!$saph_import_custom_filds) $saph_import_custom_filds = array();
			foreach($saph_import_custom_filds as $key => $custom_field ) {
				echo '<div class="item" style="padding:3px"><input name="custom_fild['.$key.'][id]" value="'.$custom_field['id'].'" rel="'.$key.'" /> <span style="color: red" class="button">Удалить</span></div>';
			}
		}
		?>
		</div><br /><span class="button save_custom_filds" >Сохранить произвольные поля</span><span class='process'></span>
	</td>
	</tr>
	<tr valign="top">
	<th scope="row">
		<label for="upload_file" value=""><?php _e( 'file', 'wc_csvl' );?>:</label>
	</th>
	<td>
		<input type="file" name="upload_file" id="upload_file" />
		<?php if(isset($_FILES['upload_file']["error"]) && $_FILES['upload_file']["error"]) {
			echo '<br /><span style="color:red">Ошибка: </span>' . $_FILES['upload_file']["error"];
			if($_FILES['upload_file']["error"] == 6) {

				echo ' Отсутствует временная папка. Ее нужно указать в php.ini в директиве <b>upload_tmp_dir</b>.'; 
				if(!empty($r)) {
					echo ' Сейчас в данной директиве указана папка: ' . $r;
				}
			}

		} ?>
	</td>
	</tr>
	</tbody>
	</table>
	
	<?php $bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
	global $wp_version;
	if( version_compare( $wp_version, '3.6', '<' ))
	$size = wp_convert_bytes_to_hr( $bytes );
	else
	$size = size_format( $bytes );
	
	?>
	<p><?php printf( __('Размер файла: не более <strong>%s</strong>' ), $size ); ?>. Формат файла: <strong>zip</strong> или <strong>csv</strong></p>
	<span class="submit"  style="position:relative"><input type="submit" name="wc_load_csv" id="wc_load_csv" value="<?php _e( 'Загрузить', 'wc_csvl' );?>" class="button-primary" /></span>
	<span>Это действие поможет вам проверить, является ли файл корректным. Отображаются только 2 строки.</span>
</form>
<script>
jQuery(document).ready(function () {
	jQuery("span.save_custom_filds").hide();
	jQuery("span.submit input#wc_load_products_from_csv").bind( 'click', function(event) {
		event.preventDefault();
		var this_el = jQuery(this);
		this_el.unbind(event.preventDefault());
		var pre_go  = jQuery("input[name='pre_go']:checked").val();
		var delete_meta_posts_emty_product  = jQuery("input[name='delete_meta_posts_emty_product']:checked").val();
		if( pre_go === '' && delete_meta_posts_emty_product === '') gowc_load_products_from_csv();
		else {
			jQuery.getJSON(
				'<?php echo admin_url('admin-ajax.php');?>?action=outofstock_trash_all&security=<?php echo wp_create_nonce( "save-pre_go" );?>&pre_go='+pre_go+'&delete_meta_posts_emty_product='+delete_meta_posts_emty_product,
				function(data) {
					// Check money.js has finished loading:
					if ( typeof data !== "undefined" ) {
						gowc_load_products_from_csv();
					} else {
						
					}
				}
			);
		}
	});
	jQuery("table.form-table tr").delegate("button.total_sales_import_new_product", 'click',function(event) {
		event.preventDefault();
		var _this = jQuery(this);
		if( _this.attr('disabled') == 'disabled' ) return false;
		
		_this.attr('disabled', 'disabled'); 
		_this.css('cursor', 'wait'); 
		jQuery.getJSON(
			'<?php echo admin_url('admin-ajax.php');?>?action=outofstock_trash_all&security=<?php echo wp_create_nonce( "save-pre_go" );?>&pre_go=total_sales',
			function(data) {
				// Check money.js has finished loading:
				if ( typeof data !== "undefined" ) {
					if( data > 0 )
						alert('Количество товаров, в которые внесены изменения: ' + data + '.' );
					else
						alert('Нет товаров, которые нуждаются в обработке. ' + "\n" +'Все в порядке, на сайте нет данной проблемы.' );
				}
				_this.attr('disabled', false); 
				_this.css('cursor', 'pointer'); 
			}
		);
	});
	jQuery("table.form-table tr").delegate("#hierarchical_multicat", 'click',function() {
		if(jQuery(this).is(":checked")) {
			setCookie_imp('hierarchical_multicat_is_c', 1, 31);
			jQuery("tr.model, tr.vendor").show('slow');
		} else {
			setCookie_imp('hierarchical_multicat_is_c', 0, 31);
			jQuery("tr.model, tr.vendor").hide('slow');
		}
	});
	jQuery("table.form-table tr").delegate("#fetch_attachments", 'click',function() {
		if(jQuery(this).is(":checked")) {
			setCookie_imp('fetch_attachments_is_c', 1, 31);
		} else {
			setCookie_imp('fetch_attachments_is_c', 0, 31);
		}
	});
	<?php if( !isset($_COOKIE['hierarchical_multicat_is_c']) ) { ?>
		jQuery("table.form-table tr #hierarchical_multicat").trigger('click');
	<?php } ?>
	<?php if( !isset($_COOKIE['fetch_attachments_is_c']) ) { ?>
		jQuery("table.form-table tr #fetch_attachments").trigger('click');
	<?php } ?>
});
function gowc_load_products_from_csv() {
	jQuery("span.submit input#wc_load_products_from_csv").unbind('click');
	jQuery("span.submit input#wc_load_products_from_csv").click();
}
var custom_fild = [];

jQuery("table.form-table").delegate("#wc_custum_fild", 'click', function () {
	var num = 0;
	jQuery("#wc_custum_fild_added input").each(function() {
		if( num < parseInt(jQuery(this).attr('rel'), 10)) num = parseInt(jQuery(this).attr('rel'), 10);
	});
	num++;
	jQuery("#wc_custum_fild_added").append('\
		<div class="item" style="padding:3px"><input name="custom_fild['+num+'][id]" value="" rel="'+num+'" /> <span style="color: red" class="button">Удалить</span></div>	\
	');
	
	jQuery("span.save_custom_filds").show();
	
	jQuery("#wc_custum_fild_added").delegate('span', 'click', function () {
		jQuery(this).parent().remove();
	});
});

jQuery(".save_custom_filds").click( function (event) {
	custom_fild = [];
	event.preventDefault();
	jQuery(".save_custom_filds").parent().find('span.process').text('обработка...');
	jQuery("#wc_custum_fild_added input").each(function(i,e) {
		var id = 'id';
		if(jQuery(this).val() != '')
		custom_fild[i] = jQuery(this).val();
	});
	var default_valute = jQuery(this).val();
	jQuery.getJSON(
		'<?php echo admin_url('admin-ajax.php');?>?action=saph_import_woocommerce_ajax_save_custom_filds&security=<?php echo wp_create_nonce( "save-filds" );?>&filds='+custom_fild,
		function(data) {
				// Check money.js has finished loading:
				if ( typeof data !== "undefined" ) {
					if (data === true) {
						jQuery(".save_custom_filds").parent().find('span.process').text('');
					}
				} 
		}
	);
});
jQuery("#wc_custum_fild_added").delegate('span', 'click', function () {
	jQuery("span.save_custom_filds").show();
	jQuery(this).parent().remove();
});
	function setCookie_imp (name, value, expires, path, domain, secure) {
	  var date = new Date( new Date().getTime() + expires * 1000 * 3600 * 24 );
      document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + date.toUTCString() : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
	}
</script>
<?php

 include_once( SAPHALI_PLUGIN_DIR_PATH_IMP . 'saphali-plugins.php');

?>
</div>
<?php if ( isset( $_REQUEST['wc_load_products_from_csv'] ) && isset( $_SESSION['wc_csv_titles'] ) ) {
	$this->process_import();
} do_action("admin_headd"); ?>
 	<div id="export_csv" class="tab_content" style="display:none;">
	<p>Экспортировать в формат CSV</p>
	<button class="action_export button">Начать</button>
 
	<button id="export-stop" style="display:none;margin-left: 20px;" class="button">Прервать</button>
	<?php
	if (file_exists( SAPHALI_PLUGIN_DIR_PATH_IMP. 'export.csv' )) {
		echo '<br /><br /><a style="display: block;" href="'. admin_url('admin-ajax.php') . '?action=action_expoexp_s_end_export&' . time() . '">Скачать текущий CSV</a><p>Ссылка: ';
		echo SAPHALI_PLUGIN_DIR_URL_IMP . "export.csv</p>";
	}
 echo '<div class="saphali-export-process" style="display:none;">'; $this->process_export(); echo '</div>';
 global $wc_product_attributes;
	?>
	<div id="export-debuglist-up"></div>
	<div class="wrap woocommerce">
	<form id="mainforfm" action="" method="post">
		<table class="form-table">
				<tr valign="top">
					<th scope="row" class="titledesc">Возможность генерации файла</th>
					<td class="forminp"><?php
				if ( $handle = @fopen( SAPHALI_PLUGIN_DIR_PATH_IMP. 'export.csv', 'a' ) )
					echo '<mark class="yes">' . __( 'Есть', 'woocommerce' ) . '</mark>';
				else
					echo '<mark class="error">' . __( 'Нет, т.к. нет возможности в каталоге (<code>wp-content/plugins/saphali-import/</code>) создать файл', 'woocommerce' ) . '</mark>';
				if($handle) fclose($handle);
				$exp_no_post_content = isset( $_COOKIE['exp_no_post_content']) ?  $_COOKIE['exp_no_post_content'] : 0;
				$exp_no_image = isset( $_COOKIE['exp_no_post_image']) ?  $_COOKIE['exp_no_post_image'] : 0;
				$exp_path_image = isset( $_COOKIE['exp_path_post_image']) ?  $_COOKIE['exp_path_post_image'] : 1;
				$exp_post_slug = isset($_COOKIE['exp_post_slug']) ? $_COOKIE['exp_post_slug'] : 0;
				$exp_post_id = isset($_COOKIE['exp_post_id']) ? $_COOKIE['exp_post_id'] : 0;
			?></td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">Исключить из прайса</th>
					<td class="forminp">
					<input type="checkbox" <?php if($exp_no_post_content) echo 'checked="checked"'; ?> id="no_post_content" name="post_content" value="no" /> <label for="no_post_content">Подробное описание</label> <br />
					<input type="checkbox" <?php if($exp_no_image) echo 'checked="checked"'; ?> id="no_post_image" name="post_image" value="no" /> <label for="no_post_image">Изображения</label> 
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">Включить в прайс</th>
					<td class="forminp">
					<input type="checkbox" <?php if($exp_post_slug) echo 'checked="checked"'; ?> id="post_slug" name="post_slug" value="1" /> <label for="post_slug">Ярлык (Slug)</label><br />
					<input type="checkbox" <?php if($exp_post_id) echo 'checked="checked"'; ?> id="post_id" name="post_id" value="1" /> <label for="post_id">ID товара</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">Выгрузить для Excel</th>
					<td class="forminp">
					<input type="checkbox" id="for_excel" name="for_excel" value="yes" /> <label for="for_excel">для Excel</label> <br />
					<span class="description"> Позволяет выгрузить прайс так, чтобы при открытии в Эксель, он не преобразовывал значения некоторых ячеек. Например, чтобы <strong>1.30</strong> не стало <strong>янв.30</strong> или <strong>05-5030</strong> не стало <strong>май.30</strong>
</span>
					</td>
				</tr>
				
				<tr valign="top" class="single_select_page">
					<th scope="row" class="titledesc">Выберите категории </th>
					<td class="forminp">
					<input type="hidden" name="product_category_shortcode_setting_save" value="1" />
					<select id="product_category_shortcode" name="product_category_shortcode[]" class="chosen_select" style="width:180px" multiple="multiple" data-placeholder="<?php _e( 'Все категории', 'woocommerce' ); ?>">
						<?php
							$category_ids = array_map( 'trim', explode( ',', $_COOKIE['str1'] ) );
							$count = get_option( 'product_category_shortcode_count_product', '' );
							$checked = get_option( 'product_category_shortcode_featured_product', 0 );
							$checked_only_cat = get_option( 'product_category_shortcode_only_category', 0 );
							$categories = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
							if ( $categories ) foreach ( $categories as $cat )
								echo '<option value="' . esc_attr( $cat->term_id ) . '"' . selected( in_array( $cat->term_id, $category_ids ), true, false ) . '>' . esc_html( $cat->name ) . '</option>';
						?>
					</select> 
					</td>
				</tr>
				<tr valign="top" class="single_select_page">
					<th scope="row" class="titledesc">Исключить категории </th>
					<td class="forminp">
					<input type="hidden" name="product_category_shortcode_setting_save" value="1" />
					<select id="product_category_shortcode_ex" name="product_category_shortcode_ex[]" class="chosen_select" multiple="multiple" style="width:180px" data-placeholder="<?php _e( 'Все категории', 'woocommerce' ); ?>">
						<?php
							$_category_ids = array_map( 'trim', explode( ',', $_COOKIE['str'] ) );
							$_categories = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
							if ( $_categories ) foreach ( $_categories as $cat )
								echo '<option value="' . esc_attr( $cat->term_id ) . '"' . selected( in_array( $cat->term_id, $_category_ids ), true, false ) . '>' . esc_html( $cat->name ) . '</option>';
						?>
					</select> 
					</td>
				</tr>
				<tr valign="top" class="single_select_page">
					<th scope="row" class="titledesc">Включить в прайс только товары со свойством с заданными значениями</th>
					<td class="forminp">
					<select id="product_category_shortcode_tax" name="product_category_shortcode_tax" class="chosen_select" style="width:180px" data-placeholder="<?php _e( 'Выберите свойство', 'woocommerce' ); ?>">
					<option value="">Выбрать...</option>
						<?php
							$tx_ids = array_map( 'trim', explode( ',', $_COOKIE['attr_str1'] ) );
			
							foreach ( $wc_product_attributes as $tax => $attribute ) {
								echo '<option value="' . esc_attr( $tax ) . '" ' . selected( in_array($tax, $tx_ids), true, false) . '  >' . esc_html( trim($attribute->attribute_label) ) . '</option>';
							}
						?>
					</select> 
					<div id="value_attr"></div>
					</td>
				</tr>
				<tr valign="top" class="single_select_page exp_path_image">
					<th scope="row" class="titledesc">Изображения</th>
					<td class="forminp">
					<input type="checkbox" <?php if($exp_path_image) echo 'checked="checked"'; ?> id="exp_path_image" name="exp_path_image" value="yes" /> <label for="exp_path_image">Выгружать относительный путь к изображению</label>
					</td>
				</tr>
				<tr valign="top" class="single_select_page">
					<th scope="row" class="titledesc">Выгружать также произвольное поля </th>
						<td>
							<div id="wc_custum_fild_exp" class="button-secondary">Добавить произвольное поле</div>
							<div id="wc_custum_fild_added_exp"><?php
							
							$_saph_import_custom_filds = get_option('saph_export_custom_filds');
							
							$saph_import_custom_filds_name = array( '_weight' => 'Вес', '_height' => 'Высота', '_width' => 'Ширина', '_length' => 'Длина');
							if(! is_array($_saph_import_custom_filds) ) $_saph_import_custom_filds = array(array( 'id' => '_weight'), array( 'id' => '_height'), array( 'id' => '_width'), array( 'id' => '_length'));
							foreach($_saph_import_custom_filds as $key => $custom_field ) {
								echo '<div class="item" style="padding:3px"><input name="custom_fild['.$key.'][id]" value="'.$custom_field['id'].'" rel="'.$key.'" /> '. (isset($saph_import_custom_filds_name[$custom_field['id']]) ? "(" . $saph_import_custom_filds_name[ $custom_field['id'] ] . ")" : '' ) .' <span style="color: red" class="button">Удалить</span></div>';
							}
							?>
							</div><br /><span class="button save_custom_filds_exp" >Сохранить произвольные поля</span><span class='process'></span>
						</td>
				</tr>
				
				<tr valign="top" class="single_select_page">
				<td>

<script type='text/javascript' src='<?php echo SAPHALI_PLUGIN_DIR_URL_IMP . 'chosen/chosen.jquery.js'; ?>'></script>
<link rel='stylesheet' href='<?php echo SAPHALI_PLUGIN_DIR_URL_IMP . 'chosen/chosen.css'; ?>' type='text/css' media='all' />

<style>
td mark.yes {
    color: #7AD03A;
}
#product_ex_select {width: 350px;}
td mark.error {
    color: #AA0000;
}
button.on, button.off {cursor: pointer;}
td mark {
    background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
}
button.off {
    color: #C93D3D;
    text-shadow: 0 0 1px;
}
button.on {
    color: #178031;
    text-shadow: 0 0 1px;
}
div#export-debuglist-up {display: none;}
</style>
<script>
var str1, attr_str1, attr_str2, str, _product_category_edit, _product_vendor_edit, _product_model_edit, _product_currency_ex,_product_currency_UAH, _product_currency_USD, _product_currency_USD,search_products_nonce = '<?php echo wp_create_nonce("search-products"); ?>', ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
jQuery("select#product_category_shortcode, select#product_category_shortcode_tax, select#product_category_shortcode_ex").chosen();	

jQuery(function($){
	var custom_fild = [];
	jQuery("#wc_custum_fild_added_exp").delegate('span', 'click', function () {
		jQuery("span.save_custom_filds_exp").show();
		jQuery(this).parent().remove();
	});
	jQuery("table.form-table").delegate("#wc_custum_fild_exp", 'click', function () {
		var num = 0;
		jQuery("#wc_custum_fild_added_exp input").each(function() {
			if( num < parseInt(jQuery(this).attr('rel'), 10)) num = parseInt(jQuery(this).attr('rel'), 10);
		});
		num++;
		jQuery("#wc_custum_fild_added_exp").append('\
			<div class="item" style="padding:3px"><input name="custom_fild['+num+'][id]" value="" rel="'+num+'" /> <span style="color: red" class="button">Удалить</span></div>	\
		');
		
		jQuery("span.save_custom_filds_exp").show();
		
		jQuery("#wc_custum_fild_added_exp").delegate('span', 'click', function () {
			jQuery(this).parent().remove();
		});
	});

	jQuery(".save_custom_filds_exp").click( function (event) {
		custom_fild = [];
		event.preventDefault();
		jQuery(".save_custom_filds_exp").parent().find('span.process').text('обработка...');
		jQuery("#wc_custum_fild_added_exp input").each(function(i,e) {
			var id = 'id';
			if(jQuery(this).val() != '')
			custom_fild[i] = jQuery(this).val();
		});
		var default_valute = jQuery(this).val();
		jQuery.getJSON(
			'<?php echo admin_url('admin-ajax.php');?>?action=saph_export_woocommerce_ajax_save_custom_filds&security=<?php echo wp_create_nonce( "save-filds" );?>&filds='+custom_fild,
			function(data) {
					// Check money.js has finished loading:
					if ( typeof data !== "undefined" ) {
						if (data === true) {
							jQuery(".save_custom_filds_exp").parent().find('span.process').text('');
						}
					} 
			}
		);
	});
	//_product_vendor_edit = getCookie('product_vendor_edit');
	
	$("select#product_category_shortcode").change(function () {
		var str1 = "";
		$("select#product_category_shortcode option:selected").each(function () {
		str1 += $(this).attr('value') + ",";
		});
		str1 =str1.replace(/,$/, '');
		setCookie('str1', str1, 31*12);

	}).trigger('change');
	$("select#product_category_shortcode_tax").change(function () {
		var attr_str1 = "";
		$("select#product_category_shortcode_tax option:selected").each(function () {
		attr_str1 += $(this).attr('value') + ",";
		});
		attr_str1 =attr_str1.replace(/,$/, '');
		setCookie('attr_str1', attr_str1, 31*12);
		var select = '';
		if(attr_str1) {
			jQuery("#value_attr").html('Обработка...');
			jQuery.getJSON(
				'<?php echo admin_url('admin-ajax.php');?>?action=saph_export_woocommerce_action_tax&security=<?php echo wp_create_nonce( "save-tax" );?>&attr='+attr_str1,
				function(data) {
					// Check money.js has finished loading:
					if ( typeof data !== "undefined" ) {
						var attr_str_2 = getCookie('attr_str2');
						if(attr_str_2 == null) {
							<?php if(isset($_COOKIE['attr_str2']) && !empty($_COOKIE['attr_str2'])) {
							   ?>
							   attr_str_2 = '<?php echo $_COOKIE['attr_str2']; ?>';
							   <?php
						   } ?>
						}
						jQuery.each(data, function(i,e){
							if(attr_str_2 == null) attr_str_2 = '';
							var tmp_attr_str_2 = attr_str_2.split(',');
							if(tmp_attr_str_2 == null) tmp_attr_str_2 = [];
							var tmp_tid = e.term_id + '';
							if(jQuery.inArray( tmp_tid, tmp_attr_str_2 ) != -1 )
							 select = select + '<option value="' + e.term_id + '" selected="selected">' + e.name + '</option>';
							else
							 select = select + '<option value="' + e.term_id + '">' + e.name + '</option>';
						});
					}
					if(select) {
						select = '<select  multiple="multiple" id="product_category_shortcode_tax_value" name="product_category_shortcode_tax_value" class="chosen_select" style="width:180px" data-placeholder="<?php _e( 'Выберите значения', 'woocommerce' ); ?>">' + select + '</select>';
						jQuery("#value_attr").html(select);
						jQuery("select#product_category_shortcode_tax_value").chosen();
					} else {
						jQuery("#value_attr").html('Нет у свойства значений');
					}
				}
			);			
		}

	}).trigger('change');
	$('body').delegate("select#product_category_shortcode_tax_value", 'change', function () {
		var attr_str2 = "";
		$("select#product_category_shortcode_tax_value option:selected").each(function () {
		attr_str2 += $(this).attr('value') + ",";
		});
		attr_str2 =attr_str2.replace(/,$/, '');
		setCookie('attr_str2', attr_str2, 31*12);
	});
	$("select#product_category_shortcode_ex").change(function () {
		var str = "";
		$("select#product_category_shortcode_ex option:selected").each(function () {
		str += $(this).attr('value')+ ",";
		});
		str =str.replace(/,$/, '');
		setCookie('str', str, 31*12);
	}).trigger('change');
	$("#no_post_content").click( function() {
		if( $(this).is(":checked") ) {
			setCookie('exp_no_post_content', 1, 31*12);
		} else {
			setCookie('exp_no_post_content', 0, 31*12);
		}
	});
	$("#no_post_image").click( function() {
		if( $(this).is(":checked") ) {
			setCookie('exp_no_post_image', 1, 31*12);
			$("#exp_path_image").closest('tr').hide('slow');
		} else {
			setCookie('exp_no_post_image', 0, 31*12);
			$("#exp_path_image").closest('tr').show('slow');
		}
	});
	$("#exp_path_image").click( function() {
		if( $(this).is(":checked") ) {
			setCookie('exp_path_post_image', 1, 31*12);
		} else {
			setCookie('exp_path_post_image', 0, 31*12);
		}
	});
	$("#post_slug").click( function() {
		if( $(this).is(":checked") ) {
			setCookie('exp_post_slug', 1, 31*12);
		} else {
			setCookie('exp_post_slug', 0, 31*12);
		}
	});
	$("#post_id").click( function() {
		if( $(this).is(":checked") ) {
			setCookie('exp_post_id', 1, 31*12);
		} else {
			setCookie('exp_post_id', 0, 31*12);
		}
	});
});
	function getCookie(name) {
	var cookie = " " + document.cookie;
	var search = " " + name + "=";
	var setStr = null;
	var offset = 0;
	var end = 0;
	if (cookie.length > 0) {
		offset = cookie.indexOf(search);
		if (offset != -1) {
			offset += search.length;
			end = cookie.indexOf(";", offset)
			if (end == -1) {
				end = cookie.length;
			}
			setStr = unescape(cookie.substring(offset, end));
		}
	}
	return(setStr);
	}
	function setCookie (name, value, expires, path, domain, secure) {
	  var date = new Date( new Date().getTime() + expires * 1000 * 3600 * 24 );
      document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + date.toUTCString() : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
	}
				</script>
				</td>
				</tr>
		</table>
	</form>
</div>
<script>

</script>
</div>
<?php do_action ('admin_head_imp'); ?>
</div>


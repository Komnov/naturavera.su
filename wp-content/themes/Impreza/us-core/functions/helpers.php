<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

if ( ! function_exists( 'us_prepare_icon_tag' ) ) {
	/**
	 * Prepare a proper icon tag from user's custom input
	 *
	 * @param {String} $icon
	 *
	 * @return mixed|string
	 */
	function us_prepare_icon_tag( $icon ) {
		$icon = apply_filters( 'us_icon_class', $icon );
		$icon_arr = explode( '|', $icon );
		if ( count( $icon_arr ) != 2 ) {
			return '';
		}

		$icon_arr[1] = strtolower( sanitize_text_field( $icon_arr[1] ) );
		if ( $icon_arr[0] == 'material' ) {
			$icon_tag = '<i class="material-icons">' . str_replace( array( ' ', '-' ), '_', $icon_arr[1] ) . '</i>';
		} else {
			if ( substr( $icon_arr[1], 0, 3 ) == 'fa-' ) {
				$icon_tag = '<i class="' . $icon_arr[0] . ' ' . $icon_arr[1] . '"></i>';
			} else {
				$icon_tag = '<i class="' . $icon_arr[0] . ' fa-' . $icon_arr[1] . '"></i>';
			}
		}

		return apply_filters( 'us_icon_tag', $icon_tag );
	}
}

if ( ! function_exists( 'us_locate_file' ) ) {
	/**
	 * Search for some file in child theme, in parent theme and in common folder
	 *
	 * @param string $filename Relative path to filename with extension
	 * @param bool $all List an array of found files
	 *
	 * @return mixed Single mode: full path to file or FALSE if no file was found
	 * @return array All mode: array or all the found files
	 */
	function us_locate_file( $filename, $all = FALSE ) {
		global $us_template_directory, $us_stylesheet_directory, $us_files_search_paths, $us_file_paths;
		if ( ! isset( $us_files_search_paths ) ) {
			$us_files_search_paths = array();
			if ( defined( 'US_THEMENAME' ) ) {
				if ( is_child_theme() ) {
					// Searching in child theme first
					$us_files_search_paths[] = trailingslashit( $us_stylesheet_directory );
				}
				// Parent theme
				$us_files_search_paths[] = trailingslashit( $us_template_directory );
				// The common folder with files common for all themes
				$us_files_search_paths[] = $us_template_directory . '/common/';
			}

			if ( defined( 'US_CORE_DIR' ) ) {
				$us_files_search_paths[] = US_CORE_DIR;
			}
			// Can be overloaded if you decide to overload something from certain plugin
			$us_files_search_paths = apply_filters( 'us_files_search_paths', $us_files_search_paths );
		}
		if ( ! $all ) {
			if ( ! isset( $us_file_paths ) ) {
				$us_file_paths = apply_filters( 'us_file_paths', array() );
			}
			$filename = untrailingslashit( $filename );
			if ( ! isset( $us_file_paths[ $filename ] ) ) {
				$us_file_paths[ $filename ] = FALSE;
				foreach ( $us_files_search_paths as $search_path ) {
					if ( file_exists( $search_path . $filename ) ) {
						$us_file_paths[ $filename ] = $search_path . $filename;
						break;
					}
				}
			}

			return $us_file_paths[ $filename ];
		} else {
			$found = array();

			foreach ( $us_files_search_paths as $search_path ) {
				if ( file_exists( $search_path . $filename ) ) {
					$found[] = $search_path . $filename;
				}
			}

			return $found;
		}
	}
}

if ( ! function_exists( 'us_load_template' ) ) {
	/**
	 * Load some specified template and pass variables to it's scope.
	 *
	 * (!) If you create a template that is loaded via this method, please describe the variables that it should receive.
	 *
	 * @param string $template_name Template name to include (ex: 'templates/form/form')
	 * @param array $vars Array of variables to pass to a included templated
	 */
	function us_load_template( $template_name, $vars = NULL ) {

		// Searching for the needed file in a child theme, in the parent theme and, finally, in the common folder
		$file_path = us_locate_file( $template_name . '.php' );

		// Template not found
		if ( $file_path === FALSE ) {
			do_action( 'us_template_not_found:' . $template_name, $vars );

			return;
		}

		$vars = apply_filters( 'us_template_vars:' . $template_name, (array) $vars );
		if ( is_array( $vars ) AND count( $vars ) > 0 ) {
			extract( $vars, EXTR_SKIP );
		}

		do_action( 'us_before_template:' . $template_name, $vars );

		include $file_path;

		do_action( 'us_after_template:' . $template_name, $vars );
	}
}

if ( ! function_exists( 'us_get_template' ) ) {
	/**
	 * Get some specified template output with variables passed to it's scope.
	 *
	 * (!) If you create a template that is loaded via this method, please describe the variables that it should receive.
	 *
	 * @param string $template_name Template name to include (ex: 'templates/form/form')
	 * @param array $vars Array of variables to pass to a included templated
	 *
	 * @return string
	 */
	function us_get_template( $template_name, $vars = NULL ) {
		ob_start();
		us_load_template( $template_name, $vars );

		return ob_get_clean();
	}
}

if ( ! function_exists( 'us_get_option' ) ) {
	/**
	 * Get theme option or return default value
	 *
	 * @param string $name
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	function us_get_option( $name, $default_value = NULL ) {
		if ( function_exists( 'usof_get_option' ) ) {
			return usof_get_option( $name, $default_value );
		} else {
			return $default_value;
		}

	}
}

/**
 * @var $us_query array Allows to use different global $wp_query in different context safely
 */
$us_wp_queries = array();

if ( ! function_exists( 'us_open_wp_query_context' ) ) {
	/**
	 * Opens a new context to use a new custom global $wp_query
	 *
	 * (!) Don't forget to close it!
	 */
	function us_open_wp_query_context() {
		if ( is_array( $GLOBALS ) AND isset( $GLOBALS['wp_query'] ) ) {
			array_unshift( $GLOBALS['us_wp_queries'], $GLOBALS['wp_query'] );
		}
	}
}

if ( ! function_exists( 'us_close_wp_query_context' ) ) {
	/**
	 * Closes last context with a custom
	 */
	function us_close_wp_query_context() {
		if ( isset( $GLOBALS['us_wp_queries'] ) AND count( $GLOBALS['us_wp_queries'] ) > 0 ) {
			$GLOBALS['wp_query'] = array_shift( $GLOBALS['us_wp_queries'] );
			wp_reset_postdata();
		} else {
			// In case someone forgot to open the context
			wp_reset_query();
		}
	}
}

if ( ! function_exists( 'us_add_to_page_block_ids' ) ) {
	/**
	 * Opens a new page block context
	 *
	 */
	function us_add_to_page_block_ids( $page_block_id = NULL ) {

		global $us_page_block_ids;
		if ( empty( $us_page_block_ids ) ) {
			$us_page_block_ids = array();
		}
		if ( $page_block_id != NULL ) {
			array_unshift( $us_page_block_ids, $page_block_id );
		}

	}
}

if ( ! function_exists( 'us_remove_from_page_block_ids' ) ) {
	/**
	 * Closes last page_block context
	 */
	function us_remove_from_page_block_ids() {

		global $us_page_block_ids;

		return array_shift( $us_page_block_ids );
	}
}

if ( ! function_exists( 'us_arr_path' ) ) {
	/**
	 * Get a value from multidimensional array by path
	 *
	 * @param array $arr
	 * @param string|array $path <key1>[.<key2>[...]]
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	function us_arr_path( &$arr, $path, $default = NULL ) {
		$path = is_string( $path ) ? explode( '.', $path ) : $path;
		foreach ( $path as $key ) {
			if ( ! is_array( $arr ) OR ! isset( $arr[ $key ] ) ) {
				return $default;
			}
			$arr = &$arr[ $key ];
		}

		return $arr;
	}
}

if ( ! function_exists( 'us_implode_atts' ) ) {
	/**
	 * Converts an array to a attributes string
	 *
	 * @param array $params Parameter Array
	 * @param string $separator Separator between parameters
	 * @return string
	 */
	function us_implode_atts( $params = array(), $separator = ' ' ) {
		$output = array();
		foreach ( $params as $key => $value ) {
			$output[] = sprintf( '%s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}

		return implode( $separator, $output );
	}
}

if ( ! function_exists( 'us_config' ) ) {
	/**
	 * Load and return some specific config or it's part
	 *
	 * @param string $path <config_name>[.<key1>[.<key2>[...]]]
	 *
	 * @oaram mixed $default Value to return if no data is found
	 *
	 * @return mixed
	 */
	function us_config( $path, $default = NULL, $reload = FALSE ) {
		global $us_template_directory;
		// Caching configuration values in a inner static value within the same request
		static $configs = array();
		// Defined paths to configuration files
		$config_name = strtok( $path, '.' );
		if ( ! isset( $configs[ $config_name ] ) OR $reload ) {
			$config_paths = array_reverse( us_locate_file( 'config/' . $config_name . '.php', TRUE ) );
			if ( empty( $config_paths ) ) {
				if ( WP_DEBUG ) {
					// TODO rework this check for correct plugin activation
					//wp_die( 'Config not found: ' . $config_name );
				}
				$configs[ $config_name ] = array();
			} else {
				us_maybe_load_theme_textdomain();
				// Parent $config data may be used from a config file
				$config = array();
				foreach ( $config_paths as $config_path ) {
					$config = require $config_path;
					// Config may be forced not to be overloaded from a config file
					if ( isset( $final_config ) AND $final_config ) {
						break;
					}
				}
				$configs[ $config_name ] = apply_filters( 'us_config_' . $config_name, $config );
			}
		}

		$path = substr( $path, strlen( $config_name ) + 1 );
		if ( $path == '' ) {
			return $configs[ $config_name ];
		}

		return us_arr_path( $configs[ $config_name ], $path, $default );
	}
}

if ( ! function_exists( 'us_get_intermediate_image_size' ) ) {
	/**
	 * Get image size information as an array
	 *
	 * @param string $size_name
	 *
	 * @return array
	 */
	function us_get_intermediate_image_size( $size_name ) {
		global $_wp_additional_image_sizes;
		if ( isset( $_wp_additional_image_sizes[ $size_name ] ) ) {
			// Getting custom image size
			return $_wp_additional_image_sizes[ $size_name ];
		} else {
			// Getting standard image size
			return array(
				'width' => get_option( "{$size_name}_size_w" ),
				'height' => get_option( "{$size_name}_size_h" ),
				'crop' => get_option( "{$size_name}_crop" ),
			);
		}
	}
}

if ( ! function_exists( 'us_pass_data_to_js' ) ) {
	/**
	 * Transform some variable to elm's onclick attribute, so it could be obtained from JavaScript as:
	 * var data = elm.onclick()
	 *
	 * @param mixed $data Data to pass
	 *
	 * @return string Element attribute ' onclick="..."'
	 */
	function us_pass_data_to_js( $data ) {
		return ' onclick=\'return ' . htmlspecialchars( json_encode( $data ), ENT_QUOTES, 'UTF-8' ) . '\'';
	}
}

if ( ! function_exists( 'us_maybe_get_post_json' ) ) {
	/**
	 * Try to get variable from JSON-encoded post variable
	 *
	 * Note: we pass some params via json-encoded variables, as via pure post some data (ex empty array) will be absent
	 *
	 * @param string $name $_POST's variable name
	 *
	 * @return array
	 */
	function us_maybe_get_post_json( $name = 'template_vars' ) {
		if ( isset( $_POST[ $name ] ) AND is_string( $_POST[ $name ] ) ) {
			$result = json_decode( stripslashes( $_POST[ $name ] ), TRUE );
			if ( ! is_array( $result ) ) {
				$result = array();
			}

			return $result;
		} else {
			return array();
		}
	}
}

if ( ! function_exists( 'us_maybe_load_theme_textdomain' ) ) {
	/**
	 * Load theme's textdomain
	 *
	 * @param string $domain
	 * @param string $path Relative path to seek in child theme and theme
	 *
	 * @return bool
	 */
	function us_maybe_load_theme_textdomain( $domain = 'us', $path = '/languages' ) {
		if ( is_textdomain_loaded( $domain ) ) {
			return TRUE;
		}
		$locale = apply_filters( 'theme_locale', is_admin() ? get_user_locale() : get_locale(), $domain );
		$filepath = us_locate_file( trailingslashit( $path ) . $locale . '.mo' );
		if ( $filepath === FALSE ) {
			return FALSE;
		}

		return load_textdomain( $domain, $filepath );
	}
}

if ( ! function_exists( 'us_array_merge_insert' ) ) {
	/**
	 * Merge arrays, inserting $arr2 into $arr1 before/after certain key
	 *
	 * @param array $arr Modifyed array
	 * @param array $inserted Inserted array
	 * @param string $position 'before' / 'after' / 'top' / 'bottom'
	 * @param string $key Associative key of $arr1 for before/after insertion
	 *
	 * @return array
	 */
	function us_array_merge_insert( array $arr, array $inserted, $position = 'bottom', $key = NULL ) {
		if ( $position == 'top' ) {
			return array_merge( $inserted, $arr );
		}
		$key_position = ( $key === NULL ) ? FALSE : array_search( $key, array_keys( $arr ) );
		if ( $key_position === FALSE OR ( $position != 'before' AND $position != 'after' ) ) {
			return array_merge( $arr, $inserted );
		}
		if ( $position == 'after' ) {
			$key_position ++;
		}

		return array_merge( array_slice( $arr, 0, $key_position, TRUE ), $inserted, array_slice( $arr, $key_position, NULL, TRUE ) );
	}
}

if ( ! function_exists( 'us_array_merge' ) ) {
	/**
	 * Recursively merge two or more arrays in a proper way
	 *
	 * @param array $array1
	 * @param array $array2
	 * @param array ...
	 *
	 * @return array
	 */
	function us_array_merge( $array1, $array2 ) {
		$keys = array_keys( $array2 );
		// Is associative array?
		if ( array_keys( $keys ) !== $keys ) {
			foreach ( $array2 as $key => $value ) {
				if ( is_array( $value ) AND isset( $array1[ $key ] ) AND is_array( $array1[ $key ] ) ) {
					$array1[ $key ] = us_array_merge( $array1[ $key ], $value );
				} else {
					$array1[ $key ] = $value;
				}
			}
		} else {
			foreach ( $array2 as $value ) {
				if ( ! in_array( $value, $array1, TRUE ) ) {
					$array1[] = $value;
				}
			}
		}

		if ( func_num_args() > 2 ) {
			foreach ( array_slice( func_get_args(), 2 ) as $array2 ) {
				$array1 = us_array_merge( $array1, $array2 );
			}
		}

		return $array1;
	}
}

if ( ! function_exists( 'us_shortcode_atts' ) ) {
	/**
	 * Combine user attributes with known attributes and fill in defaults from config when needed.
	 *
	 * @param array $atts Passed attributes
	 * @param string $shortcode Shortcode name
	 * @param string $param_name Shortcode's config param to take pairs from
	 *
	 * @return array
	 */
	function us_shortcode_atts( $atts, $shortcode ) {
		if ( substr( $shortcode, 0, 3 ) == 'us_' ) {
			$element = substr( $shortcode, 3 );
			$pairs = array();
			if ( in_array( $element, us_config( 'shortcodes.theme_elements', array() ) ) ) {
				$element_config = us_config( 'elements/' . $element, array() );
				if ( ! empty( $element_config['params'] ) ) {
					foreach ( $element_config['params'] as $param_name => $param_config ) {
						if ( isset( $param_config['shortcode_std'] ) ) {
							$param_config['std'] = $param_config['shortcode_std'];
						}
						if ( $param_config['type'] == 'checkboxes' AND isset( $param_config['std'] ) AND is_array( $param_config['std'] ) ) {
							$param_config['std'] = implode( ',', $param_config['std'] );
						}
						$pairs[ $param_name ] = ( isset( $param_config['std'] ) ) ? $param_config['std'] : NULL;
					}
				}
			}
		} else {
			$pairs = us_config( 'shortcodes.modified.' . $shortcode . '.' . 'atts', array() );
		}

		$atts = shortcode_atts( $pairs, $atts, $shortcode );

		return apply_filters( 'us_shortcode_atts', $atts, $shortcode );
	}
}

if ( ! function_exists( 'us_get_sharing_counts' ) ) {
	/**
	 * Get number of shares of the provided URL.
	 *
	 * @param string $url The url to count shares
	 * @param array $providers Possible array values: 'facebook', 'pinterest', 'vk'
	 *
	 * Dev note: keep in mind that list of providers may differ for the same URL in different function calls.
	 *
	 * @return array Associative array of providers => share counts
	 */
	function us_get_sharing_counts( $url, $providers ) {
		$transient = 'us_sharing_count_' . md5( $url );
		// Will be used for array keys operations
		$flipped = array_flip( $providers );
		$cached_counts = get_transient( $transient );
		if ( is_array( $cached_counts ) ) {
			$counts = array_intersect_key( $cached_counts, $flipped );
			if ( count( $counts ) == count( $providers ) ) {
				// The data exists and is complete
				return $counts;
			}
		} else {
			$counts = array();
		}

		// Facebook share count
		if ( in_array( 'facebook', $providers ) AND ! isset( $counts['facebook'] ) ) {
			$remote_get_url = 'https://graph.facebook.com/?ids=' . $url;
			$result = wp_remote_get( $remote_get_url, array( 'timeout' => 3 ) );
			if ( is_array( $result ) ) {
				$data = json_decode( $result['body'], TRUE );
			} else {
				$data = NULL;
			}
			if ( is_array( $data ) AND isset( $data[ $url ] ) AND isset( $data[ $url ]['share'] ) AND isset( $data[ $url ]['share']['share_count'] ) ) {
				$counts['facebook'] = use_letters_for_numbers( $data[ $url ]['share']['share_count'] );
			} else {
				$counts['facebook'] = '0';
			}
		}
		// Pinterest share count
		if ( in_array( 'pinterest', $providers ) AND ! isset( $counts['pinterest'] ) ) {
			$result = wp_remote_get( 'https://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=' . $url, array( 'timeout' => 3 ) );
			if ( is_array( $result ) ) {
				$data = json_decode( rtrim( str_replace( 'receiveCount(', '', $result['body'] ), ')' ), TRUE );
			} else {
				$data = NULL;
			}
			$counts['pinterest'] = isset( $data['count'] ) ? use_letters_for_numbers( $data['count'] ) : '0';
		}

		// VK share count
		if ( in_array( 'vk', $providers ) AND ! isset( $counts['vk'] ) ) {
			$result = wp_remote_get( 'http://vkontakte.ru/share.php?act=count&index=1&url=' . $url, array( 'timeout' => 3 ) );
			if ( is_array( $result ) ) {
				$data = intval( trim( str_replace( ');', '', str_replace( 'VK.Share.count(1, ', '', $result['body'] ) ) ) );
			} else {
				$data = NULL;
			}
			$counts['vk'] = ( ! empty( $data ) ) ? use_letters_for_numbers( $data ) : '0';
		}

		// Caching the result for the next 2 hours
		set_transient( $transient, $counts, 2 * HOUR_IN_SECONDS );

		return $counts;
	}
}

if ( ! function_exists( 'use_letters_for_numbers' ) ) {

	/**
	 * Replace millions and thousands for "M" and "K" in numbers
	 */
	function use_letters_for_numbers( $value ) {

		if ( (int) $value > 1000000 ) {
			$value = number_format( $value / 1000000, 1 ) . 'M';
		} elseif ( (int) $value > 1000 ) {
			$value = number_format( $value / 1000, 1 ) . 'К';
		}

		return $value;
	}
}

if ( ! function_exists( 'us_translate' ) ) {
	/**
	 * Call language function with string existing in WordPress or supported plugins and prevent those strings from going into theme .po/.mo files
	 *
	 * @return string Translated text.
	 */
	function us_translate( $text, $domain = NULL ) {
		if ( $domain == NULL ) {
			return __( $text );
		} else {
			return __( $text, $domain );
		}
	}
}

if ( ! function_exists( 'us_translate_x' ) ) {
	function us_translate_x( $text, $context, $domain = NULL ) {
		if ( $domain == NULL ) {
			return _x( $text, $context );
		} else {
			return _x( $text, $context, $domain );
		}
	}
}

if ( ! function_exists( 'us_translate_n' ) ) {
	function us_translate_n( $single, $plural, $number, $domain = NULL ) {
		if ( $domain == NULL ) {
			return _n( $single, $plural, $number );
		} else {
			return _n( $single, $plural, $number, $domain );
		}
	}
}

if ( ! function_exists( 'us_prepare_inline_css' ) ) {
	/**
	 * Prepare a proper inline-css string from given css property
	 *
	 * @param array $props
	 * @param bool $style_attr
	 * @param string $tag
	 *
	 * @return string
	 */
	function us_prepare_inline_css( $props, $style_attr = TRUE, $tag = 'div' ) {
		$result = '';

		foreach ( $props as $prop => $value ) {
			$value = is_string( $value ) ? trim( $value ) : $value;

			// Do not apply if a value is empty string or contains double minus --
			if ( $value == '' OR ( is_string( $value ) AND strpos( $value, '--' ) !== FALSE ) ) {
				continue;
			}
			switch ( $prop ) {

				// Font-family exceptions
				case 'font-family':
					// check h1-h6 tags to avoid duplicating styles
					if ( in_array( $tag, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ) {
						if ( $tag == $value ) {
							break;
						} elseif ( $value == 'h1' AND us_get_option( $tag . '_font_family' ) == 'get_h1|' ) {
							break;
						} else {
							$result .= us_get_font_css( $value );
						}
					} elseif ( $value != 'body' ) {
						$result .= us_get_font_css( $value );
					}
					break;

				// Properties with image values
				case 'background-image':
					if ( is_numeric( $value ) ) {
						$image = wp_get_attachment_image_src( $value, 'full' );
						if ( $image ) {
							$result .= $prop . ':url("' . $image[0] . '");';
						}
					} else {
						$result .= $prop . ':url("' . $value . '");';
					}
					break;

				// All other properties
				default:
					$result .= $prop . ':' . $value . ';';
					break;
			}
		}
		if ( $style_attr AND ! empty( $result ) ) {
			$result = ' style="' . esc_attr( $result ) . '"';
		}

		return $result;
	}
}

if ( ! function_exists( 'us_minify_css' ) ) {
	/**
	 * Prepares a minified version of CSS file
	 *
	 * @link http://manas.tungare.name/software/css-compression-in-php/
	 * @param string $css
	 *
	 * @return string
	 */
	function us_minify_css( $css ) {
		// Remove comments
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );

		// Remove space around opening bracket
		$css = str_replace( array( ' {', '{ ' ), '{', $css );

		// Remove space after colons
		$css = str_replace( ': ', ':', $css );

		// Remove spaces
		$css = str_replace( ' > ', '>', $css );
		$css = str_replace( ' ~ ', '~', $css );
		$css = str_replace( '; ', ';', $css );

		// Remove whitespace
		$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );

		// Remove semicolon before closing bracket
		$css = str_replace( array( ';}', '; }', ' }' ), '}', $css );

		return $css;
	}
}

if ( ! function_exists( 'us_api_remote_request' ) ) {
	// TODO maybe move to admin area functions
	/**
	 * Perform request to US Portal API
	 *
	 * @param $url
	 *
	 * @return array|bool|mixed|object
	 */
	function us_api_remote_request( $url ) {

		if ( empty( $url ) ) {
			return FALSE;
		}

		$args = array(
			'headers' => array( 'Accept-Encoding' => '' ),
			//		'sslverify' => FALSE,
			'timeout' => 300,
			'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36',
		);
		$request = wp_remote_request( $url, $args );

		if ( is_wp_error( $request ) ) {
			//		echo $request->get_error_message();
			return FALSE;
		}

		$data = json_decode( $request['body'] );

		return $data;
	}
}

if ( ! function_exists( 'usof_meta' ) ) {
	/**
	 * Get metabox option value
	 *
	 * @return string|array
	 */
	function usof_meta( $key, $post_id = NULL ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$value = '';
		if ( ! empty( $key ) ) {
			$value = get_post_meta( $post_id, $key, TRUE );
		}

		return $value;
	}
}

if ( ! function_exists( 'us_paragraph_fix' ) ) {
	/**
	 * Clear square brackets from extra html tags
	 *
	 * @return string
	 */
	function us_paragraph_fix( $content ) {
		$array = array(
			'<p>[' => '[',
			']</p>' => ']',
			']<br />' => ']',
			']<br>' => ']',
		);

		$content = strtr( $content, $array );

		return $content;
	}
}

if ( ! function_exists( 'us_get_preloader_numeric_types' ) ) {
	/**
	 * Get preloader numbers
	 *
	 * @return array
	 */
	function us_get_preloader_numeric_types() {
		$config = us_config( 'theme-options' );
		$result = array();

		if ( isset( $config['general']['fields']['preloader']['options'] ) ) {
			$options = $config['general']['fields']['preloader']['options'];
		} else {
			return array();
		}

		if ( is_array( $options ) ) {
			foreach ( $options as $option => $title ) {
				if ( intval( $option ) != 0 ) {
					$result[] = $option;
				}
			}

			return $result;
		} else {
			return array();
		}
	}
}

if ( ! function_exists( 'us_shade_color' ) ) {
	/**
	 * Shade color https://stackoverflow.com/a/13542669
	 *
	 * @return string
	 */
	function us_shade_color( $color, $percent = '0.2' ) {
		$default = '';

		if ( empty( $color ) ) {
			return $default;
		}
		// TODO: make RGBA values appliable
		$color = str_replace( '#', '', $color );

		if ( strlen( $color ) == 6 ) {
			$RGB = str_split( $color, 2 );
			$R = hexdec( $RGB[0] );
			$G = hexdec( $RGB[1] );
			$B = hexdec( $RGB[2] );
		} elseif ( strlen( $color ) == 3 ) {
			$RGB = str_split( $color, 1 );
			$R = hexdec( $RGB[0] );
			$G = hexdec( $RGB[1] );
			$B = hexdec( $RGB[2] );
		} else {
			return $default;
		}

		// Determine color lightness (from 0 to 255)
		$lightness = $R * 0.213 + $G * 0.715 + $B * 0.072;

		// Make result lighter, when initial color lightness is low
		$t = $lightness < 60 ? 255 : 0;

		// Correct shade percent regarding color lightness
		$percent = $percent * ( 1.3 - $lightness / 255 );

		$output = 'rgb(';
		$output .= round( ( $t - $R ) * $percent ) + $R . ',';
		$output .= round( ( $t - $G ) * $percent ) + $G . ',';
		$output .= round( ( $t - $B ) * $percent ) + $B . ')';

		$output = us_rgba2hex( $output );

		// Return HEX color
		return $output;
	}
}

if ( ! function_exists( 'us_hex2rgba' ) ) {
	/**
	 * Convert HEX to RGBA
	 *
	 * @return string
	 */
	function us_hex2rgba( $color, $opacity = FALSE ) {
		$default = 'rgb(0,0,0)';

		// Return default if no color provided
		if ( empty( $color ) ) {
			return $default;
		}

		// Sanitize $color if "#" is provided
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		// Check if color has 6 or 3 characters and get values
		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		// Convert hexadec to rgb
		$rgb = array_map( 'hexdec', $hex );

		// Check if opacity is set(rgba or rgb)
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ",", $rgb ) . ')';
		}

		// Return rgb(a) color string
		return $output;
	}
}

if ( ! function_exists( 'us_gradient2hex' ) ) {
	/**
	 * Extract first value from linear-gradient
	 *
	 * @param $color String linear-gradient value
	 * @return String hex value
	 */
	function us_gradient2hex( $color = '' ) {
		if ( preg_match( '~linear-gradient\(([^,]+),([^,]+),([^)]+)\)~', $color, $matches ) ) {
			$color = (string) $matches[2];

			if ( ( strpos( $color, 'rgb' ) !== FALSE ) AND preg_match( '~rgba?\([^)]+\)~', $matches[0], $rgba ) ) {
				$color = (string) $rgba[0];
				$color = us_rgba2hex( $color );
			}
		}

		return $color;
	}
}

if ( ! function_exists( 'us_rgba2hex' ) ) {
	/**
	 * Convert RGBA to HEX
	 *
	 * @return string
	 */
	function us_rgba2hex( $color ) {
		// Returns HEX in case of RGB is provided, otherwise returns as is
		$default = "#000000";

		if ( empty( $color ) ) {
			return $default;
		}

		$rgb = array();
		$regex = '#\((([^()]+|(?R))*)\)#';

		if ( preg_match_all( $regex, $color, $matches ) ) {
			$rgba = explode( ',', implode( ' ', $matches[1] ) );
			// Cuts first 3 values for RGB
			$rgb = array_slice( $rgba, 0, 3 );
		} else {
			return (string) $color;
		}

		$output = "#";

		foreach ( $rgb as $color ) {
			$hex_val = dechex( intval( $color ) );
			if ( strlen( $hex_val ) === 1 ) {
				$output .= '0' . $hex_val;
			} else {
				$output .= $hex_val;
			}
		}

		return $output;
	}
}

if ( ! function_exists( 'us_get_color' ) ) {
	/**
	 * Return filtered color value
	 *
	 * @param $value String
	 * @param $allow_gradient Bool
	 *
	 * @return String
	 */
	function us_get_color( $value = '', $allow_gradient = FALSE ) {

		if ( strpos( $value, 'color' ) !== FALSE ) {
			$color = us_get_option( $value, '' ); // if the value has "color" string, get the color from Theme Options > Colors
		} elseif ( strpos( $value, '_' ) === 0 ) {
			$color = us_get_option( 'color' . $value, '' ); // if the value begins with "_" string, get the color from Theme Options > Colors
		} else {
			$color = $value; // in other cases use value as color
		}

		return ( $allow_gradient ) ? $color : us_gradient2hex( $color );
	}
}

if ( ! function_exists( 'us_grid_query_offset' ) ) {
	/**
	 * Grid function
	 */
	function us_grid_query_offset( &$query ) {
		if ( ! isset( $query->query['_id'] ) OR $query->query['_id'] !== 'us_grid' ) {
			return;
		}

		global $us_grid_items_offset;

		$posts_per_page = ( ! empty( $query->query['posts_per_page'] ) ) ? $query->query['posts_per_page'] : get_option( 'posts_per_page' );

		if ( $query->is_paged ) {
			$page_offset = $us_grid_items_offset + ( ( $query->query_vars['paged'] - 1 ) * $posts_per_page );

			// Apply adjust page offset
			$query->set( 'offset', $page_offset );

		} else {
			// This is the first page. Just use the offset...
			$query->set( 'offset', $us_grid_items_offset );

		}

		remove_action( 'pre_get_posts', 'us_grid_query_offset' );
	}
}

if ( ! function_exists( 'us_grid_adjust_offset_pagination' ) ) {
	/**
	 * Grid function
	 */
	function us_grid_adjust_offset_pagination( $found_posts, $query ) {
		if ( ! isset( $query->query['_id'] ) OR $query->query['_id'] !== 'us_grid' ) {
			return $found_posts;
		}

		global $us_grid_items_offset;
		remove_filter( 'found_posts', 'us_grid_adjust_offset_pagination' );

		// Reduce WordPress's found_posts count by the offset...
		return $found_posts - $us_grid_items_offset;
	}
}

if ( ! function_exists( 'us_get_taxonomies' ) ) {
	/**
	 * Get taxonomies for selection
	 *
	 * @param $public_only bool
	 * @param $show_slug bool
	 * @param $output string woocommerce_exclude / woocommerce_only
	 *
	 * @return array: slug => title (plural label)
	 */
	function us_get_taxonomies( $public_only = FALSE, $show_slug = TRUE, $output = '' ) {
		$result = array();

		$args = array( 'show_ui' => TRUE );
		if ( $public_only ) {
			$args['public'] = TRUE;
			$args['publicly_queryable'] = TRUE;
		}

		$taxonomies = get_taxonomies( $args, 'object' );
		foreach ( $taxonomies as $taxonomy ) {

			// Exclude taxonomy which is not linked to any post type
			if ( empty( $taxonomy->object_type ) OR empty( $taxonomy->object_type[0] ) ) {
				continue;
			}

			// Check if the taxonomy is related to WooCommerce
			if ( class_exists( 'woocommerce' ) ) {
				$is_woo_tax = FALSE;
				if ( $taxonomy->name == 'product_cat' OR $taxonomy->name == 'product_tag' OR ( strpos( $taxonomy->name, 'pa_' ) === 0 AND is_object_in_taxonomy( 'product', $taxonomy->name ) ) ) {
					$is_woo_tax = TRUE;
				}

				// Exclude WooCommerce taxonomies
				if ( $output == 'woocommerce_exclude' ) {
					if ( $is_woo_tax ) {
						continue;
					}

					// Exclude all except WooCommerce taxonomies
				} elseif ( $output == 'woocommerce_only' ) {
					if ( ! $is_woo_tax ) {
						continue;
					}
				}
			}

			$taxonomy_title = $taxonomy->labels->name;

			// Show slug if set
			if ( $show_slug ) {
				$taxonomy_title .= ' (' . $taxonomy->name . ')';
			}

			$result[ $taxonomy->name ] = $taxonomy_title;
		}

		return $result;
	}
}

if ( ! function_exists( 'us_fix_grid_settings' ) ) {
	/**
	 * Make the provided grid settings value consistent and proper
	 *
	 * @param $value array
	 *
	 * @return array
	 */
	function us_fix_grid_settings( $value ) {
		if ( empty( $value ) OR ! is_array( $value ) ) {
			$value = array();
		}
		if ( ! isset( $value['data'] ) OR ! is_array( $value['data'] ) ) {
			$value['data'] = array();
		}

		$options_defaults = array();
		$elements_defaults = array();
		if ( function_exists( 'usof_get_default' ) ) {
			foreach ( us_config( 'grid-settings.options', array() ) as $option_name => $option_group ) {
				foreach ( $option_group as $option_name => $option_field ) {
					$options_defaults[ $option_name ] = usof_get_default( $option_field );
				}
			}

			foreach ( us_config( 'grid-settings.elements', array() ) as $element_name ) {
				$element_settings = us_config( 'elements/' . $element_name );
				$elements_defaults[ $element_name ] = array();
				foreach ( $element_settings['params'] as $param_name => $param_field ) {
					$elements_defaults[ $element_name ][ $param_name ] = usof_get_default( $param_field );
				}
			}
		}

		foreach ( $options_defaults as $option_name => $option_default ) {
			if ( ! isset( $value['default']['options'][ $option_name ] ) ) {
				$value['default']['options'][ $option_name ] = $option_default;
			}
		}
		foreach ( $value['data'] as $element_name => $element_values ) {
			$element_type = strtok( $element_name, ':' );
			if ( ! isset( $elements_defaults[ $element_type ] ) ) {
				continue;
			}
			foreach ( $elements_defaults[ $element_type ] as $param_name => $param_default ) {
				if ( ! isset( $value['data'][ $element_name ][ $param_name ] ) ) {
					$value['data'][ $element_name ][ $param_name ] = $param_default;
				}
			}
		}

		foreach ( array( 'default' ) as $state ) {
			if ( ! isset( $value[ $state ] ) OR ! is_array( $value[ $state ] ) ) {
				$value[ $state ] = array();
			}
			if ( ! isset( $value[ $state ]['layout'] ) OR ! is_array( $value[ $state ]['layout'] ) ) {
				if ( $state != 'default' AND isset( $value['default']['layout'] ) ) {
					$value[ $state ]['layout'] = $value['default']['layout'];
				} else {
					$value[ $state ]['layout'] = array();
				}
			}
			$state_elms = array();
			foreach ( $value[ $state ]['layout'] as $place => $elms ) {
				if ( ! is_array( $elms ) ) {
					$elms = array();
				}
				foreach ( $elms as $index => $elm_id ) {
					if ( ! is_string( $elm_id ) OR strpos( $elm_id, ':' ) == - 1 ) {
						unset( $elms[ $index ] );
					} else {
						$state_elms[] = $elm_id;
						if ( ! isset( $value['data'][ $elm_id ] ) ) {
							$value['data'][ $elm_id ] = array();
						}
					}
				}
				$value[ $state ]['layout'][ $place ] = array_values( $elms );
			}
			if ( ! isset( $value[ $state ]['layout']['hidden'] ) OR ! is_array( $value[ $state ]['layout']['hidden'] ) ) {
				$value[ $state ]['layout']['hidden'] = array();
			}
			$value[ $state ]['layout']['hidden'] = array_merge( $value[ $state ]['layout']['hidden'], array_diff( array_keys( $value['data'] ), $state_elms ) );
			// Fixing options
			if ( ! isset( $value[ $state ]['options'] ) OR ! is_array( $value[ $state ]['options'] ) ) {
				$value[ $state ]['options'] = array();
			}
			$value[ $state ]['options'] = array_merge( $options_defaults, ( $state != 'default' ) ? $value['default']['options'] : array(), $value[ $state ]['options'] );
		}

		return $value;
	}
}

if ( ! function_exists( 'us_enqueue_fonts' ) ) {
	/**
	 * Enqueue Google Fonts CSS file, used in frontend and admin pages
	 */
	function us_enqueue_fonts( $url = FALSE ) {
		$prefixes = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'body' );
		$font_options = $fonts = array();

		$uploaded_fonts = us_get_option( 'uploaded_fonts', array() );
		$uploaded_font_names = array( 'get_h1' );
		if ( is_array( $uploaded_fonts ) AND count( $uploaded_fonts ) > 0 ) {
			foreach ( $uploaded_fonts as $uploaded_font ) {
				$uploaded_font_names[] = esc_attr( strip_tags( $uploaded_font['name'] ) );
			}
		}

		foreach ( $prefixes as $prefix ) {
			$font_option = explode( '|', us_get_option( $prefix . '_font_family', 'none' ), 2 );
			if ( in_array( $font_option[0], $uploaded_font_names ) ) {
				continue;
			}
			$font_options[] = $font_option;
		}

		$custom_fonts = us_get_option( 'custom_font', array() );
		if ( is_array( $custom_fonts ) AND count( $custom_fonts ) > 0 ) {
			foreach ( $custom_fonts as $custom_font ) {
				$font_options[] = explode( '|', $custom_font['font_family'], 2 );
			}
		}

		foreach ( $font_options as $font ) {
			if ( ! isset( $font[1] ) OR empty( $font[1] ) ) {
				$font[1] = '400,700'; // fault tolerance for missing font-variants
			}
			$selected_font_variants = explode( ',', $font[1] );

			// Empty font or web safe combination selected
			if ( $font[0] == 'none' OR strpos( $font[0], ',' ) !== FALSE ) {
				continue;
			}

			$font[0] = str_replace( ' ', '+', $font[0] );
			if ( ! isset( $fonts[ $font[0] ] ) ) {
				$fonts[ $font[0] ] = array();
			}

			foreach ( $selected_font_variants as $font_variant ) {
				$fonts[ $font[0] ][] = $font_variant;
			}
		}

		$font_display = '&display=' . us_get_option( 'font_display', 'swap' );
		$font_family = '';

		foreach ( $fonts as $font_name => $font_variants ) {
			if ( count( $font_variants ) == 0 ) {
				continue;
			}
			$font_variants = array_unique( $font_variants );
			if ( $font_family != '' ) {
				$font_family .= urlencode( '|' );
			}
			$font_family .= $font_name . ':' . implode( '%2C', $font_variants );
		}

		if ( $font_family != '' ) {
			$font_url = 'https://fonts.googleapis.com/css?family=' . $font_family . $font_display;

			if ( $url ) {
				return $font_url;
			} else {
				wp_enqueue_style( 'us-fonts', $font_url );
			}
		}
	}
}

if ( ! function_exists( 'us_get_fonts' ) ) {
	/**
	 * Get fonts for selection
	 *
	 * @return array
	 */
	function us_get_fonts( $without_groups = FALSE ) {
		$options = array();

		// Regular Text
		$body_font = explode( '|', us_get_option( 'body_font_family', 'none' ), 2 );
		if ( $body_font[0] != 'none' ) {
			$options['body'] = $body_font[0] . ' (' . __( 'used as default font', 'us' ) . ')';
		} else {
			$options['body'] = __( 'No font specified', 'us' );
		}

		// Headings
		for ( $i = 1; $i <= 6; $i ++ ) {
			$heading_font = explode( '|', us_get_option( 'h' . $i . '_font_family', 'none' ), 2 );
			if ( ! in_array( $heading_font[0], array( 'none', 'get_h1' ) ) ) {
				$options[ 'h' . $i ] = $heading_font[0] . ' (' . sprintf( __( 'used in Heading %s', 'us' ), $i ) . ')';
			}
		}

		// Uploaded Fonts
		$uploaded_fonts = us_get_option( 'uploaded_fonts', array() );
		if ( is_array( $uploaded_fonts ) AND count( $uploaded_fonts ) > 0 ) {
			if ( ! $without_groups ) {
				$options[] = array(
					'optgroup' => TRUE,
					'title' => __( 'Uploaded Fonts', 'us' ),
				);
			}
			$uploaded_font_families = array();
			foreach ( $uploaded_fonts as $uploaded_font ) {
				$uploaded_font_name = strip_tags( $uploaded_font['name'] );
				if ( $uploaded_font_name == '' OR in_array( $uploaded_font_name, $uploaded_font_families ) OR empty( $uploaded_font['files'] ) ) {
					continue;
				}
				$uploaded_font_families[] = $uploaded_font_name;
				$options[ $uploaded_font_name ] = $uploaded_font_name;
			}
		}

		// Additional Google Fonts
		$custom_fonts = us_get_option( 'custom_font', array() );
		if ( is_array( $custom_fonts ) AND count( $custom_fonts ) > 0 ) {
			if ( ! $without_groups ) {
				$options[] = array(
					'optgroup' => TRUE,
					'title' => __( 'Google Fonts (loaded from Google servers)', 'us' ),
				);
			}
			foreach ( $custom_fonts as $custom_font ) {
				$font_options = explode( '|', $custom_font['font_family'], 2 );
				$options[ $font_options[0] ] = $font_options[0];
			}
		}

		// Web Safe Fonts
		if ( ! $without_groups ) {
			$options[] = array(
				'optgroup' => TRUE,
				'title' => __( 'Web safe font combinations (do not need to be loaded)', 'us' ),
			);
		}
		$web_safe_fonts = us_config( 'web-safe-fonts' );
		foreach ( $web_safe_fonts as $web_safe_font ) {
			$options[ $web_safe_font ] = $web_safe_font;
		}

		return $options;
	}
}

if ( ! function_exists( 'us_get_font_css' ) ) {
	/**
	 * Generate CSS font-family & font-weight of selected font
	 *
	 * @param string $font_name
	 * @param bool $with_weight
	 *
	 * @return string
	 */
	function us_get_font_css( $font_name, $with_weight = FALSE ) {
		if ( empty( $font_name ) ) {
			return '';
		}
		static $font_css;
		if ( empty( $font_css ) ) {
			$font_options = $font_css = array();

			// Add Regular Text font
			$font_options['body'] = explode( '|', us_get_option( 'body_font_family', 'none' ), 2 );

			// Add Headings fonts
			for ( $i = 1; $i <= 6; $i ++ ) {
				if ( us_get_option( 'h' . $i . '_font_family', 'none' ) == 'get_h1|' ) {
					$font_options[ 'h' . $i ] = explode( '|', us_get_option( 'h1_font_family', 'none' ), 2 );
				} else {
					$font_options[ 'h' . $i ] = explode( '|', us_get_option( 'h' . $i . '_font_family', 'none' ), 2 );
				}
			}

			// Add Additional Google fonts
			$custom_fonts = us_get_option( 'custom_font', array() );
			if ( is_array( $custom_fonts ) AND count( $custom_fonts ) > 0 ) {
				foreach ( $custom_fonts as $custom_font ) {
					$font_option = explode( '|', $custom_font['font_family'], 2 );
					$font_options[ $font_option[0] ] = $font_option;
				}
			}

			// Add Uploaded fonts
			$uploaded_fonts = us_get_option( 'uploaded_fonts', array() );
			if ( is_array( $uploaded_fonts ) AND count( $uploaded_fonts ) > 0 ) {
				foreach ( $uploaded_fonts as $uploaded_font ) {
					$font_options[ $uploaded_font['name'] ] = array(
						0 => strip_tags( $uploaded_font['name'] ),
						1 => $uploaded_font['weight'],
					);
				}
			}

			// Add Websafe fonts
			$web_safe_fonts = us_config( 'web-safe-fonts' );
			foreach ( $web_safe_fonts as $web_safe_font ) {
				$font_options[ $web_safe_font ] = array( $web_safe_font );
			}

			foreach ( $font_options as $prefix => $font ) {
				if ( $font[0] == 'none' ) {
					$font_css[ $prefix ][0] = '';
				} elseif ( strpos( $font[0], ',' ) === FALSE ) {
					$fallback_font_family = us_config( 'google-fonts.' . $font[0] . '.fallback', 'sans-serif' );
					$font_css[ $prefix ][0] = 'font-family:\'' . $font[0] . '\', ' . $fallback_font_family . ';';
					// Fault tolerance for missing font-variants
					if ( ! isset( $font[1] ) OR empty( $font[1] ) ) {
						$font[1] = '400,700';
					}
					// The first active font-weight will be used for "normal" weight
					$font_css[ $prefix ][1] = intval( $font[1] );
				} else {
					// Web-safe font combination
					$font_css[ $prefix ][0] = 'font-family:' . $font[0] . ';';
					$font_css[ $prefix ][1] = '400';
				}
			}
		}

		if ( isset( $font_css[ $font_name ] ) AND ! empty( $font_css[ $font_name ][0] ) ) {
			$result = $font_css[ $font_name ][0];

			if ( $with_weight AND ! empty( $font_css[ $font_name ][1] ) ) {
				$result .= 'font-weight: ' . $font_css[ $font_name ][1] . ';';
			}

			return $result;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'us_get_ip' ) ) {
	// TODO maybe move to admin area functions
	/**
	 * Get the remote IP address
	 *
	 * @return string
	 */
	function us_get_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return apply_filters( 'us_get_ip', $ip );
	}
}

if ( ! function_exists( 'us_get_sidebars' ) ) {
	/**
	 * Get Sidebars for selection
	 *
	 * @return array
	 */
	function us_get_sidebars() {
		$sidebars = array();
		global $wp_registered_sidebars;

		if ( is_array( $wp_registered_sidebars ) AND ! empty( $wp_registered_sidebars ) ) {
			foreach ( $wp_registered_sidebars as $sidebar ) {
				if ( $sidebar['id'] == 'default_sidebar' ) {
					// Add Default Sidebar to the beginning
					$sidebars = array_merge( array( $sidebar['id'] => $sidebar['name'] ), $sidebars );
				} else {
					$sidebars[ $sidebar['id'] ] = $sidebar['name'];
				}
			}
		}

		return $sidebars;
	}
}

if ( ! function_exists( 'us_grid_available_post_types' ) ) {
	/**
	 * Get post types for selection in Grid element
	 *
	 * @return array
	 */
	function us_grid_available_post_types( $reload = FALSE ) {
		static $available_posts_types = array();

		if ( empty( $available_posts_types ) OR $reload ) {
			$posts_types_params = array(
				'show_in_menu' => TRUE,
			);
			$skip_post_types = array(
				'us_header',
				'us_page_block',
				'us_grid_layout',
				'shop_order',
				'shop_coupon',
			);
			foreach ( get_post_types( $posts_types_params, 'objects', 'and' ) as $post_type_name => $post_type ) {
				if ( in_array( $post_type_name, $skip_post_types ) ) {
					continue;
				}
				$available_posts_types[ $post_type_name ] = $post_type->labels->name . ' (' . $post_type_name . ')';
			}
		}

		return $available_posts_types;
	}
}

if ( ! function_exists( 'us_grid_available_taxonomies' ) ) {
	/**
	 * Get post taxonomies for selection in Grid element
	 *
	 * @return array
	 */
	function us_grid_available_taxonomies() {
		$available_taxonomies = array();
		$available_posts_types = us_grid_available_post_types();

		foreach ( $available_posts_types as $post_type => $name ) {
			$post_taxonomies = array();
			$object_taxonomies = get_object_taxonomies( $post_type, 'objects' );
			foreach ( $object_taxonomies as $tax_object ) {
				if ( ( $tax_object->public ) AND ( $tax_object->show_ui ) ) {
					$post_taxonomies[] = $tax_object->name;
				}
			}
			if ( is_array( $post_taxonomies ) AND count( $post_taxonomies ) > 0 ) {
				$available_taxonomies[ $post_type ] = array();
				foreach ( $post_taxonomies as $post_taxonomy ) {
					$available_taxonomies[ $post_type ][] = $post_taxonomy;
				}
			}
		}

		return $available_taxonomies;
	}
}

if ( ! function_exists( 'us_get_public_cpt' ) ) {
	/**
	 * Get Custom Post Types (CPT), which have frontend appearance
	 *
	 * @return array: name => title (plural label)
	 */
	function us_get_public_cpt() {
		$public_cpt = array();

		// Fetch all post types with specified arguments
		$args = array(
			'public' => TRUE,
			'publicly_queryable' => TRUE,
			'_builtin' => FALSE,
		);
		$post_types = get_post_types( $args, 'objects' );

		// Skip some predefined post types
		$skip_post_types = array(
			// Theme
			'us_portfolio',
			'us_testimonial',
			// WooCommerce
			'product',
			// bbPress
			'reply',
		);

		foreach ( $post_types as $post_type_name => $post_type ) {
			if ( ! in_array( $post_type_name, $skip_post_types ) ) {
				$public_cpt[ $post_type_name ] = $post_type->labels->name;
			}
		}

		return $public_cpt;
	}
}

if ( ! function_exists( 'us_get_page_area_id' ) ) {
	/**
	 * Get value of specified area ID for current page
	 *
	 * @param string $area : header / titlebar / content template / sidebar / footer
	 *
	 * @return string
	 */
	function us_get_page_area_id( $area ) {
		if ( empty( $area ) ) {
			return FALSE;
		}

		// Get public custom post types
		$public_cpt = array_keys( us_get_public_cpt() );

		// Get public taxonomies EXCEPT Products
		$public_taxonomies = array_keys( us_get_taxonomies( TRUE, FALSE, 'woocommerce_exclude' ) );

		// Get Products taxonomies ONLY
		$product_taxonomies = array_keys( us_get_taxonomies( TRUE, FALSE, 'woocommerce_only' ) );

		// Default from Theme Options
		$area_id = us_get_option( $area . '_id', '' );

		// Portfolio Pages
		if ( is_singular( array( 'us_portfolio' ) ) ) {
			$area_id = us_get_option( $area . '_portfolio_id' );

			// Posts
		} elseif ( is_singular( array( 'post', 'attachment' ) ) ) {
			$area_id = us_get_option( $area . '_post_id' );

			// WooCommerce Products
		} elseif ( function_exists( 'is_product' ) AND is_product() ) {
			$area_id = us_get_option( $area . '_product_id' );

			// WooCommerce Shop Page
		} elseif ( function_exists( 'is_shop' ) AND is_shop() ) {
			$area_id = us_get_option( $area . '_shop_id' );

			// WooCommerce Products Search
		} elseif ( class_exists( 'woocommerce' ) AND is_post_type_archive( 'product' ) AND is_search() ) {
			$area_id = us_get_option( $area . '_shop_id' );

			// WooCommerce Products Taxonomies
		} elseif ( class_exists( 'woocommerce' ) AND is_tax( $product_taxonomies ) ) {

			$current_tax = get_query_var( 'taxonomy' );
			$area_id = us_get_option( $area . '_tax_' . $current_tax . '_id', '__defaults__' );

			if ( $area_id == '__defaults__' ) {
				$area_id = us_get_option( $area . '_shop_id' );
			}

			// Author Pages
		} elseif ( is_author() ) {
			$area_id = us_get_option( $area . '_author_id', '__defaults__' );

			if ( $area_id == '__defaults__' ) {
				$area_id = us_get_option( $area . '_archive_id', '' );
			}

			// Archives
		} elseif ( is_archive() OR is_tax( $public_taxonomies ) ) {
			$area_id = us_get_option( $area . '_archive_id', '' );

			if ( is_category() ) {
				$current_tax = 'category';
			} elseif ( is_tag() ) {
				$current_tax = 'post_tag';
			} elseif ( is_tax() ) {
				$current_tax = get_query_var( 'taxonomy' );
			}

			if ( ! empty( $current_tax ) AND us_get_option( $area . '_tax_' . $current_tax . '_id', '__defaults__' ) != '__defaults__' ) {
				$area_id = us_get_option( $area . '_tax_' . $current_tax . '_id', '__defaults__' );
			}

			// Custom Post Types
		} elseif ( ! empty( $public_cpt ) AND is_singular( $public_cpt ) ) {
			if ( is_singular( array( 'tribe_events' ) ) ) {
				$post_type = 'tribe_events'; // Events Calendar fix
			} else {
				$post_type = get_post_type();
			}
			$area_id = us_get_option( $area . '_' . $post_type . '_id', '__defaults__' );
		}

		// Forums archive page
		if ( is_post_type_archive( 'forum' ) ) {
			$area_id = us_get_option( $area . '_forum_id' );
		}

		// Events calendar archive page
		if ( is_post_type_archive( 'tribe_events' ) ) {
			$area_id = us_get_option( $area . '_tax_tribe_events_cat_id', '__defaults__' );

			if ( $area_id == '__defaults__' ) {
				$area_id = us_get_option( $area . '_archive_id', '' );
			}
		}

		// Search Results page
		if ( is_search() AND ! is_post_type_archive( 'product' ) AND $postID = us_get_option( 'search_page', 'default' ) AND $postID != 'default' ) {
			$area_id = usof_meta( 'us_' . $area . '_id', $postID );
		}

		// Posts page
		if ( is_home() AND $postID = us_get_option( 'posts_page', 'default' ) AND $postID != 'default' ) {
			$area_id = usof_meta( 'us_' . $area . '_id', $postID );
		}

		// 404 page
		if ( is_404() AND $postID = us_get_option( 'page_404', 'default' ) AND $postID != 'default' ) {
			$area_id = usof_meta( 'us_' . $area . '_id', $postID );
		}

		// Specific page
		if ( is_singular() ) {

			// check the existance of metadata and get its value
			if ( $postID = get_the_ID() AND metadata_exists( 'post', $postID, 'us_' . $area . '_id' ) ) {

				$singular_area_id = usof_meta( 'us_' . $area . '_id', $postID );

				// then check if the value has ID of non-existing Page Block (if it was deleted)
				if ( $singular_area_id == '' OR is_registered_sidebar( $singular_area_id ) OR get_post_status( $singular_area_id ) != FALSE ) {
					$area_id = $singular_area_id;
				}
			}
		}

		// Reset Pages defaults
		if ( $area_id == '__defaults__' ) {
			$area_id = us_get_option( $area . '_id', '' );
		}

		return apply_filters( 'us_get_page_area_id', $area_id );
	}
}

if ( ! function_exists( 'us_get_current_page_block_content' ) ) {
	/**
	 * Get Page Blocks content of the current page
	 */
	function us_get_current_page_block_content() {
		$content = '';

		$footer_id = us_get_page_area_id( 'footer' );
		$content_id = us_get_page_area_id( 'content' );

		// Output content of Page Block (us_page_block) posts
		if ( $footer_id != '' ) {
			$footer = get_post( (int) $footer_id );

			if ( $footer ) {
				$translated_footer_id = apply_filters( 'wpml_object_id', $footer->ID, 'us_page_block', TRUE );
				if ( $translated_footer_id != $footer->ID ) {
					$footer = get_post( $translated_footer_id );
				}
				$content .= $footer->post_content;
			}
		}
		if ( $content_id != '' ) {
			$page_block = get_post( (int) $content_id );

			if ( $page_block ) {
				$translated_page_block_id = apply_filters( 'wpml_object_id', $page_block->ID, 'us_page_block', TRUE );
				if ( $translated_page_block_id != $page_block->ID ) {
					$page_block = get_post( $translated_page_block_id );
				}
				$content .= $page_block->post_content;
			}
		}

		return $content;
	}
}

if ( ! function_exists( 'us_get_btn_styles' ) ) {
	/**
	 * Get Button Styles created on Theme Options > Button Styles
	 *
	 * @return array: id => name
	 */
	function us_get_btn_styles() {

		$btn_styles_list = array();
		$btn_styles = us_get_option( 'buttons', array() );

		if ( is_array( $btn_styles ) ) {
			foreach ( $btn_styles as $btn_style ) {
				$btn_name = trim( $btn_style['name'] );
				if ( $btn_name == '' ) {
					$btn_name = us_translate( 'Style' ) . ' ' . $btn_style['id'];
				}

				$btn_styles_list[ $btn_style['id'] ] = esc_html( $btn_name );
			}
		}

		return $btn_styles_list;
	}
}

if ( ! function_exists( 'us_image_sizes_select_values' ) ) {
	/**
	 * Get image size values for selection
	 *
	 * @param array [$size_names] List of size names
	 *
	 * @return array
	 */
	function us_image_sizes_select_values( $size_names = NULL ) {
		$image_sizes = array();

		// Default WordPress image sizes
		if ( $size_names === NULL ) {
			$size_names = array( 'full', 'large', 'medium', 'thumbnail' );
		}

		// Add WooCommerce image sizes if enabled
		if ( class_exists( 'woocommerce' ) ) {
			$size_names[] = 'shop_single';
			$size_names[] = 'shop_catalog';
		}

		// For translation purposes
		$size_titles = array(
			'full' => us_translate( 'Full Size' ),
		);

		foreach ( $size_names as $size_name ) {
			$size_title = isset( $size_titles[ $size_name ] ) ? $size_titles[ $size_name ] : ucwords( $size_name );
			if ( $size_name != 'full' ) {

				// Detecting size
				$size = us_get_intermediate_image_size( $size_name );

				$size_title = ( ( $size['width'] == 0 ) ? __( 'any', 'us' ) : $size['width'] );
				$size_title .= ' x ';
				$size_title .= ( $size['height'] == 0 ) ? __( 'any', 'us' ) : $size['height'];
				if ( $size['crop'] ) {
					$size_title .= ' ' . __( 'cropped', 'us' );
				}
			}
			$image_sizes[ $size_name ] = $size_title;
		}

		// Custom sizes
		$custom_tnail_sizes = us_get_option( 'img_size' );
		if ( is_array( $custom_tnail_sizes ) ) {
			foreach ( $custom_tnail_sizes as $size_index => $size ) {
				$crop = ( ! empty( $size['crop'][0] ) );
				$crop_str = ( $crop ) ? '_crop' : '';
				$width = ( ! empty( $size['width'] ) AND intval( $size['width'] ) > 0 ) ? intval( $size['width'] ) : 0;
				$height = ( ! empty( $size['height'] ) AND intval( $size['height'] ) > 0 ) ? intval( $size['height'] ) : 0;
				$size_name = 'us_' . $width . '_' . $height . $crop_str;

				$size_title = ( $width == 0 ) ? __( 'any', 'us' ) : $width;
				$size_title .= ' x ';
				$size_title .= ( $height == 0 ) ? __( 'any', 'us' ) : $height;
				if ( $crop ) {
					$size_title .= ' ' . __( 'cropped', 'us' );
				}

				if ( ! in_array( $size_title, $image_sizes ) ) {
					$image_sizes[ $size_name ] = $size_title;
				}
			}
		}

		return apply_filters( 'us_image_sizes_select_values', $image_sizes );
	}
}

if ( ! function_exists( 'us_get_link_from_custom_field' ) ) {
	/**
	 * Change '{{field_name}}' string to the custom field value
	 */
	function us_get_link_from_custom_field( $link_array ) {

		if ( isset( $link_array['url'] ) AND preg_match( "#{{([^}]+)}}#", trim( $link_array['url'] ), $matches ) ) {

			$postID = get_the_ID();
			if ( $meta_value = get_post_meta( $postID, $matches[1], TRUE ) ) {

				// If the value is array, return itself
				if ( is_array( $meta_value ) ) {
					$link_array = $meta_value;

					// If the value is serialized array (used in USOF metabox options)
				} elseif ( substr( strval( $meta_value ), 0, 1 ) === '{' ) {
					try {
						$meta_value_array = json_decode( $meta_value, TRUE );
						if ( is_array( $meta_value_array ) ) {
							$link_array['url'] = $meta_value_array['url'];

							// Override "target" only if it was empty
							if ( empty( $link_array['target'] ) ) {
								$link_array['target'] = $meta_value_array['target'];
							}

							// Force "nofollow" for metabox URLs
							$link_array['rel'] = 'nofollow';
						}
					}
					catch ( Exception $e ) {
					}

					// If the value is string with digits, use it as attachment ID
				} elseif ( is_numeric( $meta_value ) ) {
					$link_array['url'] = wp_get_attachment_url( $meta_value );

					// In other cases return the value as 'url'
				} else {
					$link_array['url'] = trim( $meta_value );
				}

				// If the value is empty, return empty 'url'
			} else {
				$link_array['url'] = '';
			}
		}

		return $link_array;
	}
}

if ( ! function_exists( 'us_generate_link_atts' ) ) {
	/**
	 * Generate attributes for link tag based on elements options
	 *
	 * @param string $link
	 *
	 * @return string
	 */
	function us_generate_link_atts( $link = '' ) {
		if ( empty( $link ) ) {
			return '';
		}

		// Default array
		$link_array = array( 'url' => '', 'title' => '', 'target' => '', 'rel' => '' );

		// Check the type of provided value
		if ( is_array( $link ) ) {
			$link_array = $link;

			// If it is string and begins with "url", use WPBakery way to create array
		} elseif ( strpos( $link, 'url:' ) === 0 OR strpos( $link, '|' ) !== FALSE ) {
			$params_pairs = explode( '|', $link );
			if ( ! empty( $params_pairs ) ) {
				foreach ( $params_pairs as $pair ) {
					$param = explode( ':', $pair, 2 );
					if ( ! empty( $param[0] ) AND isset( $param[1] ) ) {
						$link_array[ $param[0] ] = rawurldecode( $param[1] );
					}
				}
			}
		} else {
			$link_array['url'] = $link;
		}

		// Check for custom fields values
		$link_array = us_get_link_from_custom_field( $link_array );

		// Replace [lang] with current language code
		if ( ! empty( $link_array['url'] ) AND strpos( $link_array['url'], '[lang]' ) !== FALSE ) {
			$link_array['url'] = str_replace( '[lang]', usof_get_lang(), $link_array['url'] );
		}

		$link_array = apply_filters( 'us_generate_link_atts_link_array', $link_array );

		// Add attributes
		if ( ! empty( $link_array['url'] ) ) {
			$result = ' href="' . esc_url( trim( $link_array['url'] ) ) . '"';
			$result .= ( ! empty( $link_array['title'] ) ) ? ( ' title="' . esc_attr( $link_array['title'] ) . '"' ) : '';
			$result .= ( ! empty( $link_array['target'] ) ) ? ' target="_blank"' : '';

			// Force rel="noopener"
			if ( ! empty( $link_array['rel'] ) OR ! empty( $link_array['target'] ) ) {
				$result .= ' rel="noopener';
				if ( ! empty( $link_array['rel'] ) ) {
					$result .= ' ' . esc_attr( $link_array['rel'] );
				}
				$result .= '"';
			}

		} else {
			$result = '';
		}

		return $result;
	}
}

if ( ! function_exists( 'us_get_elm_link_options' ) ) {
	/**
	 * Generate array for "Link" option, used in theme elements
	 *
	 * @return array
	 */
	function us_get_elm_link_options() {

		// Predefined options
		$link_options = array(
			'us_tile_link' => __( 'Custom appearance in Grid', 'us' ) . ': ' . __( 'Custom Link', 'us' ),
		);

		// Add Testimonial author link, if Testimonials are enabled
		if ( us_get_option( 'enable_testimonials', 1 ) ) {
			$link_options['us_testimonial_link'] = __( 'Testimonial', 'us' ) . ': ' . __( 'Author Link', 'us' );
		}

		// Add field types from "Advanced Custom Fields" plugin
		if ( function_exists( 'acf_get_field_groups' ) AND $acf_groups = acf_get_field_groups() ) {
			foreach ( $acf_groups as $group ) {
				$fields = acf_get_fields( $group['ID'] );
				foreach ( $fields as $field ) {

					// Add specific types as link options
					if ( in_array( $field['type'], array( 'url', 'link', 'file' ) ) ) {
						$link_options[ $field['name'] ] = $group['title'] . ': ' . $field['label'];
					}
				}
			}
		}

		return $link_options;
	}
}

if ( ! function_exists( 'us_get_smart_date' ) ) {
	/**
	 * Return date and time in Human readable format
	 *
	 * @param int $from Unix timestamp from which the difference begins.
	 * @param int $to Optional. Unix timestamp to end the time difference. Default becomes current_time() if not set.
	 *
	 * @return string Human readable date and time.
	 */
	function us_get_smart_date( $from, $to = '' ) {
		if ( empty( $to ) ) {
			$to = current_time( 'U' );
		}

		$diff = (int) abs( $to - $from );

		// Get time format from site general settings
		$site_time_format = get_option( 'time_format', 'g:i a' );

		$time_string = date( $site_time_format, $from );
		$day = (int) date( 'jmY', $from );
		$current_day = (int) date( 'jmY', $to );
		$yesterday = (int) date( 'jmY', strtotime( 'yesterday', $to ) );
		$year = (int) date( 'Y', $from );
		$current_year = (int) date( 'Y', $to );

		if ( $diff < HOUR_IN_SECONDS ) {
			$mins = round( $diff / MINUTE_IN_SECONDS );
			if ( $mins <= 1 ) {
				$mins = 1;
			}

			// 1-59 minutes ago
			$mins_string = sprintf( us_translate_n( '%s min', '%s mins', $mins ), $mins );
			$result = sprintf( us_translate( '%s ago' ), $mins_string );
		} elseif ( $diff <= ( HOUR_IN_SECONDS * 4 ) ) {
			$hours = round( $diff / HOUR_IN_SECONDS );
			if ( $hours <= 1 ) {
				$hours = 1;
			}

			// 1-4 hours ago
			$hours_string = sprintf( us_translate_n( '%s hour', '%s hours', $hours ), $hours );
			$result = sprintf( us_translate( '%s ago' ), $hours_string );
		} elseif ( $current_day == $day ) {

			// Today at 9:30
			$result = sprintf( us_translate( '%1$s at %2$s' ), us_translate( 'Today' ), $time_string );
		} elseif ( $yesterday == $day ) {

			// Yesterday at 9:30
			$result = sprintf( us_translate( '%1$s at %2$s' ), __( 'Yesterday', 'us' ), $time_string );
		} elseif ( $current_year == $year ) {

			// 23 Jan at 12:30
			$result = sprintf( us_translate( '%1$s at %2$s' ), date_i18n( 'j M', $from ), $time_string );
		} else {

			// 18 Dec 2018
			$result = date_i18n( 'j M Y', $from );
		}

		return $result;
	}
}

if ( ! function_exists( 'us_replace_comment_count_var' ) ) {
	/**
	 * Change '{{comment_count}}' string to comments amount of the current page
	 */
	function us_replace_comment_count_var( $string ) {

		if ( strpos( $string, '{{comment_count}}' ) !== FALSE ) {
			global $post;
			if ( $post ) {
				$comments_amount = get_comment_count( $post->ID );
				$string = str_replace( '{{comment_count}}', $comments_amount['approved'], $string );
			} else {
				$string = str_replace( '{{comment_count}}', '0', $string );
			}
		}

		return $string;
	}
}

/**
 * Get list of posts titles by a certain post type
 * @param string $post_type Post type to get
 * @param bool $force_no_cache Allow using cache (use FALSE to force not-cached version)
 * @return array
 */
function us_get_posts_titles_for( $post_type, $orderby = 'title', $force_no_cache = TRUE ) {
	// Caching results
	static $result = array();
	if ( ! isset( $result[ $post_type ] ) OR $force_no_cache ) {
		$result[ $post_type ] = array();
		$get_posts_args = array(
			'post_type' => $post_type,
			'posts_per_page' => - 1,
			'post_status' => 'any',
			'suppress_filters' => 0,
		);
		if ( ! empty( $orderby ) AND $orderby == 'title' ) {
			$get_posts_args['orderby'] = 'title';
			$get_posts_args['order'] = 'ASC';
		}
		$posts = get_posts( $get_posts_args );
		foreach ( $posts as $post ) {
			if ( $post->post_title != '' ) {
				$result[ $post_type ][ $post->ID ] = $post->post_title;
			} else {
				$result[ $post_type ][ $post->ID ] = us_translate( '(no title)' );
			}
		}
	}

	return $result[ $post_type ];
}

if ( ! class_exists( 'Us_Vc_Base' ) ) {
	// some functions from Vc_Base, without extending from Vc_Base
	class Us_Vc_Base {

		public function init() {
			add_action( 'wp_head', array( $this, 'addFrontCss' ), 1000 );
		}

		public function is_vc_active() {
			if ( class_exists( 'Vc_Manager' ) ) {
				return TRUE;
			} else {
				return FALSE;
			}
		}

		/**
		 * Add css styles for current page and elements design options added w\ editor.
		 */
		public function addFrontCss() {
			$this->addPageCustomCss();
			$this->addShortcodesCustomCss();
		}

		/**
		 * Add custom styles to the page
		 *
		 * @param mixed $id Unique post id
		 * @return void
		 */
		public function addPageCustomCss( $id = NULL ) {
			if ( is_front_page() OR is_home() ) {
				$ids[] = get_queried_object_id();
			} elseif ( is_singular() ) {
				$ids[] = ! is_null( $id )
					? $id
					: get_the_ID();
			}

			global $us_page_block_ids;
			if ( ! empty( $us_page_block_ids ) ) {
				$ids = array_merge( $ids, $us_page_block_ids );
			}

			// Get custom styles by available identifiers
			foreach ( array_unique( $ids ) as $id ) {
				if ( $this->is_vc_active() AND 'true' === vc_get_param( 'preview' ) ) {
					$latest_revision = wp_get_post_revisions( $id );
					if ( ! empty( $latest_revision ) ) {
						$array_values = array_values( $latest_revision );
						$id = $array_values[0]->ID;
					}
				}
				if ( $post_custom_css = get_metadata( 'post', $id, '_wpb_post_custom_css', TRUE ) ) {
					$post_custom_css = strip_tags( $post_custom_css );
					echo sprintf( '<style type="text/css" data-type="vc_custom-css">%s</style>', $post_custom_css );
				}
			}
		}

		public function addShortcodesCustomCss( $id = NULL ) {
			if ( ! is_singular() AND ! $id ) {
				return;
			}
			if ( ! $id ) {
				$id = get_the_ID();
			}

			if ( $id ) {
				if ( $this->is_vc_active() AND 'true' === vc_get_param( 'preview' ) ) {
					$latest_revision = wp_get_post_revisions( $id );
					if ( ! empty( $latest_revision ) ) {
						$array_values = array_values( $latest_revision );
						$id = $array_values[0]->ID;
					}
				}
				$shortcodes_custom_css = get_metadata( 'post', $id, '_wpb_shortcodes_custom_css', TRUE );
				if ( ! empty( $shortcodes_custom_css ) ) {
					$shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
					echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
					echo $shortcodes_custom_css;
					echo '</style>';
				}
			}
		}
	}
}

if ( ! function_exists( 'us_get_img_placeholder' ) ) {
	/**
	 * Returns image placeholder
	 * @param string $size The image size
	 * @param string $src_only if TRUE returns file URL, if FALSE returns string with <img>
	 * @return string
	 */
	function us_get_img_placeholder( $size = 'full', $src_only = FALSE ) {

		// Default placeholder
		$size_array = us_get_intermediate_image_size( $size );
		$img_src = US_CORE_URI . '/assets/images/placeholder.svg';
		$img_full = '<img class="g-placeholder"';
		$img_full .= ' src="' . US_CORE_URI . '/assets/images/placeholder.svg"';
		$img_full .= ' width="' . $size_array['width'] . '"';
		$img_full .= ' height="' . $size_array['height'] . '"';
		$img_full .= ' alt="">';

		// If Images Placeholder is set, use its attachment ID
		if ( preg_match( '~^(\d+)(\|(.+))?$~', us_get_option( 'img_placeholder', '' ), $matches ) ) {
			$img_src = wp_get_attachment_image_url( $matches[1], $size );
			$img_full = wp_get_attachment_image( $matches[1], $size, TRUE, array( 'class' => 'g-placeholder' ) );
		}

		if ( $src_only ) {
			return $img_src;
		} else {
			return $img_full;
		}
	}
}

if ( ! function_exists( 'us_wp_link_pages' ) ) {
	/**
	 * Custom Post Pagination
	 * @param bool $echo
	 * @return string Returns or Echoes Pagination
	 */
	function us_wp_link_pages( $echo = FALSE ) {
		$links = wp_link_pages(
			array(
				'before' => '<nav class="post-pagination"><span class="title">' . us_translate( 'Pages:' ) . '</span>',
				'after' => '</nav>',
				'link_before' => '<span>',
				'link_after' => '</span>',
				'echo' => 0,
			)
		);

		if ( $echo ) {
			echo $links;
		} else {
			return $links;
		}
	}
}
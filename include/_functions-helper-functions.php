<?php
/**
 * All helper functions begin with an underscore. They are only called by other functions, not directly.
 */

if ( ! function_exists ( '_bzmndsgn_log_404_error' ) ):
	/**
	 * Log 404 errors to a file. Works in conjunction with 404.php of the theme.
	 */
	function _bzmndsgn_log_404_error ( ) {

		$date = date ( 'c' ) ;

		$not_found_url = $_SERVER['REQUEST_URI'] ;

		$user_agent = $_SERVER['HTTP_USER_AGENT'] ;

		$client_ip_address = $_SERVER['REMOTE_ADDR'] ;

		$referrer = isset ( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '' ;

		$not_found_details = sprintf ('%1$s - %4$s - %3$s - %2$s - %5$s'."\n", $date, $not_found_url, $user_agent , $client_ip_address, $referrer ) ;

		$not_found_path = sprintf ( '%1$s/%2$s', BZMNDSGN_DOCUMENT_ROOT_URI, BZMNDSGN_NOT_FOUND_404_LOG_FILE ) ;

		file_put_contents ( $not_found_path, $not_found_details, FILE_APPEND | LOCK_EX ) ;

	}
endif;

if ( ! function_exists ( '_bzmndsgn_debug' ) ):
    /**
     * Write debugging information to debug log.
     * @param $data
     */
    function _bzmndsgn_debug ( $data ) {
        if ( BZMNDSGN_DEBUG ) {
            $timestamp = date('Y.m.d H.i.s');
            $log_message = sprintf ( '%1$s: %2$s', $timestamp, $data ) . "\n" ;
            file_put_contents (BZMNDSGN_DEBUG_LOG, $log_message, FILE_APPEND | LOCK_EX ) ;
        }
    }
endif;

if ( ! function_exists ( '_print_admin_settings_heading' ) ):
	/**
	 * Print the heading, subheading, and success messages for the admin panel pages.
	 * @param $subtitle
	 * @param string $title
	 */
	function _print_admin_settings_heading ( $subtitle, $title = '' ) {
		printf ('<div class="wrap">');
	    printf ('<h1>%s Settings</h1>', $title == '' ? get_bloginfo ( 'name' ) : $title ) ;
		if ( isset( $_GET['message'] ) && $_GET['message'] == '1' ) {
			?>
            <div id="message" class="notice notice-success is-dismissible">
            <p><strong><?php echo $_GET['details'] ; ?></strong></p>
            </div><?php
		}
		printf ( '<h2>%s</h2>', $subtitle ) ;
	}
endif;

if ( ! function_exists ( '_print_admin_settings_footer' ) ):
	/**
	 * Print admin settings footer.
	 */
	function _print_admin_settings_footer ( ) {
		printf ('</div>'); // for div.wrap
	}
endif;

if ( ! function_exists ('_get_environment' ) ):
	/**
	 * Get the server environment (Production, Staging, Development, or Local Development).
	 */
	function _get_environment_type ( ) {

	    $environment_type = 'Production' ;

		$url_parts = explode ( '.', $_SERVER['HTTP_HOST'] ) ;
		if ( $url_parts ) {
			$url_parts_count = count ( $url_parts ) ;
			if ( $url_parts_count  == 3 ) {
				$subdomain = $url_parts[0] ;
				if ( $subdomain == 'dev' ) {
					$environment_type = 'Development';
				}
				if ( $subdomain == 'staging' ) {
					$environment_type = 'Staging' ;
				}
			}

			// Mostly local development, or domain.ext.
			if ( count ( $url_parts )  == 2 ) {
				$domain = $url_parts[count($url_parts)-1] ; // ".ext"
				if ( $domain ) {
					if ( $domain == 'local' ) {
						$environment_type = 'Local Development' ;
					}
				}
			}

        }
		return $environment_type ;
    }
endif;

if ( ! function_exists( '_bzmndsgn_form_redirect')):
	/**
	 * Redirect page after submitting an admin form.
	 *
	 * @param $message
	 * @param string $destination
	 */
	function _bzmndsgn_form_redirect ( $message, $destination = 'admin.php' ) {

		$referrer = $_POST['_wp_http_referer'] ;
		$link_parts = parse_url ( $referrer ) ;
		$query = $link_parts['query'] ;
		parse_str ( $query, $query_array ) ;

		$details_message = urlencode ( $message ) ;

		// Redirect with success=1 query string.
		wp_redirect (
			add_query_arg (
				array (
					'page' => $query_array['page'],
					'message' => '1',
					'details' => $details_message,
				),
				admin_url ( $destination )
			)
		);
	}
endif;

if ( ! function_exists ( '_array_key_first' ) ) :
	/**
     * Get name of first key in an array. Backward-compatible, too.
	 * @param $array
	 *
	 * @return int|mixed|string|null
	 */
	function _array_key_first ( $array ) {
        if ( function_exists ('array_key_first') ) {
            return array_key_first ( $array ) ;
        }
        else {
            $array_keys = array_keys ( $array ) ;
            return $array_keys[0] ;
        }
    }
endif;

if ( ! function_exists ( '_is_enabled' ) ) :
	/**
	 * @param $key
	 * @param $options
	 *
	 * @return bool
	 */
	function _is_enabled ( $key, $options) {
        if (isset ( $options[$key] ) && $options[$key]) {
            return true ;
        }
        return false ;
    }
endif;
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

if ( ! function_exists('_get_url_parts' ) ):
	/**
	 * Return URL parts of a given URL in an array.
	 * @param $fqdn
	 *
	 * @return false|string[]
	 */
	function _get_url_parts ( $fqdn ) {
        $url_parts = explode ( '.', $fqdn ) ;
        if ( $url_parts ) {
            return $url_parts ;
        }
        return false ;
    }
endif;

if ( ! function_exists ('_get_domain' ) ):
	/**
     * Return TLD extension of a given URL.
	 * @param $fqdn
	 *
	 * @return bool|mixed|string
	 */
	function _get_domain ( $fqdn ) {
        $url_parts = _get_url_parts ( $fqdn ) ;
        if ( $url_parts ) {
            return $url_parts[count($url_parts)-1] ;
        }
        return false ;
    }
endif;
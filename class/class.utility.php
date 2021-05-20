<?php
/**
 * Class for general utilities.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design ;

class utility {

	/**
	 * Get the server environment (Production, Staging, Development, or Local Development).
	 */
	public static function get_environment_type() {
		$environment_type = 'Production';

		$url_parts = explode( '.', $_SERVER['HTTP_HOST'] );
		if ( $url_parts ) {
			$url_parts_count = count( $url_parts );
			if ( $url_parts_count == 3 ) {
				$subdomain = $url_parts[0];
				if ( $subdomain == 'dev' ) {
					$environment_type = 'Development';
				}
				if ( $subdomain == 'staging' ) {
					$environment_type = 'Staging';
				}
			}

			// Mostly local development, or domain.ext.
			if ( count( $url_parts ) == 2 ) {
				$domain = $url_parts[ count( $url_parts ) - 1 ]; // ".ext"
				if ( $domain ) {
					if ( in_array ( $domain, ['local','test'] ) ) {
						$environment_type = 'Local Development';
					}
				}
			}
		}

		return $environment_type;
	}

	/**
	 * Log 404 errors to a file. Works in conjunction with 404.php of the theme.
	 * FIXME: this won't work.
	 * _references constants that will no longer exist.
	 * _is currently called, as a function, from 404.php.
	 */
	public static function log_404_error() {
		$date = date( 'c' );

		$not_found_url = $_SERVER['REQUEST_URI'];

		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		$client_ip_address = $_SERVER['REMOTE_ADDR'];

		$referrer = isset ( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';

		$not_found_details = sprintf( '%1$s - %4$s - %3$s - %2$s - %5$s' . "\n", $date, $not_found_url, $user_agent, $client_ip_address, $referrer );

		$not_found_path = sprintf( '%1$s/%2$s', BZMNDSGN_DOCUMENT_ROOT_URI, BZMNDSGN_NOT_FOUND_404_LOG_FILE );

		file_put_contents( $not_found_path, $not_found_details, FILE_APPEND | LOCK_EX );

	}

	/**
	 * Write debugging information to debug log.
	 * @param string $data
	 * FIXME: this won't work.
	 * _references constants that will no longer exist.
	 */
	public static function debug ( string $data ) {
		if ( bdsl::debug ) {
			$timestamp = date('Y.m.d H.i.s');
			$log_message = sprintf ( '%1$s: %2$s', $timestamp, $data ) . "\n" ;
			file_put_contents (BZMNDSGN_DEBUG_LOG, $log_message, FILE_APPEND | LOCK_EX ) ;
		}
	}

	/**
	 * Print the heading, subheading, and success messages for the admin panel pages.
	 *
	 * @param string $subtitle
	 * @param string $title
	 */
	public static function print_admin_settings_heading ( string $subtitle, string $title = '' ) {
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

	/**
	 * Print admin settings footer.
	 */
	public static function print_admin_settings_footer ( ) {
		printf ('</div>'); // for div.wrap
	}

	/**
	 * Redirect page after submitting an admin form.
	 *
	 * @param string $message
	 * @param string $destination
	 */
	public static function form_redirect ( string $message, string $destination = 'admin.php' ) {

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

	/**
	 * Get name of first key in an array. Backward-compatible, too.
	 * @param $array
	 *
	 * @return int|mixed|string|null
	 */
	public static function array_key_first ( $array ) {
		if ( function_exists ('array_key_first') ) {
			return array_key_first ( $array ) ;
		}
		else {
			$array_keys = array_keys ( $array ) ;
			return $array_keys[0] ;
		}
	}

	/**
	 * @param $key
	 * @param $options
	 *
	 * @return bool
	 */
	public static function is_enabled ( $key ): bool {
		$options = preferences::get_database_options() ;
        if (isset ( $options[$key] ) && $options[$key]) {
			return true ;
		}
		return false ;
	}

}
<?php
/**
 * Page class for dashboard config page.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design\page ;

use baizman_design\utility;

class error_log extends page {

	public function __construct( $plugin, $page_title, $page_slug ) {
		parent::__construct( $plugin, $page_title, $page_slug );
	}

	/**
	 * @return mixed
	 */
	public function render_page() {
		utility::print_admin_settings_heading ('404 Error Log', 'Baizman Design Standard Library' ) ;

		$not_found_path =  BZMNDSGN_DOCUMENT_ROOT_URI . BZMNDSGN_NOT_FOUND_404_LOG_FILE ;

		if ( file_exists ( $not_found_path ) ) {

			$not_found_log = file ( $not_found_path ) ;

			printf ('<table class="not_found_log_entries">') ;
			printf ('<tr valign="top">') ;
			printf ('<th>Date</th><th>IP address</th><th>User Agent</th><th>Requested Page</th><th>Referring Page</th>') ;
			printf ('</tr>') ;

			foreach ( $not_found_log as $line_number => $line ) {
				list ( $date_time, $client_ip_address, $user_agent, $requested_page, $referrer ) = explode ( ' - ', $line );
				printf ('<tr valign="top">') ;
				printf ('<td>%1$s</td><td>%2$s</td><td>%3$s</td><td>%4$s</td><td>%5$s</td>', $date_time, $client_ip_address, $user_agent, $requested_page, $referrer) ;
				printf ('</tr>') ;

			}
			printf ('</table>') ;
		}
		else {
			printf ('The log file <strong>%s</strong> does not exist. Please add <code>baizman_design\utility::bzmndsgn_log_404_error()</code> to the theme\'s 404.php template.', $not_found_path ) ;
		}

		utility::print_admin_settings_footer() ;
	}

	/**
	 * @return mixed
	 */
	public function register_settings() {
		// TODO: Implement register_settings() method.
	}

}
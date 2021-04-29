<?php
/**
 * Email settings functions.
 */

namespace baizman_design ;

// https://developer.wordpress.org/reference/functions/is_plugin_active/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( ! is_plugin_active( 'easy-wp-smtp/easy-wp-smtp.php' ) ) :

	$bzmndsgn_config_options_database = get_option( BZMNDSGN_CONFIG_OPTIONS );

	if ( ! function_exists( 'bzmndsgn_email_sender_address' ) ):

		/**
		 * Set sender email address on WP emails.
		 */
		function bzmndsgn_email_sender_address() {
			return $GLOBALS[ BZMNDSGN_CONFIG_OPTIONS ]['email_sender_address'];
		}

		if ( $bzmndsgn_config_options_database['email_sender_address'] ) {
			add_filter( 'wp_mail_from', __NAMESPACE__.'\bzmndsgn_email_sender_address' );
		}

	endif;

	if ( ! function_exists( 'bzmndsgn_email_sender_name' ) ):

		/**
		 * Set sender name on WP emails.
		 */
		function bzmndsgn_email_sender_name() {
			return $GLOBALS[ BZMNDSGN_CONFIG_OPTIONS ]['email_sender_name'];
		}

		if ( $bzmndsgn_config_options_database['email_sender_name'] ) {
			add_filter( 'wp_mail_from_name', __NAMESPACE__.'\bzmndsgn_email_sender_name' );
		}

	endif;

	if ( ! function_exists( 'bzmndsgn_email_reply_to_address' ) ):

		/**
		 * Set reply-to address on WP emails.
		 */
		function bzmndsgn_email_reply_to_address( $arguments ) {
			// NOTE: despite the documentation, 'headers' must be a string and cannot be an array.
			// https://developer.wordpress.org/reference/functions/wp_mail/
			$arguments['headers'] = sprintf( 'Reply-To: %s', $GLOBALS[ BZMNDSGN_CONFIG_OPTIONS ]['email_reply_to_address'] );

			return $arguments;
		}

		if ( $bzmndsgn_config_options_database['email_reply_to_address'] ) {
			add_filter( 'wp_mail', __NAMESPACE__.'\bzmndsgn_email_reply_to_address', 10, 1 );
		}

	endif;

endif;

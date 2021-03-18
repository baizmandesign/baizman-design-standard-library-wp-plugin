<?php
/**
 * Email settings functions.
 */

defined ( 'ABSPATH' ) or die ( 'This file cannot be run outside of WordPress.' ) ;

$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

if ( ! function_exists ( 'bzmndsgn_email_sender_address' ) ):

	/**
	 * Set sender email address on WP emails.
	 */
	function bzmndsgn_email_sender_address () {
		return $GLOBALS[BZMNDSGN_CONFIG_OPTIONS]['email_sender_address'];
	}

	if ( $bzmndsgn_config_options_database['email_sender_address'] ) {
		add_filter( 'wp_mail_from', 'bzmndsgn_email_sender_address' );
	}

endif;

if ( ! function_exists ( 'bzmndsgn_email_sender_name' ) ):

	/**
	 * Set sender name on WP emails.
	 */
	function bzmndsgn_email_sender_name () {
		return $GLOBALS[BZMNDSGN_CONFIG_OPTIONS]['email_sender_name'];
	}

	if ( $bzmndsgn_config_options_database['email_sender_name'] ) {
		add_filter( 'wp_mail_from_name', 'bzmndsgn_email_sender_name' );
	}

endif;

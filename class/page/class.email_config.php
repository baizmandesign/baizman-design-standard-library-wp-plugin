<?php
/**
 * Page class for dashboard config page.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design\page ;

use baizman_design\bdsl;
use baizman_design\checkbox;
use baizman_design\checkbox_group;
use baizman_design\color;
use baizman_design\email_input;
use baizman_design\form;
use baizman_design\preferences;
use baizman_design\text_area;
use baizman_design\text_input;
use baizman_design\utility;

class email_config extends page {

	public function __construct( $plugin, $page_title, $page_slug ) {
		parent::__construct( $plugin, $page_title, $page_slug );

		if ( preferences::get_database_option('email_sender_address') ) {
			add_filter( 'wp_mail_from', [__CLASS__,'email_sender_address'] );
		}

		if ( preferences::get_database_option('email_sender_name') ) {
			add_filter( 'wp_mail_from_name', [__CLASS__,'email_sender_name'] );
		}

		if ( preferences::get_database_option('email_reply_to_address') ) {
			add_filter( 'wp_mail', [__CLASS__,'email_reply_to_address'], 10, 1 );
		}
	}

	/**
	 * @return mixed
	 */
	public function render_page() {
		utility::print_admin_settings_heading ('Email Settings', 'Baizman Design Standard Library' ) ;

		$email_settings_form = new form ( 'email_settings' ) ;
		$form_database_settings = $email_settings_form->get_form_database_settings() ;
		$email_settings_form->set_settings_fields_option_group(bdsl::settings_group);
		$email_settings_form->set_settings_fields_page(bdsl::settings_group );

		// Sender name
		$email_sender_name = new text_input( 'Sender Name:', 'email_sender_name','', preferences::get_database_option('email_sender_name') );
		$email_settings_form->add_form_field( $email_sender_name );

		// Sender email address
		$email_sender_address = new email_input( 'Sender Address:', 'email_sender_address','user@domain.com', preferences::get_database_option('email_sender_address') );
		$email_sender_address->set_field_help_text( sprintf ("Note: some email programs may flag emails whose sender address domain differs from the server's domain (%s) as spam.", $_SERVER['HTTP_HOST']));
		$email_settings_form->add_form_field( $email_sender_address );

		// Reply-to email address
		$email_reply_to_address = new email_input( 'Reply-To Address:', 'email_reply_to_address','user@domain.com', preferences::get_database_option('email_reply_to_address') );
		$email_reply_to_address->set_field_help_text('If empty, the reply-to address defaults to the sender address.');

		$email_settings_form->add_form_field( $email_reply_to_address );

		// TODO: reply-to address. Use wp_mail() hook?
		// https://developer.wordpress.org/reference/hooks/wp_mail/

		$email_settings_form->render_form();

		utility::print_admin_settings_footer() ;
	}

	/**
	 * Set sender email address on WP emails.
	 */
	public function email_sender_address() {
		return preferences::get_database_option('email_sender_address');
	}

	/**
	 * Set sender name on WP emails.
	 */
	public function email_sender_name() {
		return preferences::get_database_option('email_sender_name');
	}

	/**
	 * Set reply-to address on WP emails.
	 */
	public function email_reply_to_address( $arguments ) {
		// NOTE: despite the documentation, 'headers' must be a string and cannot be an array.
		// https://developer.wordpress.org/reference/functions/wp_mail/
		$arguments['headers'] = sprintf( 'Reply-To: %s', preferences::get_database_option('email_reply_to_address') );

		return $arguments;
	}

}
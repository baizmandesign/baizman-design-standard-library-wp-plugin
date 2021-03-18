<?php
/**
 * Email settings.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

function bzmndsgn_email () {

	_print_admin_settings_heading ('Email Settings', 'Baizman Design Standard Library' ) ;

	$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );
//var_dump($bzmndsgn_config_options_database);

	$email_settings_form = new form ( 'email_settings' ) ;
	$form_database_settings = $email_settings_form->get_form_database_settings() ;
	$email_settings_form->set_settings_fields_option_group(BZMNDSGN_SETTINGS_GROUP);
	$email_settings_form->set_settings_fields_page(BZMNDSGN_SETTINGS_GROUP );

	// Sender name
	$email_sender_name = new text_input( 'Sender Name:', 'email_sender_name','', $bzmndsgn_config_options_database['email_sender_name'] );
	$email_settings_form->add_form_field( $email_sender_name );

	// Sender email address
	$email_sender_address = new email_input( 'Sender Address:', 'email_sender_address','user@domain.com', $bzmndsgn_config_options_database['email_sender_address'] );
	$email_sender_address->set_field_help_text( sprintf ("Note: some email programs may flag emails whose sender address domain differs from the server's domain (%s) as spam.", $_SERVER['HTTP_HOST']));
	$email_settings_form->add_form_field( $email_sender_address );

	// TODO: reply-to address. Use wp_mail() hook?
	// https://developer.wordpress.org/reference/hooks/wp_mail/

	$email_settings_form->render_form();

	_print_admin_settings_footer() ;

}
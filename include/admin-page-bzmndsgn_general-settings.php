<?php
/**
 * General settings.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

function bzmndsgn_general_settings () {

	_print_admin_settings_heading ('General Settings', 'Baizman Design Standard Library' ) ;

	$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

	// TODO: add the local plugin setting key name
	// background color of the wp interface?

	$general_settings_form = new form ( 'general_settings' ) ;
	$form_database_settings = $general_settings_form->get_form_database_settings() ;
	$general_settings_form->set_settings_fields_option_group(BZMNDSGN_SETTINGS_GROUP);
	$general_settings_form->set_settings_fields_page(BZMNDSGN_SETTINGS_GROUP );

	// Google Analytics ID.
	$google_analytics_id = new text_input( 'Google Analytics ID', 'google_analytics_id','UA-NNNNNNNNN-N', $bzmndsgn_config_options_database['google_analytics_id'] );
	$general_settings_form->add_form_field( $google_analytics_id );

	// 404 log file prefix
	$four_zero_four_log_file_prefix = new text_input( '404 Log File Prefix', 'log_file_prefix','UA-NNNNNNNNN-N', $bzmndsgn_config_options_database['log_file_prefix'] );
	$general_settings_form->add_form_field( $four_zero_four_log_file_prefix );

	// var_dump($bzmndsgn_config_options_database);
	// print_r($form_database_settings);
	$general_settings_form->render_form();
}

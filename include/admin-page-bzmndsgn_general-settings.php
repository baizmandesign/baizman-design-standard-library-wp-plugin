<?php
/**
 * General settings.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

function bzmndsgn_general_settings () {

	_print_admin_settings_heading ('General Settings', 'Baizman Design Standard Library' ) ;

	$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

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

	$local_plugin_option_name_label = 'Local plugin option name' ;
	$local_plugin_option_name_input_name = 'local_plugin_option_name' ;
	$local_plugin_option_name = new text_input( $local_plugin_option_name_label, $local_plugin_option_name_input_name, 'option_name', $bzmndsgn_config_options_database[$local_plugin_option_name_input_name] );
	$local_plugin_option_name->set_help_text( 'This is the <code><small>option_name</small></code> in the MySQL database.' );

	$general_settings_form->add_form_field( $local_plugin_option_name );


	// var_dump($bzmndsgn_config_options_database);
	// print_r($form_database_settings);
	$general_settings_form->render_form();
}

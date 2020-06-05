<?php
/**
 * General settings.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

function bzmndsgn_general_settings () {

	_print_admin_settings_heading ('General Settings', 'Baizman Design Standard Library' ) ;

	$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

	// one setting: the local plugin setting key name
	// background color of the wp interface?

	$general_settings_form = new form ( 'general-settings' ) ;
	$form_database_settings = $general_settings_form->get_form_database_settings() ;
	$general_settings_form->set_settings_fields_option_group(BZMNDSGN_SETTINGS_GROUP);
	$general_settings_form->set_settings_fields_page(BZMNDSGN_SETTINGS_GROUP );


	// Google Analytics ID.
	$google_analytics_id = new text_input( 'Google Analytics ID', 'google_analytics_id','UA-NNNNNNNNN-N', $bzmndsgn_config_options_database['google_analytics_id'] );
	$general_settings_form->add_form_field( $google_analytics_id );

	// 404 log file prefix
	$four_zero_four_log_file_prefix = new text_input( '404 Log File Prefix', 'log_file_prefix','UA-NNNNNNNNN-N', $bzmndsgn_config_options_database['log_file_prefix'] );
	$general_settings_form->add_form_field( $four_zero_four_log_file_prefix );

	// Show correct background color prompt depending on the environment.

	$environment = _get_environment_type ( );

	$dashboard_field_label = '' ;
	$dashboard_field_input_name = '' ;

	switch ( $environment ) {
		case 'Local Development':
			$dashboard_field_label = 'Local development dashboard background';
			$dashboard_field_input_name = 'local_dashboard_background_color' ;
			break;

		case 'Development':
			$dashboard_field_label = 'Dev dashboard background';
			$dashboard_field_input_name = 'dev_dashboard_background_color' ;
			break;

		case 'Staging':
			$dashboard_field_label = 'Staging dashboard background';
			$dashboard_field_input_name = 'staging_dashboard_background_color' ;
			break;

		default:
			break;
	}

	if ( $dashboard_field_label && $dashboard_field_input_name ) {
		$dashboard_background_color = new text_input( $dashboard_field_label, $dashboard_field_input_name, 'english, hex, rgb, rgba, or hsl, hsla color', $bzmndsgn_config_options_database[$dashboard_field_input_name] );
		$dashboard_background_color->set_help_text( 'View <a href="https://htmlcolorcodes.com/" target="_blank" rel="noopener">html color codes</a> to obtain color values.' );
		$general_settings_form->add_form_field( $dashboard_background_color );
	}
	/*
	$production_dashboard_background_color = new text_input( 'Production dashboard background', 'english, hex, rgb, rgba, hsl, hsla color', $bzmndsgn_config_options_database['production_dashboard_background_color'] ) ;
	$general_settings_form->add_form_field($production_dashboard_background_color);
	*/
//	 var_dump($bzmndsgn_config_options_database);
//	print_r($form_database_settings);
	$general_settings_form->render_form();
}

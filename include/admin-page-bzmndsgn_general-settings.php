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

	// Show correct background color prompt depending on the environment.
	$environment = _get_environment_type ( );

	if ( $environment == 'Local Development' ) {
		$local_dashboard_background_color = new text_input( 'Local development dashboard background', 'local_dashboard_background_color','english, hex, rgb, rgba, or hsl, hsla color', $bzmndsgn_config_options_database['local_dashboard_background_color'] );
		$local_dashboard_background_color->set_help_text( 'View <a href="https://htmlcolorcodes.com/" target="_blank" rel="noopener">html color codes</a> to obtain color values.' );
		$general_settings_form->add_form_field( $local_dashboard_background_color );
	}

	if ( $environment == 'Development' ) {
		$dev_dashboard_background_color = new text_input( 'Dev dashboard background', 'dev_dashboard_background_color','english, hex, rgb, rgba, hsl, or hsla color', $bzmndsgn_config_options_database['dev_dashboard_background_color'] );
		$dev_dashboard_background_color->set_help_text( 'View <a href="https://htmlcolorcodes.com/" target="_blank" rel="noopener">html color codes</a> to obtain color values.' );
		$general_settings_form->add_form_field( $dev_dashboard_background_color );
	}

	if ( $environment == 'Staging' ) {
		$staging_dashboard_background_color = new text_input( 'Staging dashboard background', 'dev_dashboard_background_color', 'english, hex, rgb, rgba, or hsl, hsla color', $bzmndsgn_config_options_database['staging_dashboard_background_color'] );
		$staging_dashboard_background_color->set_help_text( 'View <a href="https://htmlcolorcodes.com/" target="_blank" rel="noopener">html color codes</a> to obtain color values.' );
		$general_settings_form->add_form_field( $staging_dashboard_background_color );
	}

	/*
	$production_dashboard_background_color = new text_input( 'Production dashboard background', 'english, hex, rgb, rgba, hsl, hsla color', $bzmndsgn_config_options_database['production_dashboard_background_color'] ) ;
	$general_settings_form->add_form_field($production_dashboard_background_color);
	*/
//	 var_dump($bzmndsgn_config_options_database);
//	print_r($form_database_settings);
	$general_settings_form->render_form();
}

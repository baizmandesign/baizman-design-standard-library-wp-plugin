<?php
/**
 * WP dashboard customization.
 * @package Baizman Design Standard Library
 * @version 0.1
 * FIXME: add setting for fixed column headings.
 */

function bzmndsgn_dashboard ( ) {
	_print_admin_settings_heading ('WP Dashboard Customization') ;

	$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

	$dashboard_settings_form = new form ( 'dashboard_settings' ) ;
	$dashboard_settings_form->set_settings_fields_option_group(BZMNDSGN_SETTINGS_GROUP);
	$dashboard_settings_form->set_settings_fields_page(BZMNDSGN_SETTINGS_GROUP );

	// Show correct background color prompt depending on the environment.

	$environment = _get_environment_type ( );

	$dashboard_background_color_field_label = '' ;
	$dashboard_background_color_field_input_name = '' ;

	switch ( $environment ) {
		case 'Local Development':
			$dashboard_background_color_field_label = 'Local development dashboard background';
			$dashboard_background_color_field_input_name = 'local_dashboard_background_color' ;
			break;

		case 'Development':
			$dashboard_background_color_field_label = 'Dev dashboard background';
			$dashboard_background_color_field_input_name = 'dev_dashboard_background_color' ;
			break;

		case 'Staging':
			$dashboard_background_color_field_label = 'Staging dashboard background';
			$dashboard_background_color_field_input_name = 'staging_dashboard_background_color' ;
			break;

		default:
			break;
	}

	if ( $dashboard_background_color_field_label && $dashboard_background_color_field_input_name ) {
		$dashboard_background_color = new text_input( $dashboard_background_color_field_label, $dashboard_background_color_field_input_name, 'english, hex, rgb, rgba, hsl, or hsla color', $bzmndsgn_config_options_database[$dashboard_background_color_field_input_name] );
		$dashboard_background_color->set_field_help_text( 'View <a href="https://htmlcolorcodes.com/" target="_blank" rel="noopener">html color codes</a> to obtain color values.' );
		$dashboard_settings_form->add_form_field( $dashboard_background_color );
	}
	/*
	$production_dashboard_background_color = new text_input( 'Production dashboard background', 'english, hex, rgb, rgba, hsl, hsla color', $bzmndsgn_config_options_database['production_dashboard_background_color'] ) ;
	$general_settings_form->add_form_field($production_dashboard_background_color);
	*/

	// Output form.
	$dashboard_settings_form->render_form();

}
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
			$dashboard_background_color_field_label = 'Local development';
			$dashboard_background_color_field_input_name = 'local_dashboard_background_color' ;
			break;

		case 'Development':
			$dashboard_background_color_field_label = 'Dev';
			$dashboard_background_color_field_input_name = 'dev_dashboard_background_color' ;
			break;

		case 'Staging':
			$dashboard_background_color_field_label = 'Staging';
			$dashboard_background_color_field_input_name = 'staging_dashboard_background_color' ;
			break;

		default:
			break;
	}

	$dashboard_background_color_field_label .= ' dashboard background color:' ;

	if ( $dashboard_background_color_field_label && $dashboard_background_color_field_input_name ) {
		$dashboard_background_color = new color ( $dashboard_background_color_field_label, $dashboard_background_color_field_input_name,  $bzmndsgn_config_options_database[$dashboard_background_color_field_input_name] );
		$dashboard_background_color->set_field_help_text('Or try <a href="javascript:set_wp_bg_color(\'aliceblue\')">aliceblue</a>, <a href="javascript:set_wp_bg_color(\'ivory\')">ivory</a>, <a href="javascript:set_wp_bg_color(\'seashell\')">seashell</a>, or <a href="javascript:set_wp_bg_color(\'ghostwhite\')">ghostwhite</a>.');
		$dashboard_background_color->set_field_id ( 'wp_dashboard_color' );
		$dashboard_settings_form->add_form_field( $dashboard_background_color );
	}
	/*
	$production_dashboard_background_color = new text_input( 'Production dashboard background', 'english, hex, rgb, rgba, hsl, hsla color', $bzmndsgn_config_options_database['production_dashboard_background_color'] ) ;
	$general_settings_form->add_form_field($production_dashboard_background_color);
	*/

	$show_site_name = new checkbox ('Replace thank-you text with site name?',
		'checkbox-show_site_name',
		$bzmndsgn_config_options_database['checkbox-show_site_name']
	) ;
	$show_site_name->set_label_help_text('This appears in the lower left corner of every page.');
	$dashboard_settings_form->add_form_field ( $show_site_name ) ;

	$show_marketing = new checkbox ('Replace version number with branding?',
		'checkbox-show_marketing',
		$bzmndsgn_config_options_database['checkbox-show_marketing']
	) ;
	$show_marketing->set_label_help_text('This appears in the lower right corner of every page.');
	$show_marketing->set_field_help_text('Enter branding information in the field below.') ;
	$dashboard_settings_form->add_form_field ($show_marketing) ;

	$branding_info = new text_area (
		'Enter branding information:',
		'textarea-branding_info',
		'Your Company, Inc.',
		$bzmndsgn_config_options_database['textarea-branding_info']) ;
	// $branding_info->set_field_help_text('Enter one tag per line, no angle brackets (&lt;&gt;) necessary.');
	$branding_info->set_show_label( false ) ;
	$branding_info->set_rows ( 2 );
	$dashboard_settings_form->add_form_field ($branding_info) ;


	// Output form.
	$dashboard_settings_form->render_form();

}
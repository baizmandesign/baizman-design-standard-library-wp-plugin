<?php
/**
 * WP update configuration.
 * @package Baizman Design Standard Library
 * @version 0.1
 */


//	$wp_updates_form = new form ( 'wp_updates' ) ;
//	$wp_updates_form->set_settings_fields_option_group(BZMNDSGN_SETTINGS_GROUP);
//	$wp_updates_form->set_settings_fields_page(BZMNDSGN_SETTINGS_GROUP );


namespace baizman_design ;

function bzmndsgn_wp_updates ( ) {
	_print_admin_settings_heading( 'WP Update Settings', 'Baizman Design Standard Library' );

	$bzmndsgn_config_options_database = get_option( BZMNDSGN_CONFIG_OPTIONS );

	$wordpress_updates_settings_form = new form ( 'wordpress_update_settings' ) ;
	$wordpress_updates_settings_form->set_settings_fields_option_group(BZMNDSGN_SETTINGS_GROUP);
	$wordpress_updates_settings_form->set_settings_fields_page(BZMNDSGN_SETTINGS_GROUP );

	$wp_auto_update_core = '&ndash;';
	if ( defined ('WP_AUTO_UPDATE_CORE') ) {
		$wp_auto_update_core = WP_AUTO_UPDATE_CORE;
		// set explicit false value
		if ( WP_AUTO_UPDATE_CORE === false ) {
			$wp_auto_update_core = '0';
		}
	}

	$automatic_updater_disabled = '&ndash;';
	if ( defined ('AUTOMATIC_UPDATER_DISABLED') ) {
		$automatic_updater_disabled = AUTOMATIC_UPDATER_DISABLED;
		// set explicit false value
		if ( AUTOMATIC_UPDATER_DISABLED === false ) {
			$automatic_updater_disabled = '0';
		}
	}
	printf( '<h4>%s</h4>','Constants from wp-config.php') ;
	printf ('<p><span class="constant_name">WP_AUTO_UPDATE_CORE:</> %s</p>', $wp_auto_update_core ) ;
	printf ('<p><span class="constant_name">AUTOMATIC_UPDATER_DISABLED:</> %s</p>', $automatic_updater_disabled ) ;

	printf('<p><strong>Note:</strong> some of these settings may conflict. Be careful.</p>') ;

	// Enable automatic updates for all plugins.
	$enable_automatic_plugin_updates = new checkbox( 'Enable automatic updates for all plugins?','checkbox-enable_automatic_plugin_updates',$bzmndsgn_config_options_database['checkbox-enable_automatic_plugin_updates']) ;
	$wordpress_updates_settings_form->add_form_field ($enable_automatic_plugin_updates) ;

	// Suppress email notifications for automatic plugin updates.
	$disable_plugin_auto_update_email_notifications = new checkbox( 'Suppress email notifications for automatic plugin updates?','checkbox-disable_plugin_auto_update_email_notifications',$bzmndsgn_config_options_database['checkbox-disable_plugin_auto_update_email_notifications']) ;
	$wordpress_updates_settings_form->add_form_field ($disable_plugin_auto_update_email_notifications) ;

	// Enable automatic updates for all themes.
	$enable_automatic_theme_updates = new checkbox( 'Enable automatic updates for all themes?','checkbox-enable_automatic_theme_updates',$bzmndsgn_config_options_database['checkbox-enable_automatic_theme_updates']) ;
	$wordpress_updates_settings_form->add_form_field ($enable_automatic_theme_updates) ;

	// Suppress email notifications for automatic theme updates.
	$disable_theme_auto_update_email_notifications = new checkbox( 'Suppress email notifications for automatic theme updates?','checkbox-disable_theme_auto_update_email_notifications',$bzmndsgn_config_options_database['checkbox-disable_theme_auto_update_email_notifications']) ;
	$wordpress_updates_settings_form->add_form_field ($disable_theme_auto_update_email_notifications) ;

	// Disable all automatic updates.
	$disable_all_updates = new checkbox ('Disable all core, plugin, and theme updates?',
		'checkbox-disable_all_updates',
		$bzmndsgn_config_options_database['checkbox-disable_all_updates']
	) ;
	if ( defined ( 'AUTOMATIC_UPDATER_DISABLED' ) && AUTOMATIC_UPDATER_DISABLED === true ) {
		$disable_all_updates->set_is_disabled(true);
		$disable_all_updates->set_field_help_text('Constant AUTOMATIC_UPDATER_DISABLED is set in wp-config.php.');
	}
	$wordpress_updates_settings_form->add_form_field ( $disable_all_updates ) ;

	// Suppress email notifications for automatic core updates.
	$disable_core_auto_update_email_notifications = new checkbox( 'Suppress email notifications for automatic core updates?','checkbox-disable_core_auto_update_email_notifications',$bzmndsgn_config_options_database['checkbox-disable_core_auto_update_email_notifications']) ;
	$disable_core_auto_update_email_notifications->set_label_help_text('An email will still be sent if a core update fails.');
	$wordpress_updates_settings_form->add_form_field ($disable_core_auto_update_email_notifications) ;

	// Enable core updates only.
	$enable_core_updates_only = new checkbox ('Enable core updates only?',
		'checkbox-enable_core_updates_only',
		$bzmndsgn_config_options_database['checkbox-enable_core_updates_only']
	) ;

	$wordpress_updates_settings_form->add_form_field ( $enable_core_updates_only ) ;

	// Disable translation updates.
	$disable_translation_updates = new checkbox ('Disable translation updates?',
		'checkbox-disable_translation_updates',
		$bzmndsgn_config_options_database['checkbox-disable_translation_updates']
	) ;

	$wordpress_updates_settings_form->add_form_field ( $disable_translation_updates ) ;

	// For Developers: to enable automatic updates even if a VCS folder (.git, .hg, .svn, etc.) was found in the WordPress directory or any of its parent directories.
	$enable_updates_if_vcs_present = new checkbox ('Enable automatic updates if a VCS folder (.git, .hg, .svn, etc.) was found in the WordPress directory or any of its parent directories?',
		'checkbox-enable_updates_if_vcs_present',
		$bzmndsgn_config_options_database['checkbox-enable_updates_if_vcs_present']
	) ;

	$wordpress_updates_settings_form->add_form_field ( $enable_updates_if_vcs_present ) ;

	// Output form.
	$wordpress_updates_settings_form->render_form();

	_print_admin_settings_footer() ;

}
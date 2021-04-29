<?php
/**
 * General settings.
 */

namespace baizman_design ;

$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

// Suppress auto-update email notifications for plugins.
if ( _is_enabled ( 'checkbox-disable_plugin_auto_update_email_notifications', $bzmndsgn_config_options_database ) ) {
	add_filter( 'auto_plugin_update_send_email', '__return_false' );
}

// Suppress auto-update email notifications for themes.
if ( _is_enabled ( 'checkbox-disable_theme_auto_update_email_notifications', $bzmndsgn_config_options_database ) ) {
	add_filter( 'auto_theme_update_send_email', '__return_false' );
}

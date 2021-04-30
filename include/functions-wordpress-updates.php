<?php
/**
 * @package Baizman Design Standard Library
 * @link https://wordpress.org/support/article/configuring-automatic-background-updates/
 * @version 0.1
 */

namespace baizman_design ;

$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

// Enable automatic updates for all plugins.
if ( _is_enabled ( 'checkbox-enable_automatic_plugin_updates', $bzmndsgn_config_options_database ) ) {
	add_filter( 'auto_update_plugin', '__return_true' );}

// Suppress email notifications for automatic plugin updates.
if ( _is_enabled ( 'checkbox-disable_plugin_auto_update_email_notifications', $bzmndsgn_config_options_database ) ) {
	add_filter( 'auto_plugin_update_send_email', '__return_false' );
}

// Enable automatic updates for all themes.
if ( _is_enabled ( 'checkbox-enable_automatic_theme_updates', $bzmndsgn_config_options_database ) ) {
	add_filter( 'auto_update_theme', '__return_true' );}

// Suppress email notifications for automatic theme updates.
if ( _is_enabled ( 'checkbox-disable_theme_auto_update_email_notifications', $bzmndsgn_config_options_database ) ) {
	add_filter( 'auto_theme_update_send_email', '__return_false' );
}

// Disable all automatic updates.
if ( _is_enabled ( 'checkbox-disable_all_updates', $bzmndsgn_config_options_database ) ) {
	add_filter( 'automatic_updater_disabled', '__return_true' );
}

// Suppress email notifications for automatic core updates.
// Note: an email will still be sent if the update fails.
// https://www.wpbeginner.com/wp-tutorials/how-to-disable-automatic-update-email-notification-in-wordpress/
if ( _is_enabled ( 'checkbox-disable_plugin_auto_update_email_notifications', $bzmndsgn_config_options_database ) ) {
	add_filter ('auto_core_update_send_email', function ( $send, $type, $core_update, $result ) {
		if ( ! empty( $type ) && $type == 'success' ) {
			return false;
		}
		return true;
	}, 10, 4 );
}

// Enable core updates only.
if ( _is_enabled ( 'checkbox-enable_core_updates_only', $bzmndsgn_config_options_database ) ) {
	add_filter( 'auto_update_core', '__return_true' );
}

// Disable translation updates.
if ( _is_enabled ( 'checkbox-disable_translation_updates', $bzmndsgn_config_options_database ) ) {
	add_filter( 'auto_update_translation', '__return_false' );
}

// For Developers: to enable automatic updates even if a VCS folder (.git, .hg, .svn, etc.) was found in the WordPress directory or any of its parent directories.
if ( _is_enabled ( 'checkbox-enable_updates_if_vcs_present', $bzmndsgn_config_options_database ) ) {
	add_filter( 'automatic_updates_is_vcs_checkout', '__return_false', 1 );
}

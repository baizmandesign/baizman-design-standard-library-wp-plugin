<?php
/**
 * @package Baizman Design Standard Library
 * @version 0.1
 */

/******************************************************************************/

defined ( 'ABSPATH' ) or die ( 'This file cannot be run outside of WordPress.' ) ;

use baizman_design as bd;

bd\_require_once_folder ("class" ) ;

/*
 * User-configurable settings and admin page functions.
 */

// Per-site database fields and default values.

$site_defaults = array (
	'google_analytics_id' => '',
	'log_file_prefix' => str_replace ( ' ', '-', strtolower ( get_bloginfo ('name') ) ),
	) ;

define ( 'SITE_OPTIONS_DEFAULTS', $site_defaults ) ;

if ( BZMNDSGN_IS_MULTISITE ) {
	// Global database fields and default values.
	$network_defaults = array (
	) ;
	define ( 'NETWORK_OPTIONS_DEFAULTS', $network_defaults ) ;
}

/* "The thing to know is that the first argument is the name required to get your code to fire, not what file the code is in."
- https://developer.wordpress.org/reference/functions/register_activation_hook/
*/
$plugin_file_path = trailingslashit ( dirname ( __FILE__ ) ) . 'standard-library.php' ;

register_activation_hook ( $plugin_file_path, 'bzmndsgn_config_set_default_options_array' ) ;

register_activation_hook ( $plugin_file_path, 'bzmndsgn_network_config_set_default_options_array' ) ;

/**
 * Set the per-site default options.
 */
function bzmndsgn_config_set_default_options_array ( ) {

	update_option ( BZMNDSGN_CONFIG_OPTIONS, SITE_OPTIONS_DEFAULTS ) ;

}

if ( BZMNDSGN_IS_MULTISITE ) {
	/**
	 * Set the network default options.
	 */
	function bzmndsgn_network_config_set_default_options_array() {

		add_site_option( BZMNDSGN_MULTISITE_CONFIG_OPTIONS, NETWORK_OPTIONS_DEFAULTS );

	}
}

/**
 * Define the per-site admin menu and submenus.
 */
function bzmndsgn_config_admin_menu ( ) {

	global $submenu ;

	/* Top-level menu. */

	$parent_menu = new menu ( BZMNDSGN_AUTHOR_COMPANY. ' Standard Library', 'bzmndsgn_general_settings','dashicons-admin-generic' ) ;

	/* General configuration page. */
	$general_settings_submenu = new submenu ( 'General Settings', 'bzmndsgn_general_settings' ) ;
	$parent_menu->add_submenu_item ( $general_settings_submenu ) ;

	/* 404 error log. */
	$error_404_log_submenu = new submenu ( '404 Error Log', 'bzmndsgn_404_error_log' ) ;
	$parent_menu->add_submenu_item ( $error_404_log_submenu ) ;

	/* 404 error log. */
	$dashboard_submenu = new submenu ( 'Dashboard', 'bzmndsgn_dashboard' ) ;
	$parent_menu->add_submenu_item ( $dashboard_submenu ) ;

	/* Advanced submenu. */
	if ( WP_DEBUG ) {
		$advanced_submenu = new submenu( 'Advanced', 'bzmndsgn_advanced_settings') ;
		$parent_menu->add_submenu_item ( $advanced_submenu ) ;
	}
	$parent_menu->render_menu ( ) ;

}
if ( BZMNDSGN_SHOW_DASHBOARD_INTERFACE )
	add_action ( 'admin_menu', 'bzmndsgn_config_admin_menu', 1 ) ;

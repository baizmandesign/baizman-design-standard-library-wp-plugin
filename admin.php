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
	'local_dashboard_background_color' => BZMNDSGN_LOCAL_BACKGROUND_COLOR,
	'dev_dashboard_background_color' => BZMNDSGN_DEV_BACKGROUND_COLOR,
	'staging_dashboard_background_color' => BZMNDSGN_STAGING_BACKGROUND_COLOR,
	'production_dashboard_background_color' => '',
	) ;

define ( 'SITE_OPTIONS_DEFAULTS', $site_defaults ) ;

/* "The thing to know is that the first argument is the name required to get your code to fire, not what file the code is in."
- https://developer.wordpress.org/reference/functions/register_activation_hook/
*/
$plugin_file_path = trailingslashit ( dirname ( __FILE__ ) ) . 'baizman-design-standard-library.php' ;

//echo '$plugin_file_path: ' . $plugin_file_path ;

register_activation_hook ( $plugin_file_path, 'bzmndsgn_config_set_default_options_array' ) ;

/**
 * Set the per-site default options.
 */
function bzmndsgn_config_set_default_options_array ( ) {

	file_put_contents( 'activated.txt','bzmndsgn_config_set_default_options_array') ;
	update_option ( BZMNDSGN_CONFIG_OPTIONS, SITE_OPTIONS_DEFAULTS ) ;

}

/**
 * Register per-site settings.
 */
function bzmndsgn_config_settings ( ) {
	// General Settings.

	foreach ( SITE_OPTIONS_DEFAULTS as $key => $value ) {
		register_setting ( BZMNDSGN_SETTINGS_GROUP, $key ) ;

	}
}
add_action ( 'admin_init', 'bzmndsgn_config_settings' ) ;

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
if ( BZMNDSGN_SHOW_DASHBOARD_INTERFACE ):
	add_action ( 'admin_menu', 'bzmndsgn_config_admin_menu', 1 ) ;
endif;

/**
 * Save per-site settings to database.
 */
function bzmndsgn_save_config_settings ( ) {
	// Check that user has proper security level.
	if ( ! current_user_can ( 'manage_options' ) ) {
		wp_die ( 'You do not have permission to update these settings.' ) ;
	}

	// Check referrer.
	check_admin_referer ( 'bzmndsgn_save_config' ) ;

	// Get current settings.
	$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS ) ;

	// Store updated settings.
	$updated_options = array ( ) ;

	foreach ( $bzmndsgn_config_options_database as $option => $value ) {
		if ( isset ( $_POST[$option] ) ) {
			$updated_options[$option] = stripslashes_deep ( $_POST[$option] ) ; // sanitize_option, sanitize_textarea_field
		}
	}

	// Update the options.
	$merged_options = wp_parse_args ( $updated_options, $bzmndsgn_config_options_database ) ;
	update_option ( BZMNDSGN_CONFIG_OPTIONS, $merged_options ) ;

	// Get the referring page from the query string ($_GET['page']).
	$referrer = $_POST['_wp_http_referer'] ;
	$link_parts = parse_url ( $referrer ) ;
	$query = $link_parts['query'] ;
	parse_str ( $query, $query_array ) ;

	// Redirect with success=1 query string.
	wp_redirect (
		add_query_arg (
			array (
				'page' => $query_array['page'],
				'message' => '1',
			),
			admin_url ( 'admin.php' )
		)
	);

	exit ;

}
add_action ( 'admin_post_update', 'bzmndsgn_save_config_settings' ) ;

/* Multi-site stuff. */

if ( BZMNDSGN_IS_MULTISITE ) {

	// Global database fields and default values.
	$network_defaults = array (
	) ;
	define ( 'NETWORK_OPTIONS_DEFAULTS', $network_defaults ) ;

	register_activation_hook( $plugin_file_path, 'bzmndsgn_network_config_set_default_options_array' );

	/**
	 * Set the network default options.
	 */
	function bzmndsgn_network_config_set_default_options_array() {

		add_site_option( BZMNDSGN_MULTISITE_CONFIG_OPTIONS, NETWORK_OPTIONS_DEFAULTS );

	}


	/**
	 * Register global settings.
	 */
	function bzmndsgn_network_config_settings ( ) {
		// Network Settings.

		foreach ( NETWORK_OPTIONS_DEFAULTS as $key => $value ) {
			register_setting ( BZMNDSGN_NETWORK_SETTINGS_GROUP, $key ) ;

		}
	}
	add_action ( 'admin_init', 'bzmndsgn_network_config_settings' ) ;

	/**
	 * Save global settings to database.
	 */
	function bzmndsgn_save_network_config_settings ( ) {

		// Check that user has proper security level.
		if ( ! current_user_can ('manage_network_options' ) ) {
			wp_die ( 'You do not have permission to update the network-level settings.' ) ;
		}

		// Check referrer.
		check_admin_referer ( 'bzmndsgn_save_network_config' ) ;

		// Get current settings.
		$bzmndsgn_network_config_options_database = get_site_option ( BZMNDSGN_MULTISITE_CONFIG_OPTIONS ) ;

		// Store updated settings.
		$updated_options = array ( ) ;

		foreach ( $bzmndsgn_network_config_options_database as $option => $value ) {
			if ( isset ( $_POST[$option] ) ) {
				$updated_options[$option] = stripslashes_deep ( $_POST[$option] ) ; // sanitize_option, sanitize_textarea_field
			}
		}

		// Update the options.
		$merged_options = wp_parse_args ( $updated_options, $bzmndsgn_network_config_options_database ) ;
		update_site_option ( BZMNDSGN_MULTISITE_CONFIG_OPTIONS, $merged_options ) ;

		// Get the referring page from the query string ($_GET['page']).
		$referrer = $_POST['_wp_http_referer'] ;
		$link_parts = parse_url ( $referrer ) ;
		$query = $link_parts['query'] ;
		parse_str ( $query, $query_array ) ;

		// Redirect with success=1 query string.
		wp_redirect (
			add_query_arg (
				array (
					'page' => $query_array['page'],
					'message' => '1',
				),
				network_admin_url ( 'admin.php' )
			)
		);

		exit ;

	}
	add_action ( 'network_admin_edit_bzmndsgn_save_network_config_settings', 'bzmndsgn_save_network_config_settings' ) ;

}

<?php
/**
 * Baizman Design Standard Library
 *
 * @author          Baizman Design
 * @package         Baizman Design Standard Library
 * @version         1.2.2
 *
 * @wordpress-plugin
 * Plugin Name:     Baizman Design Standard Library
 * Plugin URI:      https://bitbucket.org/baizmandesign/baizman-design-standard-library-wp-plugin/
 * Description:     A standard set of frequently desired WordPress features in a customizable interface.
 * Author:          Baizman Design
 * Version:         1.2.2
 * Author URI:      https://baizmandesign.com
 * License:         GPLv2
 * Update URI:      https://wp.baizmandesign.com/bdsl.php
 */
namespace baizman_design ;

use baizman_design\page\advanced_config;
use baizman_design\page\content_sanitizer;
use baizman_design\page\dashboard_config;
use baizman_design\page\email_config;
use baizman_design\page\error_log;
use baizman_design\page\general_settings;
use baizman_design\page\wp_constants;
use baizman_design\page\wp_updates;

defined( 'ABSPATH' ) or die ( 'This file cannot be run outside of WordPress.' );

define( 'BZMNDSGN_DEBUG_LOG', 'debug.log' );

define( 'BZMNDSGN_DOCUMENT_ROOT_URI', trailingslashit( $_SERVER['DOCUMENT_ROOT'] ) );

/* Automatically load classes. */
// https://www.php.net/manual/en/function.spl-autoload-register.php
// https://www.smashingmagazine.com/2015/05/how-to-use-autoloading-and-a-plugin-container-in-wordpress-plugins/
spl_autoload_register( function ( $class_name ) {
	$original_class_name = $class_name;
	/* divvy up class name into separate parts. */
	$class_path_parts = explode('\\',$class_name) ;
	// remove namespace name from array.
	array_shift($class_path_parts) ;

	// build new array of path parts.
	$class_file = [] ;
	$class_file[] = untrailingslashit ( plugin_dir_path( __FILE__ ) ) ;
	$class_file[] = 'class' ;
	$class_path_parts_last = array_pop ($class_path_parts) ;
	if ( ! empty($class_path_parts)) { // if we have more elements, add them to the $class_file array.
		$class_file = array_merge ( $class_file, $class_path_parts ) ;
	}

	$class_file[] = sprintf ('class.%1$s.php', $class_path_parts_last);

	$class_file_path = implode ( DIRECTORY_SEPARATOR, $class_file ) ;
//	printf ("class_file_path: %s\n",$class_file_path) ;

	/* We check for the existence of the file in our plugin, because this spl_autoload_register() tries to autoload _all_ classes. */
	if ( false !== strpos( $original_class_name, __NAMESPACE__.'\\' ) ) {
		if ( file_exists( $class_file_path ) ) {
			require_once ( $class_file_path );
			//if ( method_exists ($original_class_name,'add_hooks') ) {
			//	$original_class_name::add_hooks() ;
			//}
		}
	}

} );

/**
 * Initialize plugin. Create new bdsl() object.
 * @return void
 */
function bdsl_init() {

	$plugin = new bdsl ( ) ;
	$plugin['is_multisite'] = is_multisite();
	if ( $plugin['is_multisite'] ) {
		$plugin['multisite_network_name'] = get_network()->site_name;
	}
	$plugin['document_root_uri'] = trailingslashit( $_SERVER['DOCUMENT_ROOT'] );
	/* https://developer.wordpress.org/reference/functions/home_url/ */
	/* https://developer.wordpress.org/reference/functions/get_home_url/ */
	$plugin['document_root_url'] = trailingslashit( home_url() );
	/* Note: this only retrieves the *child* theme path, not the parent theme. */
	/* https://developer.wordpress.org/reference/functions/get_stylesheet_directory_uri/ */
	$plugin['theme_folder_uri'] = trailingslashit( get_stylesheet_directory() );
	$plugin['theme_folder_url'] = trailingslashit( get_stylesheet_directory_uri() );
	$plugin['plugin_folder_uri'] = trailingslashit( plugin_dir_path( __FILE__ ) );
	$plugin['plugin_folder_url'] = trailingslashit( plugin_dir_url( __FILE__ ) );

	/* Multisite configuration variables. */
	if ( $plugin['is_multisite'] ) {
		$network = get_network();
		$plugin['multisite_network_name'] = $network->site_name;
	}

	$plugin->add_login_hooks() ;

	shortcodes::load_shortcodes($plugin);

	/* General configuration page. */
	$general_settings = new general_settings ( $plugin, 'General Settings', bdsl::parent_menu_slug, true ) ;

	/* Content Sanitizers configuration page. */
	$content_sanitizers = new content_sanitizer ( $plugin, 'Content Sanitizers', bdsl::prefix.'_content_sanitizers' );

	/* Dashboard configuration page. */
	$dashboard_configuration = new dashboard_config ( $plugin, 'Dashboard', bdsl::prefix.'_dashboard' );

	/* Email configuration page. */
	/* Hide menu / page if Easy WP SMTP is active. */
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
	if ( ! is_plugin_active( 'easy-wp-smtp/easy-wp-smtp.php' ) ) {
		$email_config = new email_config ( $plugin, 'Email', bdsl::prefix.'_email' );
	}

	/* 404 error log page. */
	$error_log_404 = new error_log ( $plugin, '404 Error Log', bdsl::prefix.'_404_error_log' );

	/* WP constants page. */
	$wp_constants = new wp_constants ( $plugin, 'WP Constants', bdsl::prefix.'_wp_constants' );

	/* WP updates page. */
	$wp_updates = new wp_updates ( $plugin, 'WP Updates', bdsl::prefix.'_wp_updates' );

	/* Advanced page. */
	if ( bdsl::debug ) {
		$advanced = new advanced_config ( $plugin, 'Advanced', bdsl::prefix.'_advanced_settings' );
	}

	/**
	 * Include admin interface if we are viewing the backend.
	 */
	if ( is_admin() ) {

		/* "The thing to know is that the first argument is the name required to get your code to fire, not what file the code is in."
- https://developer.wordpress.org/reference/functions/register_activation_hook/
*/
		// Check for presence of Toolset.
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		$plugin['has_toolset'] = is_plugin_active( 'types/wpcf.php' );

		$plugin['plugin_file_path'] = __FILE__;

		// See https://developer.wordpress.org/reference/functions/register_activation_hook/ for the syntax.
		register_activation_hook( $plugin['plugin_file_path'], [ 'preferences', 'set_default_database_options' ] );
		if ( $plugin['is_multisite'] ) {
			register_activation_hook( $plugin['plugin_file_path'], [ 'preferences', 'set_default_network_database_options' ] );

		}

	}
	/**
	 * Include support for WP CLI, if appropriate.
	 */
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		cli\wp_cli::add_command( $plugin ) ;
	}

}
// "Adding the code via the add_action() filter is important as this will make our plugin overridable by using remove_action. An example use case would be a premium plugin overriding the free version." –SM article
add_action( 'plugins_loaded', __NAMESPACE__.'\bdsl_init' );

define( 'BZMNDSGN_NOT_FOUND_404_LOG_FILE', sprintf( '%s-%s', preferences::get_database_option('log_file_prefix'), '404.log' ) );

if ( bdsl::debug ) {
	$defined_constants      = get_defined_constants( true );
	$user_defined_constants = print_r( $defined_constants['user'], true );

	// TODO: this is running too soon. Add it to an appropriate hook? That didn't seem to work for me.
	echo( '<!-- ' );
	// Note: sprintf() won't print the contents of this variable.
	echo $user_defined_constants;
	echo( ' -->' );
	utility::debug( $user_defined_constants );
}



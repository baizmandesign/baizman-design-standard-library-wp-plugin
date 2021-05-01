<?php
/**
 Plugin Name: Baizman Design Standard Library
 Plugin URI: https://bitbucket.org/baizmandesign/baizman-design-wp-plugin-standard-library
 Description: A standard set of frequently desired WordPress features in a customizable interface.
 Author: Baizman Design
 Version: 1.0
 Author URI: https://baizmandesign.com
 License: GPLv2

 * @package Baizman Design Standard Library
 * @version 1.0
 */

defined( 'ABSPATH' ) or die ( 'This file cannot be run outside of WordPress.' );

define( 'BZMNDSGN_DEBUG', false );

define( 'BZMNDSGN_PLUGIN_NAME', 'Baizman Design Standard Library' );

define( 'BZMNDSGN_DEBUG_LOG', 'debug.log' );

define( 'BZMNDSGN_DOCUMENT_ROOT_URI', trailingslashit( $_SERVER['DOCUMENT_ROOT'] ) );

/* https://developer.wordpress.org/reference/functions/home_url/ */
/* https://developer.wordpress.org/reference/functions/get_home_url/ */
define( 'BZMNDSGN_DOCUMENT_ROOT_URL', trailingslashit( home_url() ) );

/* Note: this only retrieves the *child* theme path, not the parent theme. */
/* https://developer.wordpress.org/reference/functions/get_stylesheet_directory_uri/ */
define( 'BZMNDSGN_THEME_FOLDER_URI', trailingslashit( get_stylesheet_directory() ) );

define( 'BZMNDSGN_THEME_FOLDER_URL', trailingslashit( get_stylesheet_directory_uri() ) );

define( 'BZMNDSGN_PLUGIN_FOLDER_URI', trailingslashit( plugin_dir_path( __FILE__ ) ) );

define( 'BZMNDSGN_PLUGIN_FOLDER_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

define( 'BZMNDSGN_PLUGIN_ADMIN_URI', BZMNDSGN_PLUGIN_FOLDER_URI . 'admin.php' );

define( 'BZMNDSGN_AUTHOR_NAME', 'Saul Baizman' );

define( 'BZMNDSGN_AUTHOR_EMAIL', 'saul@baizmandesign.com' );

define( 'BZMNDSGN_SUPPORT_EMAIL', 'support@baizmandesign.com' );

define( 'BZMNDSGN_AUTHOR_PHONE', '1-617-863-7165' );

define( 'BZMNDSGN_AUTHOR_COMPANY', 'Baizman Design' );

define( 'BZMNDSGN_AUTHOR_COMPANY_URL', 'https://baizmandesign.com' );

define( 'BZMNDSGN_CONFIG_OPTIONS', 'bzmndsgn_config_options' );

define( 'BZMNDSGN_SETTINGS_GROUP', 'bzmndsgn-standard-library-plugin-settings-group' );

define( 'BZMNDSGN_NETWORK_SETTINGS_GROUP', 'bzmndsgn-standard-library-plugin-network-settings-group' );

define( 'BZMNDSGN_IS_MULTISITE', is_multisite() );

define( 'BZMNDSGN_SHOW_DASHBOARD_WIDGET', true );

define( 'BZMNDSGN_NAMESPACE', 'baizman_design' );

/* Multisite constants. */
if ( BZMNDSGN_IS_MULTISITE ) {
	define( 'BZMNDSGN_MULTISITE_CONFIG_OPTIONS', 'bzmndsgn_multisite_config_options' );

	$network = get_network();
	define( 'BZMNDSGN_MULTISITE_NETWORK_NAME', $network->site_name );
}

/* Automatically load classes. */
// https://www.php.net/manual/en/function.spl-autoload-register.php
// https://www.smashingmagazine.com/2015/05/how-to-use-autoloading-and-a-plugin-container-in-wordpress-plugins/
spl_autoload_register( function ( $class_name ) {
	$original_class_name = $class_name;
//	printf ("original_class_name: %s\n",$original_class_name) ;
	/* divvy up class name into separate parts. */
	$class_path_parts = explode('\\',$class_name) ;
	// remove namespace from array
	array_shift($class_path_parts) ;

	// build new array of path parts.
	$class_file = [] ;
	$class_file[] = untrailingslashit ( BZMNDSGN_PLUGIN_FOLDER_URI ) ;
	$class_file[] = 'class' ;
	for ( $path_part = 0 ; $path_part < count($class_path_parts); $path_part++ ) {

		// on the last path element. format the correct filename.
		if ( $path_part == (count($class_path_parts)-1) ) {
			$class_file[] = sprintf ('class.%s.php', $class_path_parts[$path_part]) ;
		}
		else {
			$class_file[] = $class_path_parts[$path_part] ;
		}
	}

	$class_file_path = implode ( DIRECTORY_SEPARATOR, $class_file ) ;
//	printf ("class_file_path: %s\n",$class_file_path) ;

	/* We check for the existence of the file in our plugin, because this spl_autoload_register() tries to autoload _all_ classes. */
	if ( false !== strpos( $original_class_name, BZMNDSGN_NAMESPACE.'\\' ) ) {
		if ( file_exists( $class_file_path ) ) {
			require_once( $class_file_path );
		}
	}

} );

if ( ! function_exists( '_require_once_folder' ) ):
	/**
	 * Include all *.php files in the given subfolder.
	 *
	 * @param $folder
	 */
	function _require_once_folder( $folder ) {
		/* Note: the path needs the absolute path, not relative path. */
		/* Or my relative path might just have been wrong! */
		foreach ( glob( __DIR__ . "/{$folder}/*.php" ) as $filename ) {
			require_once( $filename );
		}
	}
endif;

_require_once_folder( "include" );

/* Load per-site plugin settings. */
$bzmndsgn_config_options = get_option( BZMNDSGN_CONFIG_OPTIONS );

define( 'BZMNDSGN_NOT_FOUND_404_LOG_FILE', sprintf( '%s-%s', $bzmndsgn_config_options['log_file_prefix'], '404.log' ) );

if ( BZMNDSGN_DEBUG ) {
	$defined_constants      = get_defined_constants( true );
	$user_defined_constants = print_r( $defined_constants['user'], true );

	echo( '<!-- ' );
	// Note: sprintf() won't print the contents of this variable.
	echo $user_defined_constants;
	echo( ' -->' );
	_bzmndsgn_debug( $user_defined_constants );
}

/**
 * Include admin interface if we are viewing the backend.
 */
if ( is_admin() && file_exists( BZMNDSGN_PLUGIN_ADMIN_URI ) ) {
	require_once( BZMNDSGN_PLUGIN_ADMIN_URI );
}

/**
 * Include support for WP CLI, if appropriate.
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	$wp_cli = new baizman_design\cli\wp_cli ( ) ;
}


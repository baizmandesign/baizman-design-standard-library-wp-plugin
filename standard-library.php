<?php
/**
 * @package Baizman Design Standard Library
 * @version 0.1
 */
/*
 * TODO
 * + add namespace
 * + after commit, regenerate documentation
 *
 */

namespace {
	defined ( 'ABSPATH' ) or die ( 'This file cannot be run outside of WordPress.' );
}

namespace baizman_design {

	define ( 'BZMNDSGN_DEBUG', false ) ;

	define ( 'BZMNDSGN_DEBUG_LOG', 'debug.log' ) ;

	define ( 'BZMNDSGN_DOCUMENT_ROOT_URI', trailingslashit ( $_SERVER['DOCUMENT_ROOT'] ) ) ;

	/* https://developer.wordpress.org/reference/functions/home_url/ */
	/* https://developer.wordpress.org/reference/functions/get_home_url/ */
	define ( 'BZMNDSGN_DOCUMENT_ROOT_URL', trailingslashit ( home_url ( ) ) ) ;

	/* Note: this only retrieves the *child* theme path, not the parent theme. */
	/* https://developer.wordpress.org/reference/functions/get_stylesheet_directory_uri/ */
	define ( 'BZMNDSGN_THEME_FOLDER_URI', trailingslashit ( get_stylesheet_directory ( ) ) ) ;

	define ( 'BZMNDSGN_THEME_FOLDER_URL', trailingslashit ( get_stylesheet_directory_uri ( ) ) ) ;

	define ( 'BZMNDSGN_PLUGIN_FOLDER_URI', trailingslashit ( dirname ( dirname (__FILE__) ) ) ) ;

	define ( 'BZMNDSGN_PLUGIN_FOLDER_URL', trailingslashit ( dirname ( plugin_dir_url ( __FILE__ ) ) ) ) ;

	define ( 'BZMNDSGN_LIBRARY_FOLDER_URI', trailingslashit ( plugin_dir_path ( __FILE__ ) ) ) ;

	define ( 'BZMNDSGN_LIBRARY_FOLDER_URL', trailingslashit ( plugin_dir_url ( __FILE__ ) ) ) ;

	define ( 'BZMNDSGN_AUTHOR_NAME', 'Saul Baizman' ) ;

	define ( 'BZMNDSGN_AUTHOR_EMAIL', 'saul@baizmandesign.com' ) ;

	define ( 'BZMNDSGN_SUPPORT_EMAIL', 'support@baizmandesign.com' ) ;

	define ( 'BZMNDSGN_AUTHOR_PHONE', '1-617-863-7165' ) ;

	define ( 'BZMNDSGN_AUTHOR_COMPANY', 'Baizman Design' ) ;

	define ( 'BZMNDSGN_AUTHOR_COMPANY_URL', 'https://baizmandesign.com' ) ;

	define ( 'BZMNDSGN_CONFIG_OPTIONS', 'bzmndsgn_config_options' ) ;

	define ( 'BZMNDSGN_IS_MULTISITE' , is_multisite ( ) ) ;

	define ( 'BZMNDSGN_SHOW_DASHBOARD_WIDGET', true ) ;

	/* Multisite constants. */
	if ( BZMNDSGN_IS_MULTISITE ) {
		define ( 'BZMNDSGN_MULTISITE_CONFIG_OPTIONS', 'bzmndsgn_multisite_config_options' ) ;

		$network = get_network() ;
		define ( 'BZMNDSGN_MULTISITE_NETWORK_NAME', $network->site_name ) ;
	}

	if ( ! function_exists ( '_require_once_folder' ) ):
		/**
		 * Include all *.php files in the given subfolder.
		 * @param $folder
		 */
		function _require_once_folder ( $folder ) {
			/* Note: the path needs the absolute path, not relative path. */
			/* Or my relative path might just have been wrong! */
			foreach ( glob (__DIR__ . "/{$folder}/*.php") as $filename )
			{
				require_once $filename;
			}
		}
	endif;

	_require_once_folder ("include" ) ;

	/* Load per-site plugin settings. */
	$bzmndsgn_config_options = get_option ( BZMNDSGN_CONFIG_OPTIONS ) ;

	define ( 'BZMNDSGN_NOT_FOUND_404_LOG_FILE', sprintf ('%s-%s',$bzmndsgn_config_options['log_file_prefix'], '404.log' ) ) ;

	if ( BZMNDSGN_DEBUG ) {
		$defined_constants = get_defined_constants (true) ;
		$user_defined_constants = print_r ( $defined_constants['user'], true ) ;

		echo ( '<!-- ' ) ;
		// Note: sprintf() won't print the contents of this variable.
		echo $user_defined_constants  ;
		echo ( ' -->' ) ;
		_bzmndsgn_debug ( $user_defined_constants ) ;
	}

	/**
	 * Include admin interface if we are viewing the backend.
	 */
	if ( is_admin ( ) && file_exists ( BZMNDSGN_LIBRARY_FOLDER_URI . 'admin.php' ) ) {
		require_once ( BZMNDSGN_LIBRARY_FOLDER_URI . 'admin.php' ) ;
	}

}
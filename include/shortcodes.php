<?php
/**
 * @package Baizman Design Standard Library
 * @version 0.1
 */

defined ( 'ABSPATH' ) or die ( 'This file cannot be run outside of WordPress.' ) ;

if ( ! function_exists ( 'bzmndsgn_get_site_database_setting' ) ):
	/**
	 * Return requested site option value from the database.
	 *
	 * @param $attributes
	 * @param null $content
	 *
	 * @return string
	 */
	function bzmndsgn_get_site_database_setting ( $attributes, $content = null ) {
		global $bzmndsgn_config_options ;

		if ( ! isset ( $attributes['key'] ) ) {
			return '<span class="lta_plugin_error_message">Please specify a key via the shortcode attribute "key."</span>' ;
		}

		if ( isset ( $bzmndsgn_config_options[$attributes['key']] ) ) {
			return $bzmndsgn_config_options[$attributes['key']] ;
		}
		else {
			return sprintf ('The key %s does not exist.', $attributes['key'] );
		}
	}
	add_shortcode ( 'bzmndsgn_get_site_database_setting', 'bzmndsgn_get_site_database_setting' ) ;
endif;

if ( ! function_exists ( 'bzmndsgn_get_theme_directory' ) ):
	/**
	 * Helper function that returns theme folder.
	 *
	 * @return string
	 */
	function bzmndsgn_get_theme_directory ( ) {
		return BZMNDSGN_THEME_FOLDER_URI ;
	}
	add_shortcode ( 'bzmndsgn_get_theme_directory', 'bzmndsgn_get_theme_directory' ) ;
endif;

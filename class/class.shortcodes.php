<?php

// TODO MAYBE: make a shortcode class, and put each shortcode in its own file?
namespace baizman_design ;

use baizman_design\bdsl;
use baizman_design\preferences;

class shortcodes {

	public static bdsl $plugin;

	// don't try to create an object.
	private function __construct() { }

	public static function load_shortcodes ( $plugin ) {
		self::$plugin = $plugin ;
		add_shortcode ( preferences::prefix.'_get_site_database_setting', [__CLASS__,'get_site_database_setting'] ) ;

		add_shortcode ( preferences::prefix.'_get_theme_directory', [__CLASS__,'get_theme_directory'] ) ;
	}

	/**
	 * Return requested site option value from the database.
	 *
	 * @param $attributes
	 * @param null $content
	 *
	 * @return string
	 */
	public static function get_site_database_setting ( $attributes, $content = null ): string {

		if ( ! isset ( $attributes['key'] ) ) {
			return '<span class="lta_plugin_error_message">Please specify a key via the shortcode attribute "key."</span>' ;
		}

		// can't use isset() here.
		if ( in_array( $attributes['key'], array_keys ( preferences::get_database_options() ) ) ) {
			return preferences::get_database_option($attributes['key']);
		}
		else {
			return sprintf ('The key %s does not exist.', $attributes['key'] );
		}
	}

	/**
	 * Helper function that returns theme folder.
	 *
	 * @return string
	 */
	public static function get_theme_directory ( ): string {
		return self::$plugin->theme_folder_uri ;
	}
}
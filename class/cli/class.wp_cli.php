<?php
/**
 * WP CLI interface to Baizman Design Standard Library.
 * @link https://make.wordpress.org/cli/handbook/guides/commands-cookbook/
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design\cli ;

use baizman_design;
use baizman_design\bdsl;
use baizman_design\page\advanced_config;
use baizman_design\preferences;
use SebastianBergmann\CodeCoverage\Report\PHP;

class wp_cli {

	private string $transient_option_key_plugin = '_site_transient_update_plugins' ;
	private string $transient_option_key_theme = '_site_transient_update_themes' ;

	private static string $plugin_path = '' ;

	public static function add_command ( $plugin ) {
		\WP_CLI::add_command( preferences::prefix, __NAMESPACE__.'\wp_cli', [
				'shortdesc' => 'WP CLI interface for Baizman Design Standard Library (BDSL).',
			]
		);
		self::$plugin_path = $plugin->plugin_folder_uri ;
	}

	/**
	 * Reserialize the database data, incorporating changes to fields.
	 *
	 * ## EXAMPLES
	 *
	 * wp bdsl refresh
	 * wp bdsl refresh --yes
	 *
	 * @subcommand refresh
	 * @synopsis [--yes]
	 */
	 public function reserialize ( $args, $assoc_args ) {
		 $defaults = [
			 'yes' => false,
		 ];
		 $assoc_args = wp_parse_args ($assoc_args,$defaults) ;
		 $skip_confirmation = $assoc_args['yes'] ;

		 if ( ! $skip_confirmation ) {
			 \WP_CLI::confirm('Are you sure you want to reserialize the database settings?');
		 }

		 advanced_config::__reserialize_data() ;

		 \WP_CLI::success( 'The configuration data has been reserialized.' );
	}

	/**
	 * Reset the plugin data to the defaults.
	 *
	 * ## EXAMPLES
	 *
	 * wp bdsl reset
	 * wp bdsl reset --yes
	 *
	 * @subcommand reset
	 * @synopsis [--yes]
	 */
	public function reinitialize ( $args, $assoc_args ) {
		$defaults = [
			'yes' => false,
		];
		$assoc_args = wp_parse_args ($assoc_args,$defaults) ;
		$skip_confirmation = $assoc_args['yes'] ;

		if ( ! $skip_confirmation ) {
			\WP_CLI::confirm( "Are you sure you want to reset the settings to their defaults?" );
		}

		preferences::set_default_database_options() ;

		\WP_CLI::success( 'The default plugin configuration has been reinitialized.' );
	}

	/**
	 * Delete update plugins transient.
	 *
	 * ## EXAMPLES
	 *
	 * wp bdsl delete-plugins-transient
	 * wp bdsl delete-plugins-transient --yes
	 *
	 * @subcommand delete-plugins-transient
	 * @synopsis [--yes]
	 */
	public function delete_update_plugins_transient ( $args, $assoc_args ) {
		$defaults = [
			'yes' => false,
		];
		$assoc_args = wp_parse_args ($assoc_args,$defaults) ;
		$skip_confirmation = $assoc_args['yes'] ;

		if ( ! $skip_confirmation ) {
			\WP_CLI::confirm( "Are you sure you want to delete the update plugins transient?" );
		}

		delete_option ( $this->transient_option_key_plugin ) ;

		\WP_CLI::success( 'The update plugins transient has been deleted.' );
	}

	/**
	 * Delete update themes transient.
	 *
	 * ## EXAMPLES
	 *
	 * wp bdsl delete-themes-transient
	 * wp bdsl delete-themes-transient --yes
	 *
	 * @subcommand delete-themes-transient
	 * @synopsis [--yes]
	 */
	public function delete_update_themes_transient ( $args, $assoc_args ) {
		$defaults = [
			'yes' => false,
		];
		$assoc_args = wp_parse_args ($assoc_args,$defaults) ;
		$skip_confirmation = $assoc_args['yes'] ;

		if ( ! $skip_confirmation ) {
			\WP_CLI::confirm( "Are you sure you want to delete the update themes transient?" );
		}

		delete_option ( $this->transient_option_key_theme ) ;

		\WP_CLI::success( 'The update themes transient has been deleted.' );
	}

	/**
	 * Print plugin version.
	 *
	 * ## EXAMPLES
	 *
	 * wp bdsl get-plugin-version
	 *
	 * @subcommand get-plugin-version
	 */
	public function get_plugin_version ( $args, $assoc_args ) {
		if( ! function_exists('get_plugin_data') ){
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$plugin_data = get_plugin_data ( self::$plugin_path . DIRECTORY_SEPARATOR . bdsl::plugin_filename) ;
		$plugin_version = $plugin_data['Version'] ;

		\WP_CLI::log($plugin_version);

	}

	/**
	 * Print message via WP CLI static method.
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	private function wpcli_print( string $message = '' ) {
		// \WP_CLI::log( $message );
		\WP_CLI::debug( $message, $this->debug_group );
	}
}


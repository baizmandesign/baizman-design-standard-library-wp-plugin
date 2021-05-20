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

class wp_cli {

	public static function add_command () {
		\WP_CLI::add_command( bdsl::prefix, __NAMESPACE__.'\wp_cli', [
				'shortdesc' => 'WP CLI interface for Baizman Design Standard Library (BDSL).',
			]
		);
	}

	/**
	 * Reserialize the database data, incorporating changes to fields.
	 * @subcommand refresh
	 */
	 public function reserialize ( ) {
		advanced_config::__reserialize_data() ;
		\WP_CLI::success( 'The configuration data has been reserialized.' );
	}

	/**
	 * Reset the plugin data to the defaults.
	 * @subcommand reset
	 */
	public function reinitialize ( ) {
		\WP_CLI::confirm( "Are you sure you want to reset the settings to their defaults?" );

		preferences::set_default_database_options() ;

		\WP_CLI::success( 'The default plugin configuration has been reinitialized.' );
	}

}


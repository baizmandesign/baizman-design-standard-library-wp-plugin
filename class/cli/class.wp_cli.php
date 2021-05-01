<?php
/**
 * WP CLI interface to Baizman Design Standard Library.
 * @link https://make.wordpress.org/cli/handbook/guides/commands-cookbook/
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design\cli ;

use baizman_design;

class wp_cli {

	public function __construct () {
		\WP_CLI::add_command( 'bzmndsgn', $this, array (
				'before_invoke' => function () {
					// admin.php has an important constant (SITE_OPTIONS_DEFAULTS), so we need to load it prior to calling WP CLI commands.
					// https://make.wordpress.org/cli/handbook/references/internal-api/wp-cli-add-hook/
					require_once( BZMNDSGN_PLUGIN_ADMIN_URI );
				},
			)
		);
	}

	/**
	 * Reserialize the database data, incorporating changes to fields.
	 * @subcommand refresh
	 * @param $args
	 */
	public function reserialize ( $args ) {
		baizman_design\__bzmndsgn_reserialize_data( ) ;
		\WP_CLI::success( 'The configuration data has been reserialized.' );
	}

	/**
	 * Reset the plugin data to the defaults.
	 * @subcommand reset
	 * @param $args
	 */
	public function reinitialize ( $args ) {

		baizman_design\__bzmndsgn_reinitialize_default_data () ;

		\WP_CLI::success( 'The default plugin configuration has been reinitialized.' );
	}

}


<?php
/**
 * WP CLI interface to Baizman Design Standard Library.
 * @link https://make.wordpress.org/cli/handbook/guides/commands-cookbook/
 * @package Baizman Design Standard Library
 * @version 0.1
 */

use baizman_design;

class bzmndsgn {

	/**
	 * Reserialize the database data, incorporating changes to fields.
	 * @subcommand refresh
	 * @param $args
	 */
	function reserialize ( $args ) {
		baizman_design\__bzmndsgn_reserialize_data( ) ;
		WP_CLI::success( 'The configuration data has been reserialized.' );
	}

	/**
	 * Reset the plugin data to the defaults.
	 * @subcommand reset
	 * @param $args
	 */
	function reinitialize ( $args ) {

		baizman_design\__bzmndsgn_reinitialize_default_data () ;

		WP_CLI::success( 'The default plugin configuration has been reinitialized.' );
	}

}

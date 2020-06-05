<?php
/**
 * WP CLI interface to Baizman Design Standard Library.
 * @link https://make.wordpress.org/cli/handbook/guides/commands-cookbook/
 * @package Baizman Design Standard Library
 * @version 0.1
 */

class bzmndsgn {

	/**
	 * Reserialize the database data, incorporating changes to fields.
	 * @subcommand refresh
	 * @param $args
	 */
	function reserialize ( $args ) {
		__bzmndsgn_reserialize_data( ) ;
		WP_CLI::success( 'The configuration data has been successfully reserialized.' );
	}

	/**
	 * Reset the plugin data to the defaults.
	 * @subcommand reset
	 * @param $args
	 */
	function reinitialize ( $args ) {

		__bzmndsgn_reinitialize_default_data () ;

		WP_CLI::success( 'The default plugin configuration has been successfully reinitialized.' );
	}

}

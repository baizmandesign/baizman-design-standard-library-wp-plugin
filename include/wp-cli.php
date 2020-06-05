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

if ( defined ( 'WP_CLI' ) && WP_CLI ) :
	WP_CLI::add_command( 'bzmndsgn', 'bzmndsgn', array (
			'before_invoke' => function () {
				// admin.php has an important constant, so we need to load it prior to calling WP CLI commands.
				// https://make.wordpress.org/cli/handbook/references/internal-api/wp-cli-add-hook/
				require_once ( BZMNDSGN_PLUGIN_ADMIN_URI ) ;
			}
		)
	);
endif;
<?php
/**
 * Page class for dashboard config page.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design\page ;

use baizman_design\bdsl;
use baizman_design\button;
use baizman_design\checkbox;
use baizman_design\checkbox_group;
use baizman_design\color;
use baizman_design\email_input;
use baizman_design\form;
use baizman_design\preferences;
use baizman_design\text_area;
use baizman_design\text_input;
use baizman_design\utility;

class advanced_config extends page {

	public function __construct( $plugin, $page_title, $page_slug ) {
		parent::__construct( $plugin, $page_title, $page_slug );
		add_action ( 'admin_post_reserialize_data', [$this,'reserialize_data'] ) ;
		add_action ( 'admin_post_reinitialize_default_data', [$this,'reinitialize_default_data'] ) ;
	}

	/**
	 * @return mixed
	 */
	public function render_page() {
		utility::print_admin_settings_heading ('Advanced Settings', 'Baizman Design Standard Library' ) ;
		?>
		<h4>Reserialize data</h4>
		<form method="post" action="<?php echo admin_url('admin-post.php') ; ?>">
			<p>If a new plugin setting has been added or removed, click the button below to synchronize the database.</p>
			<?php
			$reserialize_button = new button ( 'Reserialize configuration data', 'reserialize_data', 'small') ;
			wp_nonce_field ( $reserialize_button->get_field_input_name() );
			$reserialize_button->print_form_field() ;
			?>
		</form>
		<h4>Reinitialize default data</h4>
		<form method="post" action="<?php echo admin_url('admin-post.php') ; ?>">
			<p>Reset the saved plugin data to the default values.</p>
			<p><strong>Note: this will erase any local customizations! Use with caution.</strong></p>
			<?php
			$reinitialize_button = new button( 'Reinitialize default configuration', 'reinitialize_default_data', 'small' ) ;
			wp_nonce_field( $reinitialize_button->get_field_input_name() );
			$reinitialize_button->print_form_field();
			?>
		</form>

		<?php

		utility::print_admin_settings_footer() ;
	}

	/**
	 * @return void
	 */
	public function reserialize_data ( ) {

		if ( ! current_user_can ( 'manage_options' ) ) {
			wp_die ( 'You do not have permission to update these settings.' ) ;
		}

		check_admin_referer ( 'reserialize_data' ) ;

		$this->__reserialize_data () ;

		utility::form_redirect ('The configuration data has been successfully reserialized.' );

		exit ;

	}

	/**
	 * Reset the plugin data to the defaults.
	 */
	public function reinitialize_default_data () {

		if ( ! current_user_can ( 'manage_options' ) ) {
			wp_die ( 'You do not have permission to update these settings.' ) ;
		}

		check_admin_referer ( 'reinitialize_default_data' ) ;

		// Populate default options.
		preferences::set_default_database_options() ;

		utility::form_redirect('The default plugin configuration has been successfully reinitialized.');

		exit ;

	}

	/**
	 * Helper function to reserialize database data. Used by wp-cli.
	 */
	static function __reserialize_data ( ){

		/*
		possible cases:
		1) a setting has been added
		2) a setting has been deleted (not too likely)
		3) a value has been modified (we don't care if the old value is different from the new default value, keep the old value)
		*/

		// Merge the database option values with the defaults, over-writing them.
		$merged_options = wp_parse_args ( preferences::get_database_options(), preferences::get_default_database_options ( ) ) ;
		$deleted_options_keys = array_diff_key ( preferences::get_database_options(), preferences::get_default_database_options ( ) ) ;

		// Have any option keys been deleted?
		if ( ! empty ( $deleted_options_keys ) ) {
			foreach ( $deleted_options_keys as $deleted_option_key => $deleted_option_value ) {
				unset ( $merged_options[$deleted_option_key] ) ;
			}
		}

		preferences::set_database_options ( $merged_options ) ;

	}

}
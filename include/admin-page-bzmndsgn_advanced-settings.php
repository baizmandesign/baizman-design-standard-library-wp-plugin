<?php
/**
 * Advanced settings.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design ;

function bzmndsgn_advanced_settings () {
	_print_admin_settings_heading ('Advanced Settings', 'Baizman Design Standard Library' ) ;
	?>
	<h4>Reserialize data</h4>
	<form method="post" action="<?php echo admin_url('admin-post.php') ; ?>">
        <p>If a new plugin setting has been added or removed, click the button below to synchronize the database.</p>
        <?php
        $reserialize_button = new button ( 'Reserialize configuration data', 'bzmndsgn_reserialize_data', 'small') ;
        wp_nonce_field ( $reserialize_button->get_field_input_name() );
        $reserialize_button->print_form_field() ;
        ?>
	</form>
	<h4>Reinitialize default data</h4>
	<form method="post" action="<?php echo admin_url('admin-post.php') ; ?>">
		<p>Reset the saved plugin data to the default values.</p>
		<p><strong>Note: this will erase any local customizations! Use with caution.</strong></p>
        <?php
        $reinitialize_button = new button( 'Reinitialize default configuration', 'bzmndsgn_reinitialize_default_data', 'small' ) ;
        wp_nonce_field( $reinitialize_button->get_field_input_name() );
        $reinitialize_button->print_form_field();
        ?>
	</form>

	<?php

}

/**
 * Reserialize the database data, incorporating changes to fields.
 *
 * https://wordpress.stackexchange.com/questions/309440/wordpress-plugin-how-to-run-function-when-button-is-clicked
 */
function bzmndsgn_reserialize_data ( ) {

	if ( ! current_user_can ( 'manage_options' ) ) {
		wp_die ( 'You do not have permission to update these settings.' ) ;
	}

	check_admin_referer ( 'bzmndsgn_reserialize_data' ) ;

	__bzmndsgn_reserialize_data () ;

	_bzmndsgn_form_redirect ('The configuration data has been successfully reserialized.' );

	exit ;

}
add_action ( 'admin_post_bzmndsgn_reserialize_data', __NAMESPACE__.'\bzmndsgn_reserialize_data' ) ;

/**
 * Reset the plugin data to the defaults.
 */
function bzmndsgn_reinitialize_default_data () {

	if ( ! current_user_can ( 'manage_options' ) ) {
		wp_die ( 'You do not have permission to update these settings.' ) ;
	}

	check_admin_referer ( 'bzmndsgn_reinitialize_default_data' ) ;

	__bzmndsgn_reinitialize_default_data () ;

	_bzmndsgn_form_redirect('The default plugin configuration has been successfully reinitialized.');

	exit ;

}
add_action ( 'admin_post_bzmndsgn_reinitialize_default_data', __NAMESPACE__.'\bzmndsgn_reinitialize_default_data' ) ;

/**
 * Helper function to reserialize database data. Used by wp-cli.
 */
function __bzmndsgn_reserialize_data ( ){
	// Get current settings.
	$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS ) ;

	/*
	possible cases:
	1) a setting has been added
	2) a setting has been deleted (not too likely)
	3) a value has been modified (we don't care if the old value is different from the new default value, keep the old value)
	*/

	// Merge the database option values with the defaults, over-writing them.
	$merged_options = wp_parse_args ( $bzmndsgn_config_options_database, SITE_OPTIONS_DEFAULTS ) ;
	$deleted_options_keys = array_diff_key ( $bzmndsgn_config_options_database, SITE_OPTIONS_DEFAULTS ) ;

	// Have any option keys been deleted?
	if ( ! empty ( $deleted_options_keys ) ) {
		foreach ( $deleted_options_keys as $deleted_option_key => $deleted_option_value ) {
			unset ( $merged_options[$deleted_option_key] ) ;
		}
	}

	update_option ( BZMNDSGN_CONFIG_OPTIONS, $merged_options ) ;

}

/**
 * Helper function to reinitialize database data. Used by wp-cli.
 */
function __bzmndsgn_reinitialize_default_data () {

	// Delete existing options.
	delete_option ( BZMNDSGN_CONFIG_OPTIONS ) ;

	// Populate default options.
	bzmndsgn_config_set_default_options_array ( ) ;

}
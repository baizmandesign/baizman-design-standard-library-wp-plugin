<?php
/**
 * Advanced settings.
 */

function bzmndsgn_advanced_settings () {
	_print_admin_settings_heading ('Advanced Settings') ;
	?>
	<h4>Reserialize data</h4>
	<form method="post" action="<?php echo admin_url('admin-post.php') ; ?>">
		<p>If a new plugin setting has been added or removed, click the button below to synchronize the database.</p>
		<input type="hidden" name="action" value="bzmndsgn_reserialize_data">
		<?php wp_nonce_field ( 'bzmndsgn_reserialize_data' ); ?>
		<?php submit_button( 'Reserialize configuration data', 'small', 'reserialize', $wrap = false ) ;?>
	</form>
	<h4>Reinitialize default data</h4>
	<form method="post" action="<?php echo admin_url('admin-post.php') ; ?>">
		<p>Reset the saved plugin data to the default values.</p>
		<p><strong>Note: this will erase any local customizations! Use with caution.</strong></p>
		<input type="hidden" name="action" value="bzmndsgn_reinitialize_default_data">
		<?php wp_nonce_field ( 'bzmndsgn_reinitialize_default_data' ); ?>
		<?php submit_button( 'Reinitialize default configuration', 'small', 'reserialize', $wrap = false ) ;?>
	</form>

	<?php

}
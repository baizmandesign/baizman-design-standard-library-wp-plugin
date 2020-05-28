<?php
/**
 * WP dashboard customization.
 */

function bzmndsgn_dashboard ( ) {
	_print_admin_settings_heading ('WP Dashboard Customization') ;
	/* move elsewhere? */
	if ( isset( $_GET['message'] ) && $_GET['message'] == '1' ) :
	?>
        <div id="message" class="notice notice-success is-dismissible">
            <p><strong><?php echo $_GET['details'] ; ?></strong></p>
        </div>
    <?php
    endif ;


}
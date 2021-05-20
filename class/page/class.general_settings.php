<?php
/**
 * Page class for a general settings page.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

// FIXME: needs to be able to run on the front-end.

namespace baizman_design\page ;

use baizman_design\bdsl;
use baizman_design\checkbox;
use baizman_design\form;
use baizman_design\preferences;
use baizman_design\text_input;
use baizman_design\utility;

class general_settings extends page {

	public function __construct( $plugin, $page_title, $page_slug, $is_parent_menu ) {
		parent::__construct( $plugin, $page_title, $page_slug, $is_parent_menu );
		if ( preferences::get_database_option('google_analytics_id') ) {
			add_action ( 'wp_head', [__CLASS__,'enqueue_google_analytics'], 1 ) ;
		}

        // Disable the fatal error handler email notifications.
        // @link https://wordpress.org/support/topic/disable-the-fatal-error-handler/
		if ( utility::is_enabled ( 'checkbox-disable_fatal_error_handler_email_notifications' ) ) {
			add_filter ( 'wp_fatal_error_handler_enabled', '__return_false', PHP_INT_MAX ) ;
		}
    }

	/**
	 * @return mixed
	 */
	public function render_page() {
		utility::print_admin_settings_heading ('General Settings', 'Baizman Design Standard Library' ) ;

		$general_settings_form = new form ( 'general_settings' ) ;
		$form_database_settings = $general_settings_form->get_form_database_settings() ;
		$general_settings_form->set_settings_fields_option_group(bdsl::settings_group);
		$general_settings_form->set_settings_fields_page(bdsl::settings_group );

		// Google Analytics ID
		$google_analytics_id = new text_input( 'Google Analytics 4 ID:', 'google_analytics_id','G-1234567890', preferences::get_database_option('google_analytics_id') );
		$google_analytics_id->set_field_help_text('<a href="https://support.google.com/analytics/answer/1008080?hl=en" target="_blank" rel="noopener">Learn where to obtain your Google Analytics ID.</a>');
		$general_settings_form->add_form_field( $google_analytics_id );

		// 404 log file prefix
		$four_zero_four_log_file_prefix = new text_input( '404 Log File Prefix:', 'log_file_prefix','UA-NNNNNNNNN-N', preferences::get_database_option('log_file_prefix') );
		$four_zero_four_log_file_prefix->set_field_help_text('To log 404 errors, add <code><small>baizman_design\utility::log_404_error()</small></code> to the theme\'s 404.php.<br><a href="/wp-admin/admin.php?page=bzmndsgn_404_error_log">Visit the 404 Error Log.</a>');
		$general_settings_form->add_form_field( $four_zero_four_log_file_prefix );

		// Local plugin option name
		$local_plugin_option_name_label = 'Local plugin option name' ;
		$local_plugin_option_name_input_name = 'local_plugin_option_name' ;
		$local_plugin_option_name = new text_input( $local_plugin_option_name_label, $local_plugin_option_name_input_name, 'option_name', preferences::get_database_option($local_plugin_option_name_input_name) );
		$local_plugin_option_name->set_field_help_text( 'This is the <code><small>option_name</small></code> in the MySQL database.' );

		$general_settings_form->add_form_field( $local_plugin_option_name );

        // Disable the fatal error handler email notifications.
		$disable_fatal_error_handler = new checkbox ('Disable the fatal error handler email notifications?',
			'checkbox-disable_fatal_error_handler_email_notifications',
			preferences::get_database_option('checkbox-disable_fatal_error_handler_email_notifications')
		) ;

		$general_settings_form->add_form_field ( $disable_fatal_error_handler ) ;

		$general_settings_form->render_form();

		utility::print_admin_settings_footer() ;

	}

	/**
	 * Add Google Analytics tracking code to global header.
	 */
	public static function enqueue_google_analytics ( ) {

		// Google Tracking ID.
		?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo preferences::get_database_option('google_analytics_id') ; ?>"></script>
		<script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo preferences::get_database_option('google_analytics_id') ; ?>');
		</script>
		<?php

	}

}
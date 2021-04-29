<?php
/**
 * @package Baizman Design Standard Library
 * @version 0.1
 */

/******************************************************************************/

namespace {
	defined ( 'ABSPATH' ) or die ( 'This file cannot be run outside of WordPress.' ) ;
}

namespace baizman_design {

	/*
	 * User-configurable settings and admin page functions.
	 */

// Legal HTML tag defaults.

	$legal_tags   = [];
	$legal_tags[] = 'a';
	$legal_tags[] = 'b';
	$legal_tags[] = 'strong';
	$legal_tags[] = 'i';
	$legal_tags[] = 'em';
	$legal_tags[] = 'h1';
	$legal_tags[] = 'h2';
	$legal_tags[] = 'h3';
	$legal_tags[] = 'h4';
	$legal_tags[] = 'h5';
	$legal_tags[] = 'h6';
	$legal_tags[] = 'ul';
	$legal_tags[] = 'li';
	$legal_tags[] = 'blockquote';
	$legal_tags[] = 'p'; // necessary?

// Per-site database fields and default values.

	$site_defaults = array (
		'google_analytics_id'                                     => '',
		'google_measurement_id'                                   => '',
		'log_file_prefix'                                         => str_replace( ' ', '-', strtolower( get_bloginfo( 'name' ) ) ),
		// dashboard
		'local_dashboard_background_color'                        => BZMNDSGN_LOCAL_BACKGROUND_COLOR,
		'dev_dashboard_background_color'                          => BZMNDSGN_DEV_BACKGROUND_COLOR,
		'staging_dashboard_background_color'                      => BZMNDSGN_STAGING_BACKGROUND_COLOR,
		'production_dashboard_background_color'                   => '',
		'local_plugin_option_name'                                => '',
		'checkbox-show_site_name'                                 => '1',
		'checkbox-show_marketing'                                 => '1',
		// content sanitizers
		'checkbox-strip_double_spaces_on_save'                    => '0',
		'checkbox-strip_double_spaces_on_display'                 => '0',
		'checkbox-strip_illegal_tags_on_save'                     => '0',
		'checkbox-strip_content_blank_lines_on_display'           => '0',
		'checkbox-strip_content_blank_lines_on_save'              => '0',
		'textarea-legal_tags'                                     => implode( "\n", $legal_tags ),
		'textarea-branding_info'                                  => sprintf( 'Website design and development by <a target="_blank" rel="noopener" href="%2$s">%1$s</a>', BZMNDSGN_AUTHOR_COMPANY, BZMNDSGN_AUTHOR_COMPANY_URL ),
		'checkbox-show_global_site_warning'                       => '0',
		'textarea-global_site_warning'                            => '<strong>WARNING: this is a development server meant for experimental purposes only. Content saved on this site may be removed at any time without notice, and certain functions may not be fully configured or operational.</strong>',
		'checkbox-show_dashboard_widget'                          => '1',
		'text-dashboard_widget_title'                             => 'WordPress Website Care Package',
		'textarea-dashboard_widget_body'                          => '<p>This website (<a href="{home_url}">{hostname}</a>) has a contract with <a href="{author_company_url}" target="_blank" rel="noopener">{author_company}</a> for a WordPress Website Care Package.</p>
<p>In addition to monthly monitoring and maintenance of the website, the package includes up to two hours per month for the following:</p>
<ul>
<li>+ technical support via <a href="mailto:{support_email}">email</a>, <a href="tel:{support_phone}">phone</a>, <a href="{videoconference_url}">videoconference</a>, or in-person consultation.</li>
<li>+ custom website development (e.g., a new feature).</li>
<li>+ WordPress training for new and existing users.</li>
</ul>',
		'checkboxes-dashboard_links_to_hide'                      => [],
		// empty array
		'checkbox-hide_toolset_expiration_notice'                 => '0',
		// don't hide it
		'checkbox-enable_fixed_admin_table_headers'               => '1',
		// enable fixed headers
		'checkbox-disable_file_editor'                            => '1',
		// disable theme and plugin editor
		'checkbox-enable_toolset_taxonomy_sort'                   => '1',
		// sort toolset custom taxonomies alphabetically in the dashboard
		'checkbox-disable_plugin_auto_update_email_notifications' => '1',
		// disable plugins auto-update email notifications
		'checkbox-disable_theme_auto_update_email_notifications'  => '1',
		// disable themes auto-update email notifications
		'email_sender_name'                                       => '',
		// email sender name (WP default is "WordPress")
		'email_sender_address'                                    => '',
		// email sender address (WP default is "wordpress@domain")
		'email_reply_to_address'                                  => '',
		// email reply-to address (WP default is absent)
	);

	define( 'SITE_OPTIONS_DEFAULTS', $site_defaults );

	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
	define( 'BZMNDSGN_HAS_TOOLSET', is_plugin_active( 'types/wpcf.php' ) );

	/* "The thing to know is that the first argument is the name required to get your code to fire, not what file the code is in."
	- https://developer.wordpress.org/reference/functions/register_activation_hook/
	*/
	$plugin_file_path = trailingslashit( dirname( __FILE__ ) ) . 'baizman-design-standard-library.php';

//echo '$plugin_file_path: ' . $plugin_file_path ;

	register_activation_hook( $plugin_file_path, 'bzmndsgn_config_set_default_options_array' );

	/**
	 * Set the per-site default options.
	 */
	function bzmndsgn_config_set_default_options_array() {

		update_option( BZMNDSGN_CONFIG_OPTIONS, SITE_OPTIONS_DEFAULTS );

	}

	/**
	 * Register per-site settings.
	 */
	function bzmndsgn_config_settings() {
		// General Settings.

		foreach ( SITE_OPTIONS_DEFAULTS as $key => $value ) {
			register_setting( BZMNDSGN_SETTINGS_GROUP, $key );
		}
	}

	add_action( 'admin_init', __NAMESPACE__.'\bzmndsgn_config_settings' );

	/**
	 * Define the per-site admin menu and submenus.
	 */
	function bzmndsgn_config_admin_menu() {

		global $submenu;

		/* Top-level menu. */
		$parent_menu = new menu ( BZMNDSGN_AUTHOR_COMPANY . ' Standard Library', 'bzmndsgn_general_settings', 'dashicons-admin-generic' );

		/* General configuration page. */
		$general_settings_submenu = new submenu ( 'General Settings', 'bzmndsgn_general_settings' );
		$parent_menu->add_submenu_item( $general_settings_submenu );

		/* Content Sanitizers configuration page. */
		$content_sanitizers_submenu = new submenu ( 'Content Sanitizers', 'bzmndsgn_content_sanitizers' );
		$parent_menu->add_submenu_item( $content_sanitizers_submenu );

		/* Dashboard configuration page. */
		$dashboard_submenu = new submenu ( 'Dashboard', 'bzmndsgn_dashboard' );
		$parent_menu->add_submenu_item( $dashboard_submenu );

		/* Email configuration page. */
		/* Hide menu / page if Easy WP SMTP is active. */
		if ( ! is_plugin_active( 'easy-wp-smtp/easy-wp-smtp.php' ) ) {
			$email_submenu = new submenu ( 'Email', 'bzmndsgn_email' );
			$parent_menu->add_submenu_item( $email_submenu );
		}

		/* 404 error log. */
		$error_404_log_submenu = new submenu ( '404 Error Log', 'bzmndsgn_404_error_log' );
		$parent_menu->add_submenu_item( $error_404_log_submenu );

		/* Advanced submenu. */
		if ( WP_DEBUG ) {
			$advanced_submenu = new submenu( 'Advanced', 'bzmndsgn_advanced_settings' );
			$parent_menu->add_submenu_item( $advanced_submenu );
		}
		$parent_menu->render_menu();

	}

	if ( BZMNDSGN_SHOW_DASHBOARD_INTERFACE ):
		add_action( 'admin_menu', __NAMESPACE__.'\bzmndsgn_config_admin_menu', 1 );
	endif;

	function bzmndsgn_filter_plugin_links( $links, $file ) {
		$plugin_info          = get_plugins( '/' . explode( '/', plugin_basename( __FILE__ ) )[0] );
		$plugin_parent_folder = trailingslashit( basename( dirname( __FILE__ ) ) );

		$plugin_name = _array_key_first( $plugin_info );

		if ( $file == $plugin_parent_folder . $plugin_name ) {
			$links[] = sprintf( '<a href="%s" rel="noopener"><span class="dashicons dashicons-admin-generic"></span> Settings</a>', home_url( '/wp-admin/admin.php?page=bzmndsgn_general_settings' ) );
			$links[] = sprintf( '<a href="%s"  rel="noopener" target="%s"><span class="dashicons dashicons-welcome-write-blog"></span> Report an Issue</a>', 'https://bitbucket.org/baizmandesign/baizman-design-wp-plugin-standard-library/issues/new', '_blank' );
		}

		return $links;
	}

	add_filter( 'plugin_row_meta', 'bzmndsgn_filter_plugin_links', 10, 2 );

	/**
	 * Save per-site settings to database.
	 */
	function bzmndsgn_save_config_settings() {
		// Check that user has proper security level.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have permission to update these settings.' );
		}

		// Check referrer.
		check_admin_referer( 'bzmndsgn_save_config' );

		// Get current settings.
		$bzmndsgn_config_options_database = get_option( BZMNDSGN_CONFIG_OPTIONS );

		// Store updated settings.
		$updated_options = array ();

		foreach ( $bzmndsgn_config_options_database as $option => $value ) {
			if ( isset ( $_POST[ $option ] ) ) {
				$updated_options[ $option ] = stripslashes_deep( $_POST[ $option ] ); // sanitize_option, sanitize_textarea_field
			}
		}

		$checkboxes = 0;
		// Identify the checkboxes. Get them from the hidden field, "single_checkboxes".
		if ( isset ( $_POST['single_checkboxes'] ) ) {
			$checkboxes = explode( ',', $_POST['single_checkboxes'] );
		}

		// Force the values for checkboxes.
		if ( count( $checkboxes ) > 0 ) {
			foreach ( $checkboxes as $checkbox ) {
				if ( isset ( $_POST[ $checkbox ] ) ) {
					// The field is checked. Set field value to '1'.
					$updated_options[ $checkbox ] = '1';
				} else {
					// The field was unchecked. Set field value to '0'.
					$updated_options[ $checkbox ] = '0';
				}
			}
		}

		$checkbox_groups = 0;
		// Identify checkbox groups. Get them from the hidden field, "checkbox_groups".
		if ( isset ( $_POST['checkbox_groups'] ) ) {
			$checkbox_groups = explode( ',', $_POST['checkbox_groups'] );
		}

		// Force the values for checkbox groups.
		if ( count( $checkbox_groups ) > 0 ) {
			foreach ( $checkbox_groups as $checkbox_group ) {
				if ( isset ( $_POST[ $checkbox_group ] ) ) {
					// A field in the group is checked. Set the field value to its array in the $_POST variable.
					$updated_options[ $checkbox_group ] = $_POST[ $checkbox_group ];
				} else {
					// No fields in the group are checked. Set the field value to an empty array.
					$updated_options[ $checkbox_group ] = [];
				}
			}
		}

		// Update the options.
		$merged_options = wp_parse_args( $updated_options, $bzmndsgn_config_options_database );
		update_option( BZMNDSGN_CONFIG_OPTIONS, $merged_options );

		_bzmndsgn_form_redirect( 'The settings have been saved.' );

		exit;

	}

	add_action( 'admin_post_update_bdsl', __NAMESPACE__.'\bzmndsgn_save_config_settings' );

	/* Multi-site stuff. */

	if ( BZMNDSGN_IS_MULTISITE ) {

		// Global database fields and default values.
		$network_defaults = array ();
		define( 'NETWORK_OPTIONS_DEFAULTS', $network_defaults );

		register_activation_hook( $plugin_file_path, 'bzmndsgn_network_config_set_default_options_array' );

		/**
		 * Set the network default options.
		 */
		function bzmndsgn_network_config_set_default_options_array() {

			add_site_option( BZMNDSGN_MULTISITE_CONFIG_OPTIONS, NETWORK_OPTIONS_DEFAULTS );

		}

		/**
		 * Register global settings.
		 */
		function bzmndsgn_network_config_settings() {
			// Network Settings.

			foreach ( NETWORK_OPTIONS_DEFAULTS as $key => $value ) {
				register_setting( BZMNDSGN_NETWORK_SETTINGS_GROUP, $key );

			}
		}

		add_action( 'admin_init', __NAMESPACE__.'\bzmndsgn_network_config_settings' );

		/**
		 * Save global settings to database.
		 */
		function bzmndsgn_save_network_config_settings() {

			// Check that user has proper security level.
			if ( ! current_user_can( 'manage_network_options' ) ) {
				wp_die( 'You do not have permission to update the network-level settings.' );
			}

			// Check referrer.
			check_admin_referer( 'bzmndsgn_save_network_config' );

			// Get current settings.
			$bzmndsgn_network_config_options_database = get_site_option( BZMNDSGN_MULTISITE_CONFIG_OPTIONS );

			// Store updated settings.
			$updated_options = array ();

			foreach ( $bzmndsgn_network_config_options_database as $option => $value ) {
				if ( isset ( $_POST[ $option ] ) ) {
					$updated_options[ $option ] = stripslashes_deep( $_POST[ $option ] ); // sanitize_option, sanitize_textarea_field
				}
			}

			// Update the options.
			$merged_options = wp_parse_args( $updated_options, $bzmndsgn_network_config_options_database );
			update_site_option( BZMNDSGN_MULTISITE_CONFIG_OPTIONS, $merged_options );

			// Get the referring page from the query string ($_GET['page']).
			$referrer   = $_POST['_wp_http_referer'];
			$link_parts = parse_url( $referrer );
			$query      = $link_parts['query'];
			parse_str( $query, $query_array );

			// Redirect with success=1 query string.
			wp_redirect(
				add_query_arg(
					array (
						'page'    => $query_array['page'],
						'message' => '1',
					),
					network_admin_url( 'admin.php' )
				)
			);

			exit;

		}

		add_action( 'network_admin_edit_bzmndsgn_save_network_config_settings', __NAMESPACE__.'\bzmndsgn_save_network_config_settings' );

	}
}
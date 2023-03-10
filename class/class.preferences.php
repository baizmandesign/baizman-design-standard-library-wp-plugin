<?php
/**
 * Prefences class to set and store database settings.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design ;

use baizman_design\bdsl;
use baizman_design\utility;

class preferences {

	public static array $default_database_options = [];

	public static array $database_options = [];

	// do not instantiate outside class.
	private function __construct () {
		if ( empty ( self::$database_options ) ) {
			self::$database_options = get_option( bdsl::config_options_key );
		}

		if ( empty ( self::$default_database_options ) ) {
			// Per-site database fields and default values.
			self::$default_database_options = [
				'google_analytics_id'                                     => '',
				'log_file_prefix'                                         => str_replace( ' ', '-', strtolower( get_bloginfo( 'name' ) ) ),
				// dashboard
				'local_dashboard_background_color'                        => '#e8f5f8',
				'dev_dashboard_background_color'                          => '#f1ece7',
				'staging_dashboard_background_color'                      => '#edeae6',
				'production_dashboard_background_color'                   => '',
				'local_plugin_option_name'                                => '',
				'checkbox-show_site_name'                                 => '1',
				'checkbox-show_marketing'                                 => '1',
				'checkbox-hide_comments'                                   => '0',
				// content sanitizers
				'checkbox-strip_double_spaces_on_save'                    => '0',
				'checkbox-strip_double_spaces_on_display'                 => '0',
				'checkbox-strip_illegal_tags_on_save'                     => '0',
				'checkbox-strip_content_blank_lines_on_display'           => '0',
				'checkbox-strip_content_blank_lines_on_save'              => '0',
				'textarea-legal_tags'                                     => implode( "\n", self::get_legal_tags() ),
				'textarea-branding_info'                                  => sprintf( 'Website design and development by <a target="_blank" rel="noopener" href="%2$s">%1$s</a>', bdsl::author_company, bdsl::author_company_url ),
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
				'checkbox-disable_all_updates' => '0',
				// do not disable all wordpress updates
				'checkbox-enable_core_updates_only' => '0',
				// do not enable core wordpress updates only
				'checkbox-enable_updates_if_vcs_present' => '0',
				// do not enable wordpress updates if a VCS folder is present
				'checkbox-enable_automatic_plugin_updates' => '0',
				// do not enable automatic updates for all plugins
				'checkbox-enable_automatic_theme_updates' => '0',
				// do not enable automatic updates for all themes
				'checkbox-disable_translation_updates' => '0',
				// do not disable translation updates
				'checkbox-disable_core_auto_update_email_notifications' => '1',
				// disable core auto-update email notifications
				'checkbox-disable_fatal_error_handler_email_notifications' => '1',
				// disable fatal error handler emails
			];

		}
	}

	/**
	 * @return array
	 */
	static function get_database_options (): array {
		( new preferences )->__construct ( ) ;
		return self::$database_options;
	}

	/**
	 * @return void
	 */
	static function set_database_options ( $new_options ) {
		update_option ( bdsl::config_options_key, $new_options ) ;
	}

	/**
	 * @return array
	 */
	static function get_default_database_options (): array {
		( new preferences )->__construct ( ) ;
		return self::$default_database_options ;
	}

	/**
	 * @return void
	 */
	static function set_default_database_options () {
		// Delete existing options.
		delete_option ( bdsl::config_options_key ) ;

		self::set_database_options ( self::get_default_database_options ( ) );
	}

	/**
	 * Global database fields and default values.
	 * @return array
	 */
	static function get_default_network_database_options (): array {
		( new preferences )->__construct ( ) ;
		return [] ;
	}

	/**
	 * Set the network default options.
	 */
	static function set_default_network_database_options() {
		add_site_option( bdsl::multisite_config_options_key, self::get_default_network_database_options() );
	}

	/**
	 * @param $key
	 *
	 * @return string
	 */
	static function get_database_option ( $key ): string {
		( new preferences )->__construct ( ) ;
		if ( isset ( self::$database_options[$key] ) ) {
			return self::$database_options[$key] ;
		}
		return false ;

	}

	/**
	 * @return string[]
	 */
	static public function get_legal_tags ():array {

		// Legal HTML tags.
		// These *won't* get stripped.

		return [
			'a',
			'b',
			'strong',
			'i',
			'em',
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'ul',
			'li',
			'blockquote',
			'p', // necessary?
		] ;

	}
}
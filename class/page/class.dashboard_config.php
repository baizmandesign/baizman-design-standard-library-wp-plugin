<?php
/**
 * Page class for dashboard config page.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design\page ;

use baizman_design\bdsl;
use baizman_design\checkbox;
use baizman_design\checkbox_group;
use baizman_design\color;
use baizman_design\form;
use baizman_design\preferences;
use baizman_design\text_area;
use baizman_design\text_input;
use baizman_design\utility;

class dashboard_config extends page {

	/**
	 * @param $plugin
	 * @param $page_title
	 * @param $page_slug
	 */
	public function __construct( $plugin, $page_title, $page_slug ) {
		parent::__construct( $plugin, $page_title, $page_slug );

		if ( utility::is_enabled ( 'checkbox-show_marketing' ) ) {
			add_filter( 'update_footer', [$this,'footer_credit'], 11 );
		}
		add_action( 'admin_head', [$this,'set_dashboard_background_color'] );

		if ( utility::is_enabled ('checkbox-show_global_site_warning' ) ) {
			add_action ( 'admin_notices', [$this,'site_warning'] ) ;
		}
		add_action ( 'admin_menu' , [$this, 'add_issue_link'] ) ;

		if ( utility::is_enabled ('checkbox-hide_toolset_expiration_notice' ) ) {
			add_action( 'admin_head', [$this,'quell_toolset_expiration_notice'] );
		}

		if ( utility::is_enabled ('checkbox-enable_fixed_admin_table_headers' ) ) {
			add_action( 'admin_head', [$this,'add_fixed_header_to_admin_tables'] );
		}

		if ( utility::is_enabled ('checkbox-enable_toolset_taxonomy_sort' ) ) {
			add_filter( 'option_wpcf-custom-taxonomies', [$this,'sort_custom_toolset_taxonomies'] );
		}

		if ( utility::is_enabled ('checkbox-disable_file_editor' ) ) {
			// no hook needed.
			$this->disable_file_editor ( ) ;
		}

		if ( utility::is_enabled ( 'checkbox-show_site_name' ) ) {
			add_filter( 'admin_footer_text',[$this,'footer_site_name'] );
		}

		if ( utility::is_enabled ( 'checkbox-show_dashboard_widget' ) && bdsl::show_dashboard_widget ) {
			// Add to network dashboard.
			if ( $this->plugin['is_multisite'] ) {
				add_action( 'wp_network_dashboard_setup', [$this,'add_admin_dashboard_widget'] );
			}
			// Add to non-network dashboard.
			add_action( 'wp_dashboard_setup', [$this,'add_admin_dashboard_widget'] );
		}
        
		add_action('admin_menu', [$this,'disable_dashboard_widgets']);

		add_action('admin_bar_menu', [$this,'add_toolbar_items'], 100);

		// frontend
		add_action('wp_head', [$this,'qm_link_style']);
		// backend
		add_action('admin_head', [$this,'qm_link_style']);

		// backend
		add_action( 'admin_enqueue_scripts', [$this,'enqueue_qm_ajax'] );
		// frontend
		add_action( 'wp_enqueue_scripts', [$this,'enqueue_qm_ajax'] );

        add_action( 'wp_ajax_toggle_query_monitor', [$this,'ajax_toggle_query_monitor'] );

		add_action ( 'init', [$this, 'custom_editor_styles'] ) ;

	}

	/**
	 * @return mixed
	 */
	public function render_page() {
		utility::print_admin_settings_heading ('WP Dashboard Customization', 'Baizman Design Standard Library' ) ;

		$dashboard_settings_form = new form ( 'dashboard_settings' ) ;
		$dashboard_settings_form->set_settings_fields_option_group(bdsl::settings_group);
		$dashboard_settings_form->set_settings_fields_page(bdsl::settings_group );

		// Show correct background color prompt depending on the environment.

		$environment = utility::get_environment_type ( );

		$dashboard_background_color_field_label = '' ;
		$dashboard_background_color_field_input_name = '' ;

		switch ( $environment ) {
			case 'Local Development':
				$dashboard_background_color_field_label = 'Local development';
				$dashboard_background_color_field_input_name = 'local_dashboard_background_color' ;
				break;

			case 'Development':
				$dashboard_background_color_field_label = 'Dev';
				$dashboard_background_color_field_input_name = 'dev_dashboard_background_color' ;
				break;

			case 'Staging':
				$dashboard_background_color_field_label = 'Staging';
				$dashboard_background_color_field_input_name = 'staging_dashboard_background_color' ;
				break;

			default:
				break;
		}

		$dashboard_background_color_field_label .= ' dashboard background color:' ;

		if ( $dashboard_background_color_field_label && $dashboard_background_color_field_input_name ) {
			$dashboard_background_color = new color ( $dashboard_background_color_field_label, $dashboard_background_color_field_input_name,  preferences::get_database_option($dashboard_background_color_field_input_name) );
			$dashboard_background_color->set_field_help_text('Or try <a href="javascript:set_wp_bg_color(\'aliceblue\')">aliceblue</a>, <a href="javascript:set_wp_bg_color(\'ivory\')">ivory</a>, <a href="javascript:set_wp_bg_color(\'seashell\')">seashell</a>, or <a href="javascript:set_wp_bg_color(\'ghostwhite\')">ghostwhite</a>.');
			$dashboard_background_color->set_field_id ( 'wp_dashboard_color' );
			$dashboard_settings_form->add_form_field( $dashboard_background_color );
		}
		/*
		$production_dashboard_background_color = new text_input( 'Production dashboard background', 'english, hex, rgb, rgba, hsl, hsla color', preferences::get_database_option('production_dashboard_background_color') ) ;
		$general_settings_form->add_form_field($production_dashboard_background_color);
		*/

		$show_site_name = new checkbox ('Replace thank-you text with site name?',
			'checkbox-show_site_name',
			preferences::get_database_option('checkbox-show_site_name')
		) ;
		$show_site_name->set_label_help_text('This appears in the lower left corner of every page.');
		$dashboard_settings_form->add_form_field ( $show_site_name ) ;

		$show_marketing = new checkbox ('Replace WordPress version number with branding?',
			'checkbox-show_marketing',
			preferences::get_database_option('checkbox-show_marketing')
		) ;
		$show_marketing->set_label_help_text('This appears in the lower right corner of every page.');
		$show_marketing->set_field_help_text('Enter branding information in the field below.') ;
		$dashboard_settings_form->add_form_field ($show_marketing) ;

		$branding_info = new text_area (
			'Enter branding information:',
			'textarea-branding_info',
			'Your Company, Inc.',
			preferences::get_database_option('textarea-branding_info')) ;
		// $branding_info->set_field_help_text('Enter one tag per line, no angle brackets (&lt;&gt;) necessary.');
		$branding_info->set_show_label( false ) ;
		$branding_info->set_rows ( 2 );
		$branding_info->set_field_help_text('Note: HTML is OK.');

		$dashboard_settings_form->add_form_field ($branding_info) ;

		if ( utility::get_environment_type ( ) != 'Production' ) {
			$show_site_warning = new checkbox ('Display global site warning?',
				'checkbox-show_global_site_warning',
				preferences::get_database_option('checkbox-show_global_site_warning')
			) ;
			$show_site_warning->set_label_help_text('This message appears as a call-out at the top of every page in the dashboard.') ;
			$show_site_warning->set_field_help_text('Enter global site warning in the field below.') ;

			$dashboard_settings_form->add_form_field ($show_site_warning) ;

			$global_site_warning = new text_area (
				'Global site warning:',
				'textarea-global_site_warning',
				'Your Company, Inc.',
				preferences::get_database_option('textarea-global_site_warning')) ;
			// $branding_info->set_field_help_text('Enter one tag per line, no angle brackets (&lt;&gt;) necessary.');
			$global_site_warning->set_show_label( false ) ;
			$global_site_warning->set_rows ( 4 );
			$global_site_warning->set_field_help_text('Note: HTML is OK.');
			$dashboard_settings_form->add_form_field ($global_site_warning) ;
		}

		$show_dashboard_widget = new checkbox ('Display WordPress Care Package dashboard widget?',
			'checkbox-show_dashboard_widget',
			preferences::get_database_option('checkbox-show_dashboard_widget')
		) ;
		$show_dashboard_widget->set_label_help_text('Constant bdsl::show_dashboard_widget must also be true.');
		$show_dashboard_widget->set_field_help_text('Enter the widget title and body in the fields below.');
		$dashboard_settings_form->add_form_field ( $show_dashboard_widget ) ;

		$dashboard_widget_title = new text_input( 'Dashboard widget title:', 'text-dashboard_widget_title','Widget title', preferences::get_database_option('text-dashboard_widget_title') );
		$dashboard_widget_title->set_show_label ( false ) ;
		$dashboard_widget_title->set_field_help_text('Widget Title') ;
		$dashboard_settings_form->add_form_field ( $dashboard_widget_title ) ;

		$dashboard_widget_body = new text_area (
			'Dashboard widget body:',
			'textarea-dashboard_widget_body',
			'Widget body',
			preferences::get_database_option('textarea-dashboard_widget_body')) ;
		$dashboard_widget_body->set_show_label( false ) ;
		$dashboard_widget_body->set_rows ( 4 );
		$dashboard_widget_body->set_field_help_text('Widget Body. Note: HTML is OK.<br>The following variables are available: {author_company}, {author_company_url}, {home_url}, {hostname}, {support_email}, {support_phone}, {videoconference_url}.' ) ;
		$dashboard_settings_form->add_form_field ($dashboard_widget_body) ;

		$items = [] ;
		foreach ( $GLOBALS['menu'] as $menu ) {
			$menu_name = $menu[0];
			$menu_url  = $menu[2];
			// strip out menu names that contain html
			if ( strpos ( $menu_name, '<' ) !== false ) {
				list ( $menu_name_and_whitespace, ) = explode( '<', $menu_name ) ;
				$menu_name = trim ( $menu_name_and_whitespace ) ;
			}

			if ( $menu_name != '' && $menu_name != bdsl::plugin_name ) {
				$items[$menu_name] = $menu_url ;
			}
		}

		$dashboard_links = new checkbox_group (
			'Dashboard links to hide:',
			'checkboxes-dashboard_links_to_hide',
			$items
		) ;
		$dashboard_settings_form->add_form_field ($dashboard_links) ;

		// enable fixed table headers.
		$enable_fixed_admin_table_headers = new checkbox ('Enable fixed table headers on edit listing screens?',
			'checkbox-enable_fixed_admin_table_headers',
			preferences::get_database_option('checkbox-enable_fixed_admin_table_headers')
		) ;
		$dashboard_settings_form->add_form_field ( $enable_fixed_admin_table_headers ) ;

		// disable file editing.
		$disable_file_editing = new checkbox ('Disable theme and plugin file editor?',
			'checkbox-disable_file_editor',
			preferences::get_database_option('checkbox-disable_file_editor')
		) ;
		$disable_file_editing->set_label_help_text('Hides links to "Appearance > Theme Editor" and "Plugins > Plugin Editor."');
		$dashboard_settings_form->add_form_field ( $disable_file_editing ) ;

		// only show if toolset plugin is enabled.
		if ( $this->plugin['has_toolset'] ) {
			// hide toolset expiration notice.
			$hide_toolset_expiration_notice = new checkbox ( 'Hide Toolset plugin expiration notice?',
				'checkbox-hide_toolset_expiration_notice',
				preferences::get_database_option('checkbox-hide_toolset_expiration_notice')
			);
			$hide_toolset_expiration_notice->set_label_help_text( 'If <a href="https://toolset.com" target="_blank" rel="noopener">Toolset</a> has expired, check the box to hide the administrative notice.' );
			$dashboard_settings_form->add_form_field( $hide_toolset_expiration_notice );
		}

		// only show if toolset plugin is enabled.
		if ( $this->plugin['has_toolset'] ) {
			// sort Toolset custom taxonomies alphabetically.
			$enable_toolset_taxonomy_sort = new checkbox ( 'Sort Toolset custom taxonomies alphabetically?',
				'checkbox-enable_toolset_taxonomy_sort',
				preferences::get_database_option('checkbox-enable_toolset_taxonomy_sort')
			);
			$enable_toolset_taxonomy_sort->set_label_help_text('This sorts taxonomies in post submenus in alphabetical order. ') ;
			$dashboard_settings_form->add_form_field( $enable_toolset_taxonomy_sort );
		}

		// Output form.
		$dashboard_settings_form->render_form();

		utility::print_admin_settings_footer() ;
	}

	/**
	 * @param $default
	 *
	 * @return mixed|string
	 */
	public function footer_credit ( $default ) {

		if ( preferences::get_database_option('textarea-branding_info') ) {
			return preferences::get_database_option('textarea-branding_info') ;
		}
		// The default, if 'checkbox-show_marketing' is checked but the  'textarea-branding_info' is empty.
		return sprintf ( 'Website design and development by <a target="_blank" href="%2$s">%1$s</a>', bdsl::author_company, bdsl::author_company_url ) ;
	}

	/**
	 * @return void
	 */
	public function set_dashboard_background_color() {

		$body_background_color = '' ;
		$environment = utility::get_environment_type ( );

		if ( $environment == 'Development' ) {
			$body_background_color = preferences::get_database_option('dev_dashboard_background_color');
		}
		if ( $environment == 'Staging' ) {
			$body_background_color = preferences::get_database_option('staging_dashboard_background_color');
		}
		if ( $environment == 'Local Development' ) {
			$body_background_color = preferences::get_database_option('local_dashboard_background_color') ;
		}

		if ( $body_background_color ) {
			printf ('<!-- Special over-ride to distinguish dev and staging sites from production. --><style>body { background-color: %s }</style>', $body_background_color );
		}
	}

	/**
	 * @return void
	 */
	public function site_warning ( ) {
		if ( preferences::get_database_option('textarea-global_site_warning') ) {
			printf ('<div class="notice notice-warning bzmndsgn_site_warning">') ;
			printf( '<p>%s</p>', preferences::get_database_option('textarea-global_site_warning') ) ;
			printf ('</div>') ;
		}
	}

	/**
	 * @return void
	 */
	public function add_issue_link ( ) {
		global $menu ;
		// FIXME: link value should be set in preferences.
		// Set position in preferences too?
		// $menu[28][2] = 'https://bitbucket.org/baizmandesign/carroll-and-sons-wp-plugin/issues/new' ;
	}

	/**
	 * @return void
	 */
	public function quell_toolset_expiration_notice () {
		printf ( '<style>' ) ;
		printf ( '/* Hide "Register Toolset" message for lapsed subscriptions. */ ' );
		printf ( 'div.toolset-notice-wp.notice.toolset-notice { display: none ; }' ) ;
		printf ( '</style>' ) ;
	}

	/**
	 * FIXME: this doesn't work anymore.
	 * @return void
	 */
	public function add_fixed_header_to_admin_tables ( ) {
		printf ( '<style>' ) ;
		printf ( '/* https://catalin.red/sticky-table-th/ */ ');
		printf ( '/* Add sticky header to admin tables / indexical listings. */ ');
		printf ( 'thead.sticky th, thead.sticky #cb { ');
		printf ( 'top: 32px ;');
		printf ( 'position: -webkit-sticky;');
		printf ( 'position: -moz-sticky;');
		printf ( 'position: -ms-sticky;');
		printf ( 'position: -o-sticky;');
		printf ( 'position: sticky;');
		printf ( 'background-color: rgb(255, 255, 255) ; }');
		printf ( '</style>' ) ;
	}

	/**
	 * @return void
	 */
	public function disable_file_editor ( ) {
		if ( ! defined ( 'DISALLOW_FILE_EDIT' ) ) {
			define( 'DISALLOW_FILE_EDIT', true );
		}
	}

	/**
	 * @param $taxonomies
	 *
	 * @return mixed
	 */
	public function sort_custom_toolset_taxonomies ( $taxonomies ) {
		ksort ($taxonomies ) ;
		return $taxonomies;
	}

	/**
	 * @param $default
	 *
	 * @return string
	 */
	public function footer_site_name ( $default ): string {

		$environment = utility::get_environment_type ( );
		$server_name = gethostname() ;

		if ( $this->plugin['is_multisite'] ) {
			// https://wordpress.stackexchange.com/questions/15309/how-to-get-blog-name-when-using-wordpress-multisite
			global $blog_id;
			$current_blog_details = get_blog_details ( array( 'blog_id' => $blog_id ) );
			return sprintf ( '<a href="%2$s" target="_blank">%1$s</a> &gt; <a href="%4$s" target="_blank">%3$s</a> (%5$s on %6$s)', $this->plugin['multisite_network_name'],
				network_home_url (),
				$current_blog_details->blogname,
				$current_blog_details->home,
				$environment,
				$server_name
			);
		}
		return sprintf ( '<a href="%2$s" target="_blank">%1$s</a> (%3$s on %4$s)',
			get_bloginfo ( 'name' ),
			home_url ( ),
			$environment,
			$server_name
		);
	}

	/**
	 * @param $admin_bar
	 *
	 * @return void
	 */
	public function add_toolbar_items( $admin_bar ) {

		$menu_items = [] ;

		// Plugins
		$menu_items[] = [
			'id'     => 'plugins',
			'parent' => 'appearance',
			'title'  => 'Plugins',
			'href'   => admin_url( 'plugins.php' ),
			'meta'   => [],
		] ;

		// Spacer
		$menu_items[] = [
			'id'     => 'spacer',
			'parent' => 'appearance',
			'title'  => '--',
			'href'   => '',
			'meta'   => [],		] ;

		// Custom post types
		$custom_post_types = get_post_types(['public'=>true,'_builtin' => false], 'objects') ;
		$custom_post_types_array = [] ;

		foreach ( $custom_post_types as $custom_post_type ) {
			$custom_post_types_array[$custom_post_type->name] = $custom_post_type->label ;
		}

		// sort by label
		asort($custom_post_types_array);

		foreach ( $custom_post_types_array as $cpt_name => $cpt_label ) {
			$menu_items[] = [
				'id'     => $cpt_name,
				'parent' => 'appearance',
				'title'  => $cpt_label,
				'href'   => admin_url( 'edit.php?post_type=' . $cpt_name ),
				'meta'   => [],
			] ;
		}

		// add link to enable / disable query monitor, if needed, but only for admins.
        $user_data = get_userdata (get_current_user_id());
        if ( in_array ('administrator',$user_data->roles ) ) {

            $all_plugins = get_plugins();
            if (in_array('query-monitor/query-monitor.php', array_keys($all_plugins))) {
                $menu_items[] = [
                    'id' => 'toggle-qm',
                    'parent' => '',
                    'title' => 'Toggle QM',
                    'href' => '',
                    'meta' => [],];
            }
        }
		foreach ( $menu_items as $menu_item ) {
			// TODO: check if the item is already in the list of menu items.
			$admin_bar->add_menu( $menu_item );
		}

	}

	/**
	 * @return void
	 */
	public function qm_link_style() {
		// can't run this outside of the function, even when referring to the global namespace, due to a php fatal error.
		if (is_user_logged_in()) { // only for logged in users
			?>
			<style>
                /* toggle query monitor link. */
                li#wp-admin-bar-toggle-qm .ab-item:hover {
                    cursor: pointer;
                }
			</style>
			<?php
		}
	}

	/**
	 * @param $hook
	 *
	 * @return void
	 */
	public function enqueue_qm_ajax( $hook ) {

		wp_enqueue_script(
			'ajax-script',
			$this->plugin['plugin_folder_url'] . 'js/ajax.js',
			array( 'jquery' ),
			'1.0.0',
			true
		);

		wp_localize_script(
			'ajax-script',
			'my_ajax_obj',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'toggle_qm' ),
			)
		);

	}
	/**
	 * Handle AJAX request to toggle Query Monitor plugin.
	 *
	 * @return void
	 */
	public function ajax_toggle_query_monitor() {
		// Handle the ajax request here
		check_ajax_referer( 'toggle_qm' );

		// is query monitor installed?

		$qm_plugin = 'query-monitor/query-monitor.php' ;

		// is plugin active?
		// https://developer.wordpress.org/reference/functions/is_plugin_active/
		if ( in_array($qm_plugin, apply_filters('active_plugins', get_option('active_plugins'))) ) {
			// no return value
			$result = deactivate_plugins( $qm_plugin );
		} else {
			// activate plugin
			// https://developer.wordpress.org/reference/functions/activate_plugin/
			// returns null on success
			$result = activate_plugin( $qm_plugin );
		}

		wp_send_json ( [ 'return_status' => $result ] ) ;

		wp_die(); // All ajax handlers die when finished
	}

	/**
     * Callback for outputting contents of the widget.
	 * @return void
	 */
	public function admin_dashboard_widget( ) {

		$substitutions = array (
			'{author_company}' => bdsl::author_company,
			'{author_company_url}' => bdsl::author_company_url,
			'{home_url}' => home_url(),
			'{hostname}' => parse_url(home_url(),PHP_URL_HOST),
			'{support_email}' => bdsl::plugin_support_email,
			'{support_phone}' => bdsl::plugin_author_phone,
			'{videoconference_url}' => 'https://meet.google.com',
		) ;

		$dashboard_widget_body = preferences::get_database_option('textarea-dashboard_widget_body') ;
		if ( $dashboard_widget_body ) {
			$dashboard_widget_body = strtr ( $dashboard_widget_body, $substitutions ) ;
			printf ( '%s', $dashboard_widget_body ) ;

			if ( get_bloginfo('admin_email') != bdsl::plugin_author_email ) {
				printf('<p><small>Note: the admin email address of this site is set to <strong>%1$s</strong>, which means Baizman Design may miss critical system notifications. <a href="%2$s">Update the admin address here.</a></small></p>', get_bloginfo('admin_email'), admin_url('options-general.php') ) ;
			}
		}
	}

	/**
	 * @return void
	 */
	public function add_admin_dashboard_widget( ) {
		$dashboard_widget_title = preferences::get_database_option('text-dashboard_widget_title') ?? 'Widget Title' ;

		// Only display the widget if the user is an admin.
        // Note: this is a rare instance of a hook *not* located in the constructor.
		if ( current_user_can ( 'manage_options' ) ) {
			wp_add_dashboard_widget( bdsl::prefix.'_admin_dashboard_widget',
				$dashboard_widget_title,
				 [$this,'admin_dashboard_widget'] );
		}
	}

	/**
	 * Remove WP admin dashboard widgets.
	 * https://isabelcastillo.com/remove-wordpress-dashboard-widgets
	 * TODO: make this a setting in the admin panel?
	 */
	public function disable_dashboard_widgets ( ) {
		// remove_meta_box('dashboard_right_now', 'dashboard', 'normal');// Remove "At a Glance"
		remove_meta_box ('dashboard_activity', 'dashboard', 'normal');// Remove "Activity" which includes "Recent Comments"
		remove_meta_box ('dashboard_quick_press', 'dashboard', 'side');// Remove Quick Draft
		remove_meta_box ('dashboard_primary', 'dashboard', 'core');// Remove WordPress Events and News
	}

	/**
	 * Add custom styles to tinyMCE editor.
	 */
	function custom_editor_styles ( ) {
		add_editor_style ( $this->plugin['plugin_folder_url'] . 'css/editor-styles.css' ) ;
	}

}
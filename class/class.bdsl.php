<?php
/**
 * Class for a plugin container.
 * @package Baizman Design Standard Library
 * @version 0.1
 * @link https://www.smashingmagazine.com/2015/05/how-to-use-autoloading-and-a-plugin-container-in-wordpress-plugins/
 */

namespace baizman_design ;

class bdsl {

	// toggle visibility of menus in wp dashboard
	const show_dashboard_interface = true ;

	// toggle visibility of meta box in wp dashboard
	const show_dashboard_widget = true ;

	// toggle debug status
	const debug = false ;

	// set filename for debug output
	const debug_log = 'bdsl-debug.log' ;

	const config_options_key = 'bzmndsgn_config_options' ;
	const settings_group = 'bzmndsgn-standard-library-plugin-settings-group' ;

	const multisite_config_options_key = 'bzmndsgn_multisite_config_options';
	const network_settings_group = 'bzmndsgn-standard-library-plugin-network-settings-group' ;

	const nonce_name = 'bzmndsgn_save_config' ;

	const plugin_name = 'Baizman Design Standard Library';

	// FIXME: this should be dynamically calculated
	const plugin_filename = 'baizman-design-standard-library.php' ;

	const parent_menu_slug = 'bzmndsgn_general_settings';

	const plugin_namespace = __NAMESPACE__;

	const plugin_author_company = 'Baizman Design' ;

	const plugin_author_company_url = 'https://baizmandesign.com' ;

	const plugin_author_name = 'Saul Baizman';

	const plugin_author_email = 'saul@baizmandesign.com';

	const plugin_support_email = 'support@baizmandesign.com';

	const plugin_author_phone = '1-617-863-7165';

	public bool $is_multisite;

	public string $multisite_network_name;

	public string $document_root_uri;

	public string $document_root_url;

	public string $theme_folder_uri;

	public string $theme_folder_url;

	public string $plugin_folder_uri;

	public string $plugin_folder_url;

	public bool $has_toolset;

	/**
	 * bdsl constructor.
	 */
	public function __construct( ) {
		add_filter( 'plugin_row_meta', [$this, 'filter_plugin_links'], 10, 2 );

		if (bdsl::show_dashboard_interface) {
			add_action( 'admin_menu', [ $this, 'print_admin_menu' ], 1 );
		}

		add_action( 'admin_init', [ __NAMESPACE__.'\form', 'site_config_settings' ] );
		add_action( 'admin_post_update_bdsl', [ __NAMESPACE__.'\form','save_form_data'] );

		// TODO: replace function with variable.
		if ( is_multisite() ) {
			add_action( 'network_admin_edit_bzmndsgn_save_network_config_settings', [__NAMESPACE__.'\form', 'save_network_form_data'] );
			add_action( 'admin_init', [__NAMESPACE__.'\form', 'network_config_settings'] );
		}

		add_action ( 'admin_enqueue_scripts', [$this,'load_js_and_css'] ) ;

		// check for updates
		updater::add_filter();

	}

	/**
	 * @return void
	 */
	public function load_js_and_css ( ) {
		wp_enqueue_script ( preferences::prefix.'-js', $this->plugin_folder_url . 'js/admin-scripts.js', false, false, true) ;
		wp_enqueue_style( preferences::prefix.'-css', $this->plugin_folder_url . 'css/admin-style.css', false, false, 'all' ) ;
	}

	/**
	 * Add custom links to the plugin listing page.
	 * @param $links
	 * @param $file
	 *
	 * @return mixed
	 */
	public function filter_plugin_links( $links, $file ) {

		// "folder/filename.php"
		$plugin_folder_and_filename = trailingslashit(preferences::$plugin_slug) . basename(preferences::$plugin_file_path) ;

		if ( $file == $plugin_folder_and_filename ) {
			$links[] = sprintf( '<a href="%s" rel="noopener"><span class="dashicons dashicons-admin-generic"></span> Settings</a>', home_url( '/wp-admin/admin.php?page=bzmndsgn_general_settings' ) );
			$links[] = sprintf( '<a href="%s"  rel="noopener" target="%s"><span class="dashicons dashicons-welcome-write-blog"></span> Report an Issue</a>', 'https://bitbucket.org/baizmandesign/baizman-design-standard-library-wp-plugin/issues/new', '_blank' );
		}

		return $links;
	}

	/**
	 *
	 */
	// TODO: move location of BDSL link lower in left column of WP dashboard.
	public function print_admin_menu () {
		/* Top-level menu. */

	}

	/**
	 * Add hooks for the login page.
	 * @return void
	 */
	public function add_login_hooks ( ) {
		add_action( 'login_enqueue_scripts', [$this, 'login_stylesheet'] ) ;
		add_filter( 'login_message', [$this,'login_screen_message'] ) ;
	}

	/**
	 * Add custom styles on WP login screen.
	 */
	public function login_stylesheet ( ) {
		wp_enqueue_style ( 'custom-login', $this->plugin_folder_url . 'css/login-styles.css' ) ;
	}

	/**
	 * Customize WP login screen.
	 * https://codex.wordpress.org/Customizing_the_Login_Form
	 * https://codex.wordpress.org/Plugin_API/Filter_Reference/login_message
	 *
	 * @param $message
	 *
	 * @return string
	 */
	public function login_screen_message ( $message ): string {
		if ( empty ( $message ) ) {
			$website_name = get_bloginfo('name') ;
			if ( $this->is_multisite ) {
				$h2_text = $this->multisite_network_name ;
				$h3_text = $website_name ;
			}
			else {
				$h2_text = $website_name ;
				$h3_text = false ;
			}
			return sprintf ( '<h2><a title="%1$s" href="%3$s">%1$s</a></h2><h3>%2$s</h3>',$h2_text, $h3_text, home_url ( ) ) ;
		} else {
			return $message;
		}
	}

}
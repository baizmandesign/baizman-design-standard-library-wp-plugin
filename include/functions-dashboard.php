<?php
/**
 * Dashboard customizations.
 */

defined ( 'ABSPATH' ) or die ( 'This file cannot be run outside of WordPress.' ) ;

$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

if ( ! function_exists ( 'bzmndsgn_admin_dashboard_widget' ) ):
    /**
     * This function outputs the content of the admin dashboard widget.
     */
    function bzmndsgn_admin_dashboard_widget( ) {

        $substitutions = array (
            '{author_company}' => BZMNDSGN_AUTHOR_COMPANY,
            '{author_company_url}' => BZMNDSGN_AUTHOR_COMPANY_URL,
            '{home_url}' => home_url(),
            '{hostname}' => parse_url(home_url(),PHP_URL_HOST),
            '{support_email}' => BZMNDSGN_SUPPORT_EMAIL,
            '{support_phone}' => BZMNDSGN_AUTHOR_PHONE,
            '{videoconference_url}' => 'https://meet.google.com',
        ) ;

        // FIXME: return to the GLOBALS issue.
        $dashboard_widget_body = $GLOBALS[BZMNDSGN_CONFIG_OPTIONS]['textarea-dashboard_widget_body'] ;
	    if ( $dashboard_widget_body ) {
            $dashboard_widget_body = strtr ( $dashboard_widget_body, $substitutions ) ;
            printf ( '%s', $dashboard_widget_body ) ;

            if ( get_bloginfo('admin_email') != BZMNDSGN_AUTHOR_EMAIL ) {
                printf('<p><small>Note: the admin email address of this site is set to <strong>%1$s</strong>, which means Baizman Design may miss critical system notifications. <a href="%2$s">Update the admin address here.</a></small></p>', get_bloginfo('admin_email'), home_url('/wp-admin/options-general.php') ) ;
            }
        }
    }

    if ( _is_enabled ( 'checkbox-show_dashboard_widget', $bzmndsgn_config_options_database ) && BZMNDSGN_SHOW_DASHBOARD_WIDGET ) {
        // Add the correct hook.
        if ( BZMNDSGN_IS_MULTISITE ) {
            add_action( 'wp_network_dashboard_setup', 'bzmndsgn_add_admin_dashboard_widget' );
        } else {
            add_action( 'wp_dashboard_setup', 'bzmndsgn_add_admin_dashboard_widget' );
        }
    }
endif;

if ( ! function_exists ( 'bzmndsgn_add_admin_dashboard_widget' ) ):
    /**
     * Add the widget to the admin dashboard.
     */
    function bzmndsgn_add_admin_dashboard_widget( ) {
	    $dashboard_widget_title = $GLOBALS['bzmndsgn_config_options']['text-dashboard_widget_title'] ? $GLOBALS['bzmndsgn_config_options']['text-dashboard_widget_title'] : 'Widget Title' ;

	    wp_add_dashboard_widget( 'bzmndsgn_admin_dashboard_widget',
		    $dashboard_widget_title,
            'bzmndsgn_admin_dashboard_widget' );
    }
endif;

if ( ! function_exists ( 'bzmndsgn_disable_dashboard_widgets' ) ):
	/**
	 * Remove WP admin dashboard widgets.
	 * https://isabelcastillo.com/remove-wordpress-dashboard-widgets
	 * TODO: make this a setting in the admin panel?
	 */
	function bzmndsgn_disable_dashboard_widgets ( ) {
		// remove_meta_box('dashboard_right_now', 'dashboard', 'normal');// Remove "At a Glance"
		remove_meta_box ('dashboard_activity', 'dashboard', 'normal');// Remove "Activity" which includes "Recent Comments"
		remove_meta_box ('dashboard_quick_press', 'dashboard', 'side');// Remove Quick Draft
		remove_meta_box ('dashboard_primary', 'dashboard', 'core');// Remove WordPress Events and News
	}
	add_action('admin_menu', 'bzmndsgn_disable_dashboard_widgets');
endif;

if ( ! function_exists ( 'bzmndsgn_remove_menus' ) ):
	/**
	 * Remove unused links in admin menu.
	 */
	function bzmndsgn_remove_menus ( )
	{
		$dashboard_links_to_remove = $GLOBALS[BZMNDSGN_CONFIG_OPTIONS]['checkboxes-dashboard_links_to_hide'] ;

		if ( count ( $dashboard_links_to_remove ) > 0 ) {
			// the only items in this array are the items that have been checked.
			foreach ( $dashboard_links_to_remove as $name => $url ) {
				remove_menu_page ( $url );
			}
		}
	}
	add_action( 'admin_menu', 'bzmndsgn_remove_menus' );
endif;

if ( ! function_exists ( 'bzmndsgn_custom_editor_styles' ) ):
	/**
	 * Add custom styles to tinyMCE editor.
	 */
	function bzmndsgn_custom_editor_styles ( ) {
		add_editor_style ( BZMNDSGN_PLUGIN_FOLDER_URL . 'css/editor-styles.css' ) ;
	}
	add_action ( 'init', 'bzmndsgn_custom_editor_styles' ) ;
endif;

if ( ! function_exists ( 'bzmndsgn_admin_stylesheet' ) ):
	/**
	 * Add CSS and JS for admin interface.
	 */
	function bzmndsgn_admin_stylesheet() {
		wp_enqueue_style ('bzmndsgn-admin-styles', BZMNDSGN_PLUGIN_FOLDER_URL . 'css/admin-style.css');
		wp_enqueue_script ( 'bzmndsgn-admin-scripts', BZMNDSGN_PLUGIN_FOLDER_URL . 'js/admin-scripts.js' ) ;

	}
	add_action('admin_enqueue_scripts', 'bzmndsgn_admin_stylesheet');
endif;

if ( ! function_exists ( 'bzmndsgn_footer_site_name' ) ):
	/**
	 * Return the new left footer.
	 *
	 * @param $default
	 *
	 * @return string|void
	 */
	function bzmndsgn_footer_site_name ( $default ) {

	    $environment = _get_environment_type ( );

		if ( BZMNDSGN_IS_MULTISITE ) {
			// https://wordpress.stackexchange.com/questions/15309/how-to-get-blog-name-when-using-wordpress-multisite
			global $blog_id;
			$current_blog_details = get_blog_details ( array( 'blog_id' => $blog_id ) );
			return sprintf ( '<a href="%2$s" target="_blank">%1$s</a> &gt; <a href="%4$s" target="_blank">%3$s</a> (%5$s)', BZMNDSGN_MULTISITE_NETWORK_NAME, network_home_url (), $current_blog_details->blogname, $current_blog_details->home, $environment );
		}
		return sprintf ( '<a href="%2$s" target="_blank">%1$s</a> (%3$s)',get_bloginfo ( 'name' ), home_url ( ), $environment );
	}
	if ( _is_enabled ( 'checkbox-show_site_name', $bzmndsgn_config_options_database ) ) {
		add_filter( 'admin_footer_text', 'bzmndsgn_footer_site_name' );
	}
endif;

if ( ! function_exists ( 'bzmndsgn_footer_credit' ) ):
	/**
	 * Return the new right footer.
	 *
	 * @param $default
	 *
	 * @return string
	 */
	function bzmndsgn_footer_credit ( $default ) {
		$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );
        if ( $bzmndsgn_config_options_database['textarea-branding_info'] ) {
	        return $bzmndsgn_config_options_database['textarea-branding_info'] ;
        }
        // The default, if 'checkbox-show_marketing' is checked but the  'textarea-branding_info' is empty.
		return sprintf ( 'Website design and development by <a target="_blank" href="%2$s">%1$s</a>', BZMNDSGN_AUTHOR_COMPANY, BZMNDSGN_AUTHOR_COMPANY_URL ) ;
	}
	if ( _is_enabled ( 'checkbox-show_marketing', $bzmndsgn_config_options_database ) ) {
		add_filter( 'update_footer', 'bzmndsgn_footer_credit', 11 );
	}
endif ;

if ( ! function_exists ('bzmndsgn_set_dashboard_background_color' ) ):
    /**
     * Set background color of WP dashboard to distinguish dev and staging sites from production.
     */
	function bzmndsgn_set_dashboard_background_color() {

		$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

		$body_background_color = '' ;
		$environment = _get_environment_type ( );

		if ( $environment == 'Development' ) {
			$body_background_color = $bzmndsgn_config_options_database['dev_dashboard_background_color'];
		}
		if ( $environment == 'Staging' ) {
			$body_background_color = $bzmndsgn_config_options_database['staging_dashboard_background_color'];
		}
		if ( $environment == 'Local Development' ) {
			$body_background_color = $bzmndsgn_config_options_database['local_dashboard_background_color'] ;
		}

		if ( $body_background_color ) {
			printf ('<!-- Special over-ride to distinguish dev and staging sites from production. --><style type="text/css">body { background-color: %s }</style>', $body_background_color );
		}
	}
	add_action( 'admin_head', 'bzmndsgn_set_dashboard_background_color' );
endif;

if ( ! function_exists('bzmndsgn_site_warning' ) ) {
	/**
	 * Display global warning in WP dashboard if we are working on a dev site.
	 */
	function bzmndsgn_site_warning ( ) {
			$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );
        if ( $bzmndsgn_config_options_database['textarea-global_site_warning'] ) {
            printf ('<div class="notice notice-warning bzmndsgn_site_warning">') ;
            printf( '<p>%s</p>', $bzmndsgn_config_options_database['textarea-global_site_warning'] ) ;
            printf ('</div>') ;
        }
    }

    if ( _is_enabled ('checkbox-show_global_site_warning', $bzmndsgn_config_options_database ) ) {
        add_action ( 'admin_notices', 'bzmndsgn_site_warning' ) ;
    }
}

if ( ! function_exists('bzmndsgn_issue_link')):
    /**
     * Set URL of "Report an issue" link.
     */
    function bzmndsgn_issue_link ( ) {
        global $menu ;
        // FIXME: link value should be set in preferences.
        // Set position in preferences too?
        // $menu[28][2] = 'https://bitbucket.org/baizmandesign/carroll-and-sons-wp-plugin/issues/new' ;
    }
    add_action ( 'admin_menu' , 'bzmndsgn_issue_link' ) ;
endif ;

if ( ! function_exists('bzmndsgn_quell_toolset_expiration_notice')) :
	/**
	 * Quell Toolset expiration notice.
	 */
	function bzmndsgn_quell_toolset_expiration_notice () {
		$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );
		printf ( '<style type="text/css">' ) ;
		printf ( '/* Hide "Register Toolset" message for lapsed subscriptions. */ ' );
		printf ( 'div.toolset-notice-wp.notice.toolset-notice { display: none ; }' ) ;
		printf ( '</style>' ) ;
	}
	if ( _is_enabled ('checkbox-hide_toolset_expiration_notice', $bzmndsgn_config_options_database ) ) {
		add_action( 'admin_head', 'bzmndsgn_quell_toolset_expiration_notice' );
	}
endif;
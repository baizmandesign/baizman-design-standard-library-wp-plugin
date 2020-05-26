<?php
/**
 * Dashboard customizations.
 */

if ( BZMNDSGN_SHOW_DASHBOARD_WIDGET ) {
	if ( ! function_exists ( 'bzmndsgn_admin_dashboard_widget' ) ):
		/**
		 * This function outputs the content of the admin dashboard widget.
		 */
		function bzmndsgn_admin_dashboard_widget( ) {

			printf( '<p>This website (<a href="%3$s">%4$s</a>) has a contract with <a href="%2$s" target="_blank">%1$s</a> for monthly support and maintenance.</p>',BZMNDSGN_AUTHOR_COMPANY, BZMNDSGN_AUTHOR_COMPANY_URL, home_url(), parse_url(home_url(),PHP_URL_HOST)) ;
			printf( '
<p>The contract includes up to two hours per month for the following:</p>
<ul>
<li>+ technical support via <a href="mailto:%1$s">email</a>, <a href="tel:%2$s">phone</a>, <a href="%3$s">videoconference</a>, or in-person consultation.</li> 
<li>+ custom website development (e.g., a new feature).</li>
<li>+ WordPress training for new and existing users.</li>
</ul>
', BZMNDSGN_SUPPORT_EMAIL, BZMNDSGN_AUTHOR_PHONE, 'https://meet.google.com' ) ;

			if ( get_bloginfo('admin_email') != BZMNDSGN_AUTHOR_EMAIL ) {
				printf('<p><small>Note: the admin email address of this site is set to <strong>%1$s</strong>, which means Baizman Design may miss critical system notifications. <a href="%2$s">Update the admin address here.</a></small></p>', get_bloginfo('admin_email'), home_url('/wp-admin/options-general.php') ) ;

			}
		}
		// Add the correct hook.
		if ( BZMNDSGN_IS_MULTISITE ) {
			add_action( 'wp_network_dashboard_setup', 'bzmndsgn_add_admin_dashboard_widget' );
		} else {
			add_action( 'wp_dashboard_setup', 'bzmndsgn_add_admin_dashboard_widget' );
		}
	endif;

	if ( ! function_exists ( 'bzmndsgn_add_admin_dashboard_widget' ) ):
	/**
	 * Add the widget to the admin dashboard.
	 */
		function bzmndsgn_add_admin_dashboard_widget( ) {
			wp_add_dashboard_widget( 'bzmndsgn_admin_dashboard_widget',
				'Website Support and Maintenance',
				'bzmndsgn_admin_dashboard_widget' );
		}
	endif;
}

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
	 * TODO: make this a setting in the admin panel.
	 */
	function bzmndsgn_remove_menus ( )
	{
		remove_menu_page ( 'edit-comments.php' ); // Comments
		remove_menu_page ( 'edit.php' ); // Posts
	}
	add_action( 'admin_menu', 'bzmndsgn_remove_menus' );
endif;

if ( ! function_exists ( 'bzmndsgn_custom_editor_styles' ) ):
	/**
	 * Add custom styles to tinyMCE editor.
	 */
	function bzmndsgn_custom_editor_styles ( ) {
		add_editor_style ( BZMNDSGN_LIBRARY_FOLDER_URL . 'css/editor-styles.css' ) ;
	}
	add_action ( 'init', 'bzmndsgn_custom_editor_styles' ) ;
endif;

if ( ! function_exists ( 'bzmndsgn_admin_stylesheet' ) ):
	/**
	 * Add CSS and JS for admin interface.
	 */
	function bzmndsgn_admin_stylesheet() {
		wp_enqueue_style ('bzmndsgn-admin-styles', BZMNDSGN_LIBRARY_FOLDER_URL . 'css/admin-style.css');
		wp_enqueue_script ( 'bzmndsgn-admin-scripts', BZMNDSGN_LIBRARY_FOLDER_URL . 'js/admin-scripts.js' ) ;

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

		if ( BZMNDSGN_IS_MULTISITE ) {
			// https://wordpress.stackexchange.com/questions/15309/how-to-get-blog-name-when-using-wordpress-multisite
			global $blog_id;
			$current_blog_details = get_blog_details ( array( 'blog_id' => $blog_id ) );
			return sprintf ( '<a href="%2$s" target="_blank">%1$s</a> &gt; <a href="%4$s" target="_blank">%3$s</a>', BZMNDSGN_MULTISITE_NETWORK_NAME, network_home_url (), $current_blog_details->blogname, $current_blog_details->home );
		}
		return sprintf ( '<a href="%2$s" target="_blank">%1$s</a>',get_bloginfo ( 'name' ), home_url ( ) );
	}
	add_filter ( 'admin_footer_text', 'bzmndsgn_footer_site_name' ) ;
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
		return sprintf ( 'Website design and development by <a target="_blank" href="%2$s">%1$s</a>', BZMNDSGN_AUTHOR_COMPANY, BZMNDSGN_AUTHOR_COMPANY_URL ) ;
	}
	add_filter ( 'update_footer', 'bzmndsgn_footer_credit', 11 ) ;
endif ;

if ( ! function_exists ( 'bzmndsgn_dev_site_warning' ) ):
	/**
	 * Display global warning in WP dashboard if we are working on a dev site.
	 */
	function bzmndsgn_dev_site_warning ( ) {
		if ( strpos ( $_SERVER['HTTP_HOST'], 'dev.' ) !== false ) {
			?>
			<div class="error bzmndsgn_dev_site_warning-error">
				<p><strong>WARNING: this is a development server meant for experimental purposes only. Content saved on this site may be removed at any time without notice, and certain functions may not be fully configured or operational. Need assistance? <?php printf ( '<a href="mailto:%2$s">Please email %1$s.</a>',BZMNDSGN_AUTHOR_COMPANY, BZMNDSGN_SUPPORT_EMAIL) ;?></strong></p>
			</div>
			<?php
		}
	}
	add_action ( 'admin_notices', 'bzmndsgn_dev_site_warning' ) ;
endif;
<?php
/**
 * @package Baizman Design Standard Library
 * @version 0.1
 */

define ( 'DEBUG', false ) ;

define ( 'DEBUG_LOG', 'debug.log' ) ;

define ( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] ) ;

define ( 'THEME_FOLDER', trailingslashit ( get_stylesheet_directory_uri ( ) ) ) ;

define ( 'LIBRARY_FOLDER', trailingslashit ( plugin_dir_url ( __FILE__ ) ) ) ;

define ( 'AUTHOR_NAME', 'Saul Baizman' ) ;

define ( 'AUTHOR_EMAIL', 'saul@baizmandesign.com' ) ;

define ( 'AUTHOR_COMPANY', 'Baizman Design' ) ;

define ( 'AUTHOR_COMPANY_URL', 'https://baizmandesign.com' ) ;

define ( 'BZMNDSGN_CONFIG_OPTIONS', 'bzmndsgn_config_options' ) ;

// Load per-site plugin settings.
$bzmndsgn_config_options = get_option ( BZMNDSGN_CONFIG_OPTIONS ) ;

define ( 'NOT_FOUND_404_LOG_FILE', sprintf ('%s-%s',$bzmndsgn_config_options['log_file_prefix'], '404.log' ) ) ;

/**
 * Include admin interface if we are viewing the backend.
 */
if ( is_admin ( ) ) {
	require_once trailingslashit ( plugin_dir_path ( __FILE__ ) ) . 'admin.php' ;
}

/**
 * Add Google Analytics tracking code to global header.
 */
function bzmndsgn_enqueue_google_analytics ( ) {
//	global $bzmndsgn_config_options ;

	$bzmndsgn_config_options['google_analytics_id'] = 'UA-77345635-1' ;

	if ( $bzmndsgn_config_options['google_analytics_id'] ) {
		?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $bzmndsgn_config_options['google_analytics_id'] ; ?>"></script>
		<script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo $bzmndsgn_config_options['google_analytics_id'] ; ?>');
		</script>
		<?php
	}
}
add_action ( 'wp_head', 'bzmndsgn_enqueue_google_analytics', 1 ) ;

/**
 * Add custom styles to tinyMCE editor.
 */
function bzmndsgn_custom_editor_styles ( ) {
	add_editor_style ( LIBRARY_FOLDER . 'css/editor-styles.css' ) ;
}
add_action ( 'init', 'bzmndsgn_custom_editor_styles' ) ;

/**
 * Add CSS and JS for admin interface.
 */
function admin_stylesheet() {
	wp_enqueue_style ('bzmndsgn-admin-styles', LIBRARY_FOLDER . 'css/admin-style.css');
	wp_enqueue_script ( 'bzmndsgn-admin-scripts', LIBRARY_FOLDER . 'js/admin-scripts.js' ) ;

}
add_action('admin_enqueue_scripts', 'admin_stylesheet');

/**
 * Add custom styles on WP login screen.
 */
function bzmndsgn_login_stylesheet ( ) {
	wp_enqueue_style ( 'custom-login', LIBRARY_FOLDER . 'css/login-styles.css.php' ) ;
	// wp_enqueue_style ( 'typography-styles', LIBRARY_FOLDER . 'css/fonts.css' ) ;
}
add_action ( 'login_enqueue_scripts', 'bzmndsgn_login_stylesheet' ) ;

/**
 * Customize WP login screen.
 * https://codex.wordpress.org/Customizing_the_Login_Form
 * https://codex.wordpress.org/Plugin_API/Filter_Reference/login_message
 *
 * @param $message
 *
 * @return string
 */
function bzmndsgn_login_screen_message ( $message ) {
	if ( empty ( $message ) ) {
		$h2_text = get_bloginfo('name') ;
		$h3_text = false ;
		if ( is_multisite( ) ) {
			$network = get_network() ;
			$h2_text = $network->site_name ;
			$h3_text = get_bloginfo('name') ;
		}
		return sprintf ( '<h2><a title="%1$s" href="%3$s">%1$s</a></h2><h3>%2$s</h3>',$h2_text, $h3_text, home_url ( ) ) ;
	} else {
		return $message;
	}
}
add_filter( 'login_message', 'bzmndsgn_login_screen_message' ) ;

/**
 * Return the new left footer.
 *
 * @param $default
 *
 * @return string|void
 */
function bzmndsgn_footer_site_name ( $default ) {

	if ( is_multisite( ) ) {
		// https://wordpress.stackexchange.com/questions/15309/how-to-get-blog-name-when-using-wordpress-multisite
		global $blog_id;
		$current_blog_details = get_blog_details ( array( 'blog_id' => $blog_id ) );
		$network = get_network() ;
		return sprintf ( '%1$s / %2$s', $network->site_name, $current_blog_details->blogname );
	}
	return get_bloginfo ( 'name' ) ;
}
add_filter ( 'admin_footer_text', 'bzmndsgn_footer_site_name' ) ;

/**
 * Return the new right footer.
 *
 * @param $default
 *
 * @return string
 */
function bzmndsgn_footer_credit ( $default ) {
	return sprintf ( 'Website design and development by <a target="_blank" href="%2$s">%1$s</a>', AUTHOR_COMPANY, AUTHOR_COMPANY_URL ) ;
}
add_filter ( 'update_footer', 'bzmndsgn_footer_credit', 11 ) ;

/**
 * Return requested site option value from the database.
 *
 * @param $attributes
 * @param null $content
 *
 * @return string
 */
function bzmndsgn_get_site_database_setting ( $attributes, $content = null ) {
	global $bzmndsgn_config_options ;

	if ( ! isset ( $attributes['key'] ) ) {
		return '<span class="lta_plugin_error_message">Please specify a key via the shortcode attribute "key."</span>' ;
	}

	if ( isset ( $bzmndsgn_config_options[$attributes['key']] ) ) {
		return $bzmndsgn_config_options[$attributes['key']] ;
	}
	else {
		return sprintf ('The key %s does not exist.', $attributes['key'] );
	}
}
add_shortcode ( 'bzmndsgn_get_site_database_setting', 'bzmndsgn_get_site_database_setting' ) ;

/**
 * Helper function that returns theme folder.
 *
 * @return string
 */
function bzmndsgn_get_theme_directory ( ) {
	return THEME_FOLDER ;
}
add_shortcode ( 'bzmndsgn_get_theme_directory', 'bzmndsgn_get_theme_directory' ) ;

/**
 * Helper function that returns image subdirectory.
 *
 * @return string
 */
function bzmndsgn_get_theme_image_directory ( ) {
	return IMAGE_SUBFOLDER ;
}
add_shortcode ( 'bzmndsgn_get_theme_image_directory', 'bzmndsgn_get_theme_image_directory' ) ;

/**
 * Log 404 errors to a file. Works in conjunction with 404.php of the theme.
 */
function bzmndsgn_log_404_error ( ) {

	$date = date ( 'c' ) ;

	$not_found_url = $_SERVER['REQUEST_URI'] ;

	$user_agent = $_SERVER['HTTP_USER_AGENT'] ;

	$client_ip_address = $_SERVER['REMOTE_ADDR'] ;

	$referrer = isset ( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '' ;

	$not_found_details = sprintf ('%1$s - %4$s - %3$s - %2$s - %5$s'."\n", $date, $not_found_url, $user_agent , $client_ip_address, $referrer ) ;

	$not_found_path = sprintf ( '%1$s/%2$s', DOCUMENT_ROOT, NOT_FOUND_404_LOG_FILE ) ;

	file_put_contents ( $not_found_path, $not_found_details, FILE_APPEND | LOCK_EX ) ;

}
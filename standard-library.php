<?php
/**
 * @package Baizman Design Standard Library
 * @version 0.1
 */
/*
 * TODO
 *
 * + after commit, regenerate documentation
 *
 */

defined ( 'ABSPATH' ) or die ( 'This file cannot be run outside of WordPress.' ) ;

define ( 'BZMNDSGN_DEBUG', false ) ;

define ( 'BZMNDSGN_DEBUG_LOG', 'debug.log' ) ;

define ( 'BZMNDSGN_DOCUMENT_ROOT_URI', trailingslashit ( $_SERVER['DOCUMENT_ROOT'] ) ) ;

// https://developer.wordpress.org/reference/functions/home_url/
// https://developer.wordpress.org/reference/functions/get_home_url/
define ( 'BZMNDSGN_DOCUMENT_ROOT_URL', trailingslashit ( home_url ( ) ) ) ;

// Note: this only retrieves the *child* theme path, not the parent theme.
// https://developer.wordpress.org/reference/functions/get_stylesheet_directory_uri/
define ( 'BZMNDSGN_THEME_FOLDER_URI', trailingslashit ( get_stylesheet_directory ( ) ) ) ;

define ( 'BZMNDSGN_THEME_FOLDER_URL', trailingslashit ( get_stylesheet_directory_uri ( ) ) ) ;

define ( 'BZMNDSGN_PLUGIN_FOLDER_URI', trailingslashit ( dirname ( dirname (__FILE__) ) ) ) ;

define ( 'BZMNDSGN_PLUGIN_FOLDER_URL', trailingslashit ( dirname ( plugin_dir_url ( __FILE__ ) ) ) ) ;

define ( 'BZMNDSGN_LIBRARY_FOLDER_URI', trailingslashit ( plugin_dir_path ( __FILE__ ) ) ) ;

define ( 'BZMNDSGN_LIBRARY_FOLDER_URL', trailingslashit ( plugin_dir_url ( __FILE__ ) ) ) ;

define ( 'BZMNDSGN_AUTHOR_NAME', 'Saul Baizman' ) ;

define ( 'BZMNDSGN_AUTHOR_EMAIL', 'saul@baizmandesign.com' ) ;

define ( 'BZMNDSGN_SUPPORT_EMAIL', 'support@baizmandesign.com' ) ;

define ( 'BZMNDSGN_AUTHOR_PHONE', '1-617-863-7165' ) ;

define ( 'BZMNDSGN_AUTHOR_COMPANY', 'Baizman Design' ) ;

define ( 'BZMNDSGN_AUTHOR_COMPANY_URL', 'https://baizmandesign.com' ) ;

define ( 'BZMNDSGN_CONFIG_OPTIONS', 'bzmndsgn_config_options' ) ;

define ( 'BZMNDSGN_IS_MULTISITE' , is_multisite ( ) ) ;

define ( 'BZMNDSGN_SHOW_DASHBOARD_WIDGET', true ) ;

// Multisite constants.
if ( BZMNDSGN_IS_MULTISITE ) {
    define ( 'BZMNDSGN_MULTISITE_CONFIG_OPTIONS', 'bzmndsgn_multisite_config_options' ) ;

	$network = get_network() ;
	define ( 'BZMNDSGN_MULTISITE_NETWORK_NAME', $network->site_name ) ;
}

// Load per-site plugin settings.
$bzmndsgn_config_options = get_option ( BZMNDSGN_CONFIG_OPTIONS ) ;

define ( 'BZMNDSGN_NOT_FOUND_404_LOG_FILE', sprintf ('%s-%s',$bzmndsgn_config_options['log_file_prefix'], '404.log' ) ) ;

if ( BZMNDSGN_DEBUG ) {
    $defined_constants = get_defined_constants (true) ;
	$user_defined_constants = print_r ( $defined_constants['user'], true ) ;

    echo ( '<!-- ' ) ;
	// Note: sprintf() won't print the contents of this variable.
    echo $user_defined_constants  ;
    echo ( ' -->' ) ;
	_bzmndsgn_debug ( $user_defined_constants ) ;
}

/**
 * Include admin interface if we are viewing the backend.
 */
if ( is_admin ( ) ) {
	require_once ( BZMNDSGN_LIBRARY_FOLDER_URI . 'admin.php' ) ;
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
	add_editor_style ( BZMNDSGN_LIBRARY_FOLDER_URL . 'css/editor-styles.css' ) ;
}
add_action ( 'init', 'bzmndsgn_custom_editor_styles' ) ;

/**
 * Add CSS and JS for admin interface.
 */
function admin_stylesheet() {
	wp_enqueue_style ('bzmndsgn-admin-styles', BZMNDSGN_LIBRARY_FOLDER_URL . 'css/admin-style.css');
	wp_enqueue_script ( 'bzmndsgn-admin-scripts', BZMNDSGN_LIBRARY_FOLDER_URL . 'js/admin-scripts.js' ) ;

}
add_action('admin_enqueue_scripts', 'admin_stylesheet');

/**
 * Add custom styles on WP login screen.
 */
function bzmndsgn_login_stylesheet ( ) {
	wp_enqueue_style ( 'custom-login', BZMNDSGN_LIBRARY_FOLDER_URL . 'css/login-styles.css' ) ;
	// wp_enqueue_style ( 'typography-styles', BZMNDSGN_LIBRARY_FOLDER_URL . 'css/fonts.css' ) ;
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
	    $website_name = get_bloginfo('name') ;
		if ( BZMNDSGN_IS_MULTISITE ) {
			$h2_text = BZMNDSGN_MULTISITE_NETWORK_NAME ;
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
add_filter( 'login_message', 'bzmndsgn_login_screen_message' ) ;

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

if ( BZMNDSGN_SHOW_DASHBOARD_WIDGET ) {
    /**
     * This function outputs the content of the admin dashboard widget.
     */
    function bzmndsgn_admin_dashboard_widget( ) {

        printf( '<p>This website (<a href="%3$s">%4$s</a>) has a contract with <a href="%2$s" target="_blank">%1$s</a> for monthly support and maintenance.</p>',BZMNDSGN_AUTHOR_COMPANY, BZMNDSGN_AUTHOR_COMPANY_URL, home_url(), parse_url(home_url(),PHP_URL_HOST)) ;
        printf( '
<p>The contract includes up to two hours per month for any of the following activities:</p>
<ul>
<li>+ technical support via <a href="mailto:%1$s">email</a>, <a href="tel:%2$s">phone</a>, <a href="%3$s">videoconference</a>, or in-person consultation.</li> 
<li>+ WordPress training for new and existing users.</li>
<li>+ custom website development (e.g., a new feature).</li>
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

    /**
     * Add the widget to the admin dashboard.
     */
    function bzmndsgn_add_admin_dashboard_widget( ) {
        wp_add_dashboard_widget( 'bzmndsgn_admin_dashboard_widget',
            'Website Support and Maintenance',
            'bzmndsgn_admin_dashboard_widget' );
    }
}

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
	return BZMNDSGN_THEME_FOLDER_URI ;
}
add_shortcode ( 'bzmndsgn_get_theme_directory', 'bzmndsgn_get_theme_directory' ) ;

/**
 * Helper function that returns image subdirectory.
 *
 * @return string
 */
//function bzmndsgn_get_theme_image_directory ( ) {
//	return BZMNDSGN_IMAGE_SUBFOLDER_URI ;
//}
//add_shortcode ( 'bzmndsgn_get_theme_image_directory', 'bzmndsgn_get_theme_image_directory' ) ;

/**
 * Log 404 errors to a file. Works in conjunction with 404.php of the theme.
 */
function _bzmndsgn_log_404_error ( ) {

	$date = date ( 'c' ) ;

	$not_found_url = $_SERVER['REQUEST_URI'] ;

	$user_agent = $_SERVER['HTTP_USER_AGENT'] ;

	$client_ip_address = $_SERVER['REMOTE_ADDR'] ;

	$referrer = isset ( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '' ;

	$not_found_details = sprintf ('%1$s - %4$s - %3$s - %2$s - %5$s'."\n", $date, $not_found_url, $user_agent , $client_ip_address, $referrer ) ;

	$not_found_path = sprintf ( '%1$s/%2$s', BZMNDSGN_DOCUMENT_ROOT_URI, BZMNDSGN_NOT_FOUND_404_LOG_FILE ) ;

	file_put_contents ( $not_found_path, $not_found_details, FILE_APPEND | LOCK_EX ) ;

}

/**
 * Write debugging information to debug log.
 * @param $data
 */
function _bzmndsgn_debug ( $data ) {
    if ( BZMNDSGN_DEBUG ) {
        $timestamp = date('Y.m.d H.i.s');
        $log_message = sprintf ( '%1$s: %2$s', $timestamp, $data ) . "\n" ;
	    file_put_contents (BZMNDSGN_DEBUG_LOG, $log_message, FILE_APPEND | LOCK_EX ) ;
    }
}
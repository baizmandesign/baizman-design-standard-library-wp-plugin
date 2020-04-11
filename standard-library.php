<?php

define ( 'DEBUG', false ) ;

define ( 'DEBUG_LOG', 'debug.log' ) ;

define ( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] ) ;

define ( 'THEME_FOLDER', trailingslashit ( get_stylesheet_directory_uri ( ) ) ) ;

define ( 'PLUGIN_FOLDER', trailingslashit ( plugin_dir_url ( __FILE__ ) ) ) ;

define ( 'AUTHOR_NAME', 'Saul Baizman' ) ;

define ( 'AUTHOR_EMAIL', 'saul@baizmandesign.com' ) ;

define ( 'AUTHOR_COMPANY', 'Baizman Design' ) ;

define ( 'AUTHOR_COMPANY_URL', 'https://baizmandesign.com' ) ;

define ( 'BZMNDSGN_CONFIG_OPTIONS', 'bzmndsgn_config_options' ) ;

// Load per-site plugin settings.
$bzmndsgn_config_options = get_option ( BZMNDSGN_CONFIG_OPTIONS ) ;

define ( 'NOT_FOUND_404_LOG_FILE', sprintf ('%s-%s',$bzmndsgn_config_options['log_file_prefix'], '404.log' ) ) ;

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
	add_editor_style ( PLUGIN_FOLDER . 'css/editor-styles.css' ) ;
}
add_action ( 'init', 'bzmndsgn_custom_editor_styles' ) ;

/**
 * Add CSS and JS for admin interface.
 */
function admin_stylesheet() {
	wp_enqueue_style ('bzmndsgn-admin-styles', PLUGIN_FOLDER . 'css/admin-style.css');
	wp_enqueue_script ( 'bzmndsgn-admin-scripts', PLUGIN_FOLDER . 'js/admin-scripts.js' ) ;

}
add_action('admin_enqueue_scripts', 'admin_stylesheet');

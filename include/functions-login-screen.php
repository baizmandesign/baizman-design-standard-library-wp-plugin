<?php

namespace baizman_design ;

if ( ! function_exists ( 'bzmndsgn_login_stylesheet' ) ):
	/**
	 * Add custom styles on WP login screen.
	 */
	function bzmndsgn_login_stylesheet ( ) {
		wp_enqueue_style ( 'custom-login', BZMNDSGN_PLUGIN_FOLDER_URL . 'css/login-styles.css' ) ;
		// wp_enqueue_style ( 'typography-styles', BZMNDSGN_LIBRARY_FOLDER_URL . 'css/fonts.css' ) ;
	}
	add_action ( 'login_enqueue_scripts', __NAMESPACE__.'\bzmndsgn_login_stylesheet' ) ;
endif;

if ( ! function_exists ( 'bzmndsgn_login_screen_message' ) ):
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
	add_filter( 'login_message', __NAMESPACE__.'\bzmndsgn_login_screen_message' ) ;
endif;

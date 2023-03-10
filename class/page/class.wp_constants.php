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
use baizman_design\email_input;
use baizman_design\form;
use baizman_design\preferences;
use baizman_design\text_area;
use baizman_design\text_input;
use baizman_design\utility;

class wp_constants extends page {

	public function __construct( $plugin, $page_title, $page_slug ) {
		parent::__construct( $plugin, $page_title, $page_slug );
	}

	/**
	 * @return mixed
	 */
	public function render_page() {
		utility::print_admin_settings_heading ('WP Constants', 'Baizman Design Standard Library' ) ;

		$constants = [] ;
		$constants[] = 'WP_DEBUG';
		$constants[] = 'WP_DEBUG_LOG';
		$constants[] = 'WP_DEBUG_DISPLAY';
		$constants[] = 'JETPACK_STAGING_MODE';
		$constants[] = 'SAVEQUERIES';
		$constants[] = 'DISALLOW_FILE_EDIT';
		$constants[] = 'OTGS_INSTALLER_SITE_KEY_TOOLSET';
		$constants[] = 'WP_POST_REVISIONS';
		$constants[] = 'SCRIPT_DEBUG';
		$constants[] = 'WP_LOCAL_DEV';
		$constants[] = 'DISABLE_WP_CRON';
		$constants[] = 'AUTOMATIC_UPDATER_DISABLED';
		$constants[] = 'WP_AUTO_UPDATE_CORE';
		$constants[] = 'WP_ENVIRONMENT_TYPE';
		$constants[] = 'IMAGE_EDIT_OVERWRITE';
		$constants[] = 'WP_CACHE';
		$constants[] = 'WP_ALLOW_MULTISITE';
		$constants[] = 'WP_MEMORY_LIMIT';
		$constants[] = 'WP_DISABLE_FATAL_ERROR_HANDLER';
		$constants[] = 'WP_MAX_MEMORY_LIMIT';
		$constants[] = 'EMPTY_TRASH_DAYS';
		$constants[] = 'CONCATENATE_SCRIPTS';
		$constants[] = 'ALLOW_UNFILTERED_UPLOADS';

		$user_defined_constants = get_defined_constants(true) ;
		sort ($constants) ;
		foreach ( $constants as $constant ) {
			$value = '&mdash;' ;
			$value_class = 'undefined' ;
			if ( defined ($constant) ) {
				$value = $user_defined_constants['user'][$constant] ;
				$value = $value === true ? 'true' : $value ;
				$value = $value === false ? 'false' : $value ;
				if ( $value == 'true' || $value == 'false' ) {
					$value_class = $value ;
				}
				else {
					$value_class = 'string' ;
				}
			}
			printf ('<div class="constants">') ;
			printf ('<p><span class="constant_name">%1$s</span></p> <p><span class="value_%3$s">%2$s</span></p>',$constant, $value, $value_class ) ;
			printf ('</div>') ;
		}

		utility::print_admin_settings_footer() ;

	}

	/**
	 * @return mixed
	 */
	public function register_settings() {
		// TODO: Implement register_settings() method.
	}

}
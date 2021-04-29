<?php
/**
 * WP constants.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design ;

function bzmndsgn_wp_constants ( ) {
	_print_admin_settings_heading ('WP Constants', 'Baizman Design Standard Library' ) ;

	$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

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

	$user_defined_constants = get_defined_constants(true) ;
	sort ($constants) ;
	foreach ( $constants as $constant ) {
		$value = '&ndash;' ;
		if ( defined ($constant) ) {
			$value = $user_defined_constants['user'][$constant] ;
		}
			printf ('<p><span class="constant_name">%1$s: %2$s</span></p>',$constant, $value) ;
	}

	_print_admin_settings_footer() ;

}
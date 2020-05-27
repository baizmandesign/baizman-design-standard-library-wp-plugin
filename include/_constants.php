<?php

// Move all constants here?
// Two issues to resolve:
// + this file should always be called first.. because the order matters, doesn't it?
// --> renaming this file and giving it an underscore might address this.
// + some of the constants derive the current folder from __FILE__, which will make folder references incorrect since we'll need to refer to the parent folder.

// Include local configuration, if present.
$local_config_path = sprintf ('%s%s%s', dirname ( __FILE__ ), DIRECTORY_SEPARATOR, 'local-config.php' ) ;

if ( file_exists ( $local_config_path ) ) {
	include_once ( $local_config_path ) ;
}

// Wrap constants in if statement to allow local over-rides.
if ( ! defined ( 'BZMNDSGN_STAGING_BACKGROUND_COLOR' ) ) {
	define ( 'BZMNDSGN_STAGING_BACKGROUND_COLOR', 'rgba(255,96,188,.1)' ) ;
}

if ( ! defined ( 'BZMNDSGN_DEV_BACKGROUND_COLOR' ) ) {
	define ( 'BZMNDSGN_DEV_BACKGROUND_COLOR', 'rgba(125,69,16,.1)' ) ;
}


if ( ! defined ( 'BZMNDSGN_LOCAL_BACKGROUND_COLOR' ) ) {
	define ( 'BZMNDSGN_LOCAL_BACKGROUND_COLOR', 'rgba(23,165,188,.1)' ) ;
}

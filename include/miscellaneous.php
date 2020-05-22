<?php
/**
 * Miscellaneous functions.
 */

/**
 * When a post is saved, remove any empty lines at the beginning and end of the content (body).
 * @param $content
 *
 * @return mixed
 */
function bzmndsgn_remove_bookend_blank_lines ( $content ) {
	$content = preg_replace ( "/^&nbsp;\s*/m", '', $content) ;
	return trim ( $content) ;
}
add_filter( 'content_save_pre', 'bzmndsgn_remove_bookend_blank_lines', 10, 1 );

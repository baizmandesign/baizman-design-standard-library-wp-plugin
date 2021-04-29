<?php
/**
 * Miscellaneous functions.
 * TODO: add filter to autolink URLs in the_content?
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design ;

$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

if ( ! function_exists ( 'bzmndsgn_remove_content_blank_lines' ) ):
	/**
	 * When a post is saved, remove any empty lines from the_content.
	 * @param $content
	 *
	 * @return mixed
	 */
	function bzmndsgn_remove_content_blank_lines ( $content ) {
		$content = preg_replace ( "/^&nbsp;\s*/m", '', $content) ;
		return trim ( $content) ;
	}
	if ( _is_enabled ( 'checkbox-strip_content_blank_lines_on_save', $bzmndsgn_config_options_database ) ) {
		add_filter( 'content_save_pre', __NAMESPACE__.'\bzmndsgn_remove_content_blank_lines', 10, 1 );
	}
endif;

/**
 * Remove blank links when the_content is output. (In case we're on a website that doesn't have the bzmndsgn_remove_content_blank_lines filter enabled.)
 * @param $content
 *
 * @return string
 *
 * @link https://developer.wordpress.org/reference/hooks/the_content/
 */
if ( ! function_exists ( 'bzmndsgn_filter_content_blank_lines' ) ):
	function bzmndsgn_filter_content_blank_lines( $content ) {

		// Check if we're inside the main loop in a single post page.
		if ( is_single() && in_the_loop() && is_main_query() ) {
			if ( get_post_type() == 'course' ) {
				$content = preg_replace ( "/^&nbsp;\s*/m", '', $content) ;
				return trim ( $content) ;
			}
		}

		return $content;
	}
	if ( _is_enabled ( 'checkbox-strip_content_blank_lines_on_display', $bzmndsgn_config_options_database ) ) {
		add_filter( 'the_content', __NAMESPACE__.'\bzmndsgn_filter_content_blank_lines' );
	}
endif;

if ( ! function_exists ( 'bzmndsgn_strip_illegal_tags' ) ):
	/**
	 * When a post is saved, remove all tags but the legal tags in the array.
	 * @param $content
	 *
	 * @return string
	 */
	function bzmndsgn_strip_illegal_tags ( $content ) {

		// FIXME: limit to one or more post types?
		// These tags will not be stripped out.
		$legal_tags = explode ("\r\n",$GLOBALS[BZMNDSGN_CONFIG_OPTIONS]['textarea-legal_tags'] ) ;

		$legal_tags = array_map (
			function ( $tag ) {
				// Add angle brackets to HTML tag.
				return sprintf('<%s>',$tag) ;
			},
			$legal_tags
		) ;
		return strip_tags( $content, implode( '', $legal_tags ) );
	}
	if ( _is_enabled ( 'checkbox-strip_illegal_tags_on_save', $bzmndsgn_config_options_database ) ) {
		add_filter( 'content_save_pre', __NAMESPACE__.'\bzmndsgn_strip_illegal_tags', 10, 1 );
	}
endif;

if ( ! function_exists ( 'bzmndsgn_filter_content_double_spaces' ) ):
	/**
	 * Condense double-spaces into a single space when the_content is output.
	 * @param $content
	 *
	 * @return string
	 * @link https://stackoverflow.com/questions/16563421/cant-get-str-replace-to-strip-out-spaces-in-a-php-string
	 */
	function bzmndsgn_filter_content_double_spaces( $content ) {

		// Check if we're inside the main loop in a single post page.
		if ( is_single() && in_the_loop() && is_main_query() ) {
			if ( get_post_type() == 'section' ) {

				return preg_replace('/\s+/u', ' ', $content ) ;
			}
		}

		return $content;
	}
	if ( _is_enabled ( 'checkbox-strip_double_spaces_on_display', $bzmndsgn_config_options_database ) ) {
		add_filter( 'the_content', __NAMESPACE__.'\bzmndsgn_filter_content_double_spaces' );
	}
endif;

if ( ! function_exists ( 'bzmndsgn_strip_double_spaces' ) ):
	/**
	 * Condense double-spaces into a single space when the post is saved.
	 * FIXME: this may need more testing before it is rolled out to clients' websites.
	 * @param $content
	 *
	 * @return string
	 * @link https://stackoverflow.com/questions/16563421/cant-get-str-replace-to-strip-out-spaces-in-a-php-string
	 */
	function bzmndsgn_strip_double_spaces( $content ) {

		return preg_replace('/\s+/u', ' ', $content ) ;

	}
	if ( _is_enabled ( 'checkbox-strip_double_spaces_on_save', $bzmndsgn_config_options_database ) ) {
		add_filter( 'content_save_pre', __NAMESPACE__.'\bzmndsgn_strip_double_spaces', 10, 1 );
	}
endif;


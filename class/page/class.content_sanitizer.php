<?php
/**
 * Page class for a general settings page.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design\page ;

use baizman_design\bdsl;
use baizman_design\checkbox;
use baizman_design\form;
use baizman_design\preferences;
use baizman_design\text_area;
use baizman_design\utility;

class content_sanitizer extends page {

	public function __construct( $plugin, $page_title, $page_slug ) {
		parent::__construct( $plugin, $page_title, $page_slug );

		if ( utility::is_enabled( 'checkbox-strip_content_blank_lines_on_save' ) ) {
			add_filter( 'content_save_pre', [ __CLASS__, 'remove_content_blank_lines' ], 10, 1 );
		}
		if ( utility::is_enabled( 'checkbox-strip_content_blank_lines_on_display' ) ) {
			add_filter( 'the_content', [ __CLASS__, 'filter_content_blank_lines' ] );
		}
		if ( utility::is_enabled( 'checkbox-strip_illegal_tags_on_save' ) ) {
			add_filter( 'content_save_pre', [ __CLASS__, 'strip_illegal_tags' ], 10, 1 );
		}
		if ( utility::is_enabled( 'checkbox-strip_double_spaces_on_display' ) ) {
			add_filter( 'the_content', [ __CLASS__, 'filter_content_double_spaces' ] );
		}
		if ( utility::is_enabled( 'checkbox-strip_double_spaces_on_save' ) ) {
			add_filter( 'content_save_pre', [ __CLASS__, 'strip_double_spaces' ], 10, 1 );
		}
	}

	/**
	 * @return mixed
	 */
	public function render_page() {
		utility::print_admin_settings_heading ('Content Sanitizers', 'Baizman Design Standard Library' ) ;

		$content_sanitizers_settings_form = new form ( 'content_sanitizers_settings' ) ;

		$strip_content_blank_lines_on_save = new checkbox( 'Strip blank lines from the body of posts when <u>saving</u> them?','checkbox-strip_content_blank_lines_on_save',preferences::get_database_option('checkbox-strip_content_blank_lines_on_save')) ;
		$content_sanitizers_settings_form->add_form_field ($strip_content_blank_lines_on_save) ;

		$strip_content_blank_lines_on_display = new checkbox( 'Strip blank lines from the body of posts when <u>displaying</u> them?','checkbox-strip_content_blank_lines_on_display',preferences::get_database_option('checkbox-strip_content_blank_lines_on_display')) ;
		$content_sanitizers_settings_form->add_form_field ($strip_content_blank_lines_on_display) ;

		$strip_double_spaces_on_save = new checkbox( 'Condense double-spaces into a single space when <u>saving</u> a post?','checkbox-strip_double_spaces_on_save',preferences::get_database_option('checkbox-strip_double_spaces_on_save')) ;
		$content_sanitizers_settings_form->add_form_field ($strip_double_spaces_on_save) ;

		$strip_double_spaces_on_display = new checkbox( 'Condense double-spaces into a single space when <u>displaying</u> a post?','checkbox-strip_double_spaces_on_display',preferences::get_database_option('checkbox-strip_double_spaces_on_display')) ;
		$content_sanitizers_settings_form->add_form_field ($strip_double_spaces_on_display) ;

		$strip_illegal_tags_on_save = new checkbox(
			'Strip illegal tags when saving a post?',
			'checkbox-strip_illegal_tags_on_save',
			preferences::get_database_option('checkbox-strip_illegal_tags_on_save')
		) ;
		$strip_illegal_tags_on_save->set_field_help_text('Specify the tags to <u>keep</u> in the field below.');
		$content_sanitizers_settings_form->add_form_field ($strip_illegal_tags_on_save) ;

		$legal_tags = new text_area (
			'Legal tags to keep when saving a post:',
			'textarea-legal_tags',
			'span',
			preferences::get_database_option('textarea-legal_tags')) ;
		$legal_tags->set_field_help_text('Enter one tag per line, no angle brackets (&lt;&gt;) necessary.');
		$legal_tags->set_show_label( false ) ;
		$legal_tags->remove_duplicates() ;
		$legal_tags->sort() ;
		$content_sanitizers_settings_form->add_form_field ($legal_tags) ;
		$content_sanitizers_settings_form->render_form();

		utility::print_admin_settings_footer() ;
	}

	/**
	 * When a post is saved, remove any empty lines from the_content.
	 * @param $content
	 *
	 * @return mixed
	 */
	public function remove_content_blank_lines ( $content ) {
		$content = preg_replace ( "/^&nbsp;\s*/m", '', $content) ;
		return trim ( $content) ;
	}

	/**
	 * Remove blank links when the_content is output. (In case we're on a website that doesn't have the bzmndsgn_remove_content_blank_lines filter enabled.)
	 * @param $content
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/the_content/
	 */
	public function filter_content_blank_lines( $content ) {

		// Check if we're inside the main loop in a single post page.
		if ( is_single() && in_the_loop() && is_main_query() ) {
			if ( get_post_type() == 'course' ) {
				$content = preg_replace ( "/^&nbsp;\s*/m", '', $content) ;
				return trim ( $content) ;
			}
		}

		return $content;
	}

	/**
	 * When a post is saved, remove all tags but the legal tags in the array.
	 * @param $content
	 *
	 * @return string
	 */
	public function strip_illegal_tags ( $content ) {

		// FIXME: limit to one or more post types?
		// These tags will not be stripped out.
		$legal_tags = explode ("\r\n",preferences::get_database_option('textarea-legal_tags') ) ;

		$legal_tags = array_map (
			function ( $tag ) {
				// Add angle brackets to HTML tag.
				return sprintf('<%s>',$tag) ;
			},
			$legal_tags
		) ;
		return strip_tags( $content, implode( '', $legal_tags ) );
	}

	/**
	 * Condense double-spaces into a single space when the_content is output.
	 * @param $content
	 *
	 * @return string
	 * @link https://stackoverflow.com/questions/16563421/cant-get-str-replace-to-strip-out-spaces-in-a-php-string
	 */
	public function filter_content_double_spaces( $content ) {

		// Check if we're inside the main loop in a single post page.
		if ( is_single() && in_the_loop() && is_main_query() ) {
			if ( get_post_type() == 'section' ) {

				return preg_replace('/\s+/u', ' ', $content ) ;
			}
		}

		return $content;
	}
	
	/**
	 * Condense double-spaces into a single space when the post is saved.
	 * FIXME: this may need more testing before it is rolled out to clients' websites.
	 * @param $content
	 *
	 * @return string
	 * @link https://stackoverflow.com/questions/16563421/cant-get-str-replace-to-strip-out-spaces-in-a-php-string
	 */
	public function strip_double_spaces( $content ) {

		return preg_replace('/\s+/u', ' ', $content ) ;

	}
}
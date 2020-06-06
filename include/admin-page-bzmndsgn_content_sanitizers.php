<?php
/**
 * Content sanitizers.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

function bzmndsgn_content_sanitizers () {
	_print_admin_settings_heading ('Content Sanitizer Settings') ;

	$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );
	$content_sanitizers_settings_form = new form ( 'content_sanitizers_settings' ) ;
	$content_sanitizers_settings_form->add_table_class('content-sanitizers-settings-table') ;

	$strip_content_blank_lines_on_save = new checkbox( 'Strip blank lines from the body of WordPress posts when <u>saving</u> them?','checkbox-strip_content_blank_lines_on_save',$bzmndsgn_config_options_database['checkbox-strip_content_blank_lines_on_save']) ;
	$content_sanitizers_settings_form->add_form_field ($strip_content_blank_lines_on_save) ;

	$strip_content_blank_lines_on_display = new checkbox( 'Strip blank lines from the body of WordPress posts when <u>displaying</u> them?','checkbox-strip_content_blank_lines_on_display',$bzmndsgn_config_options_database['checkbox-strip_content_blank_lines_on_display']) ;
	$content_sanitizers_settings_form->add_form_field ($strip_content_blank_lines_on_display) ;

	$strip_double_spaces_on_save = new checkbox( 'Condense double-spaces into a single space when <u>saving</u> a post?','checkbox-strip_double_spaces_on_save',$bzmndsgn_config_options_database['checkbox-strip_double_spaces_on_save']) ;
	$content_sanitizers_settings_form->add_form_field ($strip_double_spaces_on_save) ;

	$strip_double_spaces_on_display = new checkbox( 'Condense double-spaces into a single space when <u>displaying</u> a post?','checkbox-strip_double_spaces_on_display',$bzmndsgn_config_options_database['checkbox-strip_double_spaces_on_display']) ;
	$content_sanitizers_settings_form->add_form_field ($strip_double_spaces_on_display) ;

	$strip_illegal_tags_on_save = new checkbox(
		'Strip illegal tags when saving a post?',
		'checkbox-strip_illegal_tags_on_save',
		$bzmndsgn_config_options_database['checkbox-strip_illegal_tags_on_save']
	) ;
	$strip_illegal_tags_on_save->set_field_help_text('Specify the tags to strip in the field below.');
	$content_sanitizers_settings_form->add_form_field ($strip_illegal_tags_on_save) ;

	$illegal_tags = new text_area(
		'Illegal tags to strip when saving a post:',
		'textarea-illegal_tags',
		'span',
		$bzmndsgn_config_options_database['textarea-illegal_tags']) ;
	$illegal_tags->set_field_help_text('Enter one tag per line, no angle brackets (&lt;&gt;) necessary.');
	$illegal_tags->set_show_label( false ) ;
	$content_sanitizers_settings_form->add_form_field ($illegal_tags) ;

	// Output form.
	$content_sanitizers_settings_form->render_form();

}
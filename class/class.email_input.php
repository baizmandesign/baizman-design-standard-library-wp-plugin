<?php
/**
 * Class email_input.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design ;

class email_input extends text_input {

	public function __construct( $text_field_label, $text_field_input_name, $text_field_placeholder, $text_field_default_value ) {
		parent::__construct( $text_field_label, $text_field_input_name, $text_field_placeholder, $text_field_default_value );
		$this->set_field_type( 'email' );
	}

	/**
	 * @return mixed|void
	 */
	public function print_form_field ( ) {

		printf ('<tr valign="top">') ;
		printf ('<th scope="row">');
		if ( $this->get_show_label() ) {
			printf(
				'%1$s',
				$this->get_field_label()
			);
		}
		if ( $this->get_label_help_text() ) {
			printf(
				'<br><small>%1$s</small>',
				$this->get_label_help_text()
			);
		}
		printf ('</th>' );
		printf (
			'<td><input type="email" name="%1$s" id="%4$s" placeholder="%2$s" value="%3$s" size="50">',
			esc_attr ( $this->get_field_input_name() ),
			esc_attr ( $this->get_text_field_placeholder() ),
			esc_attr ( $this->get_field_default_value() ),
			$this->get_field_id()
		);
		if ( $this->get_field_help_text() ) {
			printf (
				'<br><small>%1$s</small>',
				$this->get_field_help_text()
			) ;
		}
		printf (
			'</td></tr>'
		) ;
	}

}
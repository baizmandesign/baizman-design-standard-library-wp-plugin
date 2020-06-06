<?php
/**
 * Class checkbox.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

class checkbox extends field {

	public function __construct( $checkbox_label, $checkbox_input_name, $checkbox_default_value ) {
		parent::__construct( $checkbox_label, $checkbox_input_name );
		$this->set_field_type( 'checkbox' );
		$this->set_field_default_value ( $checkbox_default_value ) ;
	}

	public function print_form_field ( ) {
		printf ('<tr valign="top">') ;
		printf (
			'<th scope="row"><label for="%1$s">%2$s</label>',
			esc_attr ( $this->get_field_input_name( ) ),
			$this->get_field_label( )
		) ;
		if ( $this->get_label_help_text() ) {
			printf(
				'<br><small>%1$s</small>',
				$this->get_label_help_text()
			);
		}
		printf('</th>' );

		printf (
			'<td><input type="checkbox" name="%2$s" id="%3$s" value="1"%1$s/>',
			checked ( '1', $this->get_field_default_value( ), false ),
			esc_attr ( $this->get_field_input_name( ) ),
			$this->get_field_id()
		) ;
		if ( $this->get_field_help_text() ) {
			printf(
				'<br><small>%1$s</small>',
				$this->get_field_help_text()
			);
		}

		printf('</td></tr>');
	}
}
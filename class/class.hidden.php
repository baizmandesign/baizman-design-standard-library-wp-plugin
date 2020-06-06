<?php
/**
 * Hidden field class.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

class hidden extends field {

	/**
	 * hidden field constructor.
	 *
	 * @param $hidden_field_input_name
	 * @param $hidden_field_default_value
	 */
	public function __construct( $hidden_field_input_name, $hidden_field_default_value ) {
		parent::__construct( '', $hidden_field_input_name );
		$this->set_field_default_value ( $hidden_field_default_value ) ;
		$this->set_field_type( 'hidden' );
	}

	/**
	 * Print hidden field.
	 * @return mixed|void
	 */
	public function print_form_field() {
		printf (
			'<input type="%1$s" name="%2$s" id="%4$s" value="%3$s">',
			$this->get_field_type(),
			esc_attr ( $this->get_field_input_name() ),
			esc_attr ( $this->get_field_default_value() ),
			$this->get_field_id()
		) ;
	}
}
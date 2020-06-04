<?php
/**
 * Field class for a generic form field.
 * Note: this file begins with an underscore because the order it is loaded matters.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

abstract class field
{
	protected $field_input_name ;
	protected $field_label ;
	protected $field_default_value ;
	protected $field_value ;
	protected $field_type ;
	protected $help_text ;

	/**
	 * field constructor.
	 */
	public function __construct ( $field_label, $field_input_name ) {

		$this->set_field_label ( $field_label ) ;
		$this->set_field_input_name ( $field_input_name ) ;

	}

	/**
	 * @param $field_type
	 */
	public function set_field_type ( $field_type ) {
		$this->field_type = $field_type ;
	}

	/**
	 * @return mixed
	 */
	public function get_field_type ( ) {
		return $this->field_type ;
	}

	/**
	 * @return mixed
	 */
	public function get_field_label() {
		return $this->field_label;
	}

	/**
	 * @param mixed $field_label
	 */
	public function set_field_label ( $field_label ) {
		$this->field_label = $field_label;
	}

	/**
	 * @param $field_input_name
	 */
	public function set_field_input_name ( $field_input_name ) {
		$this->field_input_name = $field_input_name ;
	}

	/**
	 * @return mixed
	 */
	public function get_field_input_name ( ) {
		return $this->field_input_name ;
	}

	/**
	 * @return mixed
	 */
	public function get_field_default_value() {
		return $this->field_default_value;
	}

	/**
	 * @param mixed $field_default_value
	 */
	public function set_field_default_value( $field_default_value ) {
		$this->field_default_value = $field_default_value;
	}

	/**
	 * @param $field_value
	 */
	public function set_field_value ( $field_value ) {
		$this->field_value = $field_value ;
	}

	/**
	 * @return mixed
	 */
	public function get_field_value ( ) {
		return $this->field_value ;
	}

	/**
	 * @param $help_text
	 */
	public function set_help_text ( $help_text ) {
		$this->help_text = $help_text ;
	}

	/**
	 * @return mixed
	 */
	public function get_help_text ( ) {
		return $this->help_text ;
	}

	/**
	 * Child classes need to define this themselves.
	 * @return mixed
	 */
	abstract public function print_form_field ( ) ;

}
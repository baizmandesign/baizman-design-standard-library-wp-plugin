<?php
/**
 * Class button.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

/*
 * TODO: add $other_attributes.
 */

namespace baizman_design ;

class button extends field {

	/**
	 * @var
	 */
	private $button_type ;

	/**
	 * @var
	 */
	private $wrap ;

	/**
	 * @var
	 */
	private $callback_function ;

	/**
	 * button constructor.
	 */
	public function __construct( $button_text, $button_input_name, $button_type, $wrap = false ) {
		parent::__construct( $button_text, $button_input_name );
		$this->set_field_type( 'button' );
		$this->set_button_type($button_type) ;
		$this->set_wrap($wrap) ;
		$this->set_callback_function($button_input_name) ; // this can be over-ridden, if desired.
	}

	/**
	 * @return mixed
	 */
	public function get_button_type() {
		return $this->button_type;
	}

	/**
	 * @param mixed $button_type
	 */
	public function set_button_type( $button_type ) {
		$this->button_type = $button_type;
	}

	/**
	 * @return mixed
	 */
	public function get_callback_function() {
		return $this->callback_function;
	}

	/**
	 * @param mixed $callback_function
	 */
	public function set_callback_function( $callback_function ) {
		$this->callback_function = $callback_function;
	}

	/**
	 * @return mixed
	 */
	public function get_wrap() {
		return $this->wrap;
	}

	/**
	 * @param mixed $wrap
	 */
	public function set_wrap( $wrap ) {
		$this->wrap = $wrap;
	}

	/**
	 * @return mixed|void
	 */
	public function print_form_field ( ) {
		printf ('<input type="hidden" name="action" value="%1$s">', $this->get_callback_function()) ;
		submit_button( $this->get_field_label(), $this->get_button_type(), $this->get_field_input_name(), $this->get_wrap() );
	}

}
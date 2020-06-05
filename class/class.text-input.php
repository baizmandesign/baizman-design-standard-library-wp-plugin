<?php
/**
 * Class text_input.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

class text_input extends field {

	// add data validation? for example, for length?
	/**
	 * @var
	 */
	private $field_placeholder ;

	/**
	 * text_input constructor.
	 *
	 * @param $text_field_label
	 * @param $text_field_input_name
	 * @param $text_field_placeholder
	 * @param $text_field_default_value
	 */
	public function __construct( $text_field_label, $text_field_input_name, $text_field_placeholder, $text_field_default_value ) {
		parent::__construct( $text_field_label, $text_field_input_name );
		$this->set_field_placeholder( $text_field_placeholder ) ;
		$this->set_field_default_value ( $text_field_default_value ) ;
		$this->set_field_type( 'input' );
	}

	/**
	 * @return mixed
	 */
	public function get_text_field_placeholder() {
		return $this->field_placeholder;
	}

	/**
	 * @param mixed $field_placeholder
	 */
	public function set_field_placeholder( $field_placeholder ) {
		$this->field_placeholder = $field_placeholder;
	}

	/**
	 * @return mixed|void
	 */
	public function print_form_field ( ) {

		printf ('<tr valign="top">') ;

		printf (
			'<th scope="row">%1$s:</th>',
			$this->get_field_label( )
		) ;
		printf (
			'<td><input type="text" name="%1$s" placeholder="%2$s" value="%3$s" size="50">',
			esc_attr ( $this->get_field_input_name() ),
			esc_attr ( $this->get_text_field_placeholder() ),
			esc_attr ( $this->get_field_default_value() )
		);
		if ( $this->get_help_text() ) {
			printf (
				'<br><small>%1$s</small>',
				$this->get_help_text()
			) ;
		}
		printf (
			'</td></tr>'
		) ;
	}

}
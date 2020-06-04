<?php
/**
 * Class text_input
 */

class text_input extends field {

	// something for data validation?
	// or length
	private $field_placeholder ;


	/**
	 * text_input constructor.
	 *
	 * @param $field_label
	 * @param $field_input_name
	 * @param $placeholder
	 * @param $default_value
	 */
	public function __construct( $field_label, $field_input_name, $placeholder, $default_value ) {
		parent::__construct( $field_label, $field_input_name );
		$this->set_field_placeholder( $placeholder ) ;
		$this->set_field_default_value ( $default_value ) ;
		$this->set_field_type( 'input' );
	}

	/**
	 * @return mixed
	 */
	public function get_field_placeholder() {
		return $this->field_placeholder;
	}

	/**
	 * @param mixed $field_placeholder
	 */
	public function set_field_placeholder( $field_placeholder ) {
		$this->field_placeholder = $field_placeholder;
	}

	public function print_form_field ( ) {

		printf ('<tr valign="top">') ;

		printf (
			'<th scope="row">%s:</th>',
			$this->get_field_label( )
		) ;
		printf (
			'<td><input type="text" name="%s" placeholder="%s" value="%s" size="50">',
			esc_attr ( $this->get_field_input_name() ),
			esc_attr ( $this->get_field_placeholder() ),
			esc_attr ( $this->get_field_default_value() )
		);
		if ( $this->get_help_text() ) {
			printf (
				'<br><small>%s</small>',
				$this->get_help_text()
			) ;
		}
		printf (
			'</td></tr>'
		) ;
	}

}
<?php
/**
 * Class color
 * @package Baizman Design Standard Library
 * @version 0.1
 */

class color extends field {

	/**
	 * color constructor.
	 *
	 * @param $color_field_label
	 * @param $color_field_input_name
	 */
	public function __construct( $color_field_label, $color_field_input_name, $color_field_default_value ) {
		parent::__construct( $color_field_label, $color_field_input_name );
		$this->set_field_default_value ( $color_field_default_value ) ;
		$this->set_field_type( 'color' );
	}

	/**
	 * @return mixed|void
	 */
	public function print_form_field() {
		printf ('<tr valign="top">') ;

		printf (
			'<th scope="row"><label for="%1$s">%2$s</label>',
			esc_attr ( $this->get_field_input_name() ),
			$this->get_field_label( )
		) ;
		if ( $this->get_label_help_text() ) {
			printf(
				'<br><small>%1$s</small>',
				$this->get_label_help_text()
			);
		}
		printf ('</th>' );
		printf (
			'<td><input type="%1$s" name="%2$s" value="%3$s">',
			$this->get_field_type(),
			esc_attr ( $this->get_field_input_name() ),
			esc_attr ( $this->get_field_default_value() )
		) ;
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
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
            	'<th scope="row"><label for="%1$s">%2$s</label></th>',
	            esc_attr ( $this->get_field_input_name( ) ),
	            $this->get_field_label( )
            ) ;
                printf (
                	'<td><input type="checkbox" name="%2$s" id="%2$s" value="1"%1$s/>',
	                checked ( '1', $this->get_field_default_value( ), false ),
	                esc_attr ( $this->get_field_input_name( ) )
                ) ;
                printf('</td></tr>');
	}
}
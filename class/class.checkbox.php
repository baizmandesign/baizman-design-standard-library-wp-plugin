<?php
/**
 * Class checkbox.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design ;

class checkbox extends field {

	/**
	 * @var
	 */
	public $is_in_checkbox_group ;

	/**
	 * checkbox constructor.
	 *
	 * @param $checkbox_label
	 * @param $checkbox_input_name
	 * @param $checkbox_default_value
	 * @param bool $is_in_checkbox_group
	 */
	public function __construct( $checkbox_label, $checkbox_input_name, $checkbox_default_value, $is_in_checkbox_group = false ) {
		parent::__construct( $checkbox_label, $checkbox_input_name );
		$this->set_field_type( 'checkbox' );
		$this->set_field_default_value ( $checkbox_default_value ) ;
		$this->set_is_in_checkbox_group ( $is_in_checkbox_group ) ;
	}

	/**
	 * @return mixed
	 */
	public function get_is_in_checkbox_group() {
		return $this->is_in_checkbox_group;
	}

	/**
	 * @param mixed $is_in_checkbox_group
	 */
	public function set_is_in_checkbox_group( $is_in_checkbox_group ) {
		$this->is_in_checkbox_group = $is_in_checkbox_group;
	}

	/**
	 * @return mixed|void
	 */
	public function print_form_field ( ) {
		// not in checkbox group. print in table row.
		if ( ! $this->get_is_in_checkbox_group() ) {
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

		// in checkbox group. do not print in table row.
		if ( $this->get_is_in_checkbox_group() ) {
			$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

			$dashboard_links_to_hide = $bzmndsgn_config_options_database['checkboxes-dashboard_links_to_hide'] ;

			// is the field in the array of items in the database?
			// if so, check the box.
			$checked = in_array ( $this->get_field_default_value(), $dashboard_links_to_hide ) ? 'checked' : '' ;

			printf(
				'<input type="checkbox" name="%2$s" id="%3$s" value="%4$s"%1$s/>',
				$checked,
				esc_attr( $this->get_field_input_name() ),
				$this->get_field_id(),
				$this->get_field_default_value()
			);
			printf( ' <label for="%1$s">%2$s</label><br>',
				esc_attr( $this->get_field_input_name() ),
				$this->get_field_label() );
		}
	}
}
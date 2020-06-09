<?php
/**
 * Class checkboxes.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

class checkboxes {

	/**
	 * @var array
	 */
	public $items = [] ;

	/**
	 * @var array
	 */
	public $checkboxes = [] ;

	/**
	 * @var
	 */
	public $checkbox_input_name ;

	/**
	 * @var
	 */
	public $checkbox_label ;

	/**
	 * checkboxes constructor.
	 *
	 * @param $checkbox_label
	 * @param $checkbox_input_name
	 * @param $items
	 */
	public function __construct ( $checkbox_label, $checkbox_input_name, $items ) {
		$this->set_checkbox_label ( $checkbox_label ) ;

		$bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

		$dashboard_links_to_remove = $bzmndsgn_config_options_database['checkboxes-dashboard_links_to_hide'] ;

		// add hidden dashboard links from the database.
		$items += $dashboard_links_to_remove ;

		// sort the links.
		ksort ($items) ;

		foreach ( $items as $name => $url ) {

			$checkbox = new checkbox (
				$name,
				sprintf ( '%1$s[%2$s]', $checkbox_input_name, $name ),
				$url,
				true
			) ;

			$this->add_checkbox( $checkbox ) ;

		}

	}

	/**
	 * @return array
	 */
	public function get_checkboxes( ) {
		return $this->checkboxes;
	}

	/**
	 * @param $checkbox
	 */
	public function add_checkbox( $checkbox ) {
		$this->checkboxes[] = $checkbox;
	}

	/**
	 * @param $checkbox_label
	 */
	public function set_checkbox_label ( $checkbox_label ) {
		$this->checkbox_label = $checkbox_label ;
	}

	/**
	 * @return mixed
	 */
	public function get_checkbox_label ( ) {
		return $this->checkbox_label ;
	}

	/**
	 * @return mixed
	 */
	public function get_checkbox_input_name() {
		return $this->checkbox_input_name;
	}

	/**
	 * @param mixed $checkbox_input_name
	 */
	public function set_checkbox_input_name( $checkbox_input_name ) {
		$this->checkbox_input_name = $checkbox_input_name;
	}

	public function print_form_field ( ) {
		printf ('<tr valign="top">') ;
		printf (
			'<th scope="row">%2$s',
			esc_attr ( $this->get_checkbox_input_name( ) ),
			$this->get_checkbox_label( )
		) ;
		printf ('</th>' );

		printf ('<td>' ) ;
		$checkboxes = $this->get_checkboxes() ;
		foreach ( $checkboxes as $checkbox ) {
			$checkbox->print_form_field() ;
		}
		printf('</td>');
		printf('</tr>');
	}
}
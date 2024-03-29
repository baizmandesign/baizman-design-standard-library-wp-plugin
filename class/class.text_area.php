<?php
/**
 * Text area class.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design ;

class text_area extends field {

	/**
	 * @var
	 */
	private $field_placeholder ;

	/**
	 * This should be either 'left', 'top', or 'bottom'. Default: left.
	 * @var
	 */
	private $label_position ;

	/**
	 * @var
	 */
	private $rows ;

	/**
	 * @var
	 */
	private $columns ;

	/**
	 * text_area constructor.
	 *
	 * @param $text_area_label
	 * @param $text_area_input_name
	 * @param $text_area_placeholder
	 * @param $text_area_default_value
	 */
	public function __construct( $text_area_label, $text_area_input_name, $text_area_placeholder, $text_area_default_value ) {
		parent::__construct ( $text_area_label, $text_area_input_name ) ;
		$this->set_text_area_placeholder( $text_area_placeholder ) ;
		$this->set_field_default_value ( $text_area_default_value ) ;
		$this->set_label_position( 'top' );
		$this->set_rows ( 8 ) ;
		$this->set_columns ( 50 ) ;
		$this->set_field_type( 'textarea' );
	}

	/**
	 * @return mixed
	 */
	public function get_text_area_placeholder() {
		return $this->field_placeholder;
	}

	/**
	 * @param mixed $field_placeholder
	 */
	public function set_text_area_placeholder( $field_placeholder ) {
		$this->field_placeholder = $field_placeholder;
	}

	/**
	 * @return mixed
	 */
	public function get_label_position() {
		return $this->label_position;
	}

	/**
	 * @param mixed $label_position
	 */
	public function set_label_position( $label_position ) {
		$this->label_position = $label_position;
	}

	/**
	 * @return mixed
	 */
	public function get_rows() {
		return $this->rows;
	}

	/**
	 * @param mixed $rows
	 */
	public function set_rows( $rows ) {
		$this->rows = $rows;
	}

	/**
	 * @return mixed
	 */
	public function get_columns() {
		return $this->columns;
	}

	/**
	 * @param mixed $columns
	 */
	public function set_columns( $columns ) {
		$this->columns = $columns;
	}

	/**
	 * Sort values in a textarea. For the legal HTML tags.
	 * TODO: move me somewhere else.
	 */
	public function sort ( ) {
		$values = explode ( "\n", $this->get_field_default_value() ) ;
		sort ($values) ;
		$this->set_field_default_value ( implode ( "\n", $values ) ) ;
	}

	/**
	 * Remove duplicate items in a textarea. For the legal HTML tags.
	 * TODO: move me somewhere else.
	 */
	public function remove_duplicates () {
		$values = explode ( "\r\n", $this->get_field_default_value() ) ;
		$unique_values = [] ;
		foreach ( $values as $value ) {
			if ( ! in_array ( $value, $unique_values ) ) {
				$unique_values[] = $value ;
			}
		}
		$this->set_field_default_value ( implode ( "\r\n", $unique_values ) ) ;
	}

	/**
	 * @return mixed|void
	 */
	public function print_form_field ( ) {
		printf ( '<tr valign="top">' );
		printf ('<th scope="row">');
		// Left-aligned label.
		if ( $this->get_label_position() == 'left' ) {
			if ($this->get_show_label() ) {
				printf(
					'%1$s',
					$this->get_field_label()
				);
			}
			if ( $this->get_label_help_text() ) {
				printf(
					'<br><small>%1$s</small>',
					$this->get_label_help_text()
				);
			}
		}
		printf ('</th>' );
		printf ('<td>') ;
		if ( $this->get_label_position() == 'top' ) {
			if ( $this->get_show_label() ) {
				printf(
					'%1$s',
					$this->get_field_label()
				);
			}
			if ( $this->get_label_help_text() ) {
				printf(
					'<br><small>%1$s</small><br>',
					$this->get_label_help_text()
				);
			}
		}
		printf (
			'<textarea name="%1$s" id="%6$s" rows="%4$d" cols="%5$d" placeholder="%2$s">%3$s</textarea>',
			esc_attr ( $this->get_field_input_name() ),
			esc_attr ( $this->get_text_area_placeholder() ),
			esc_attr ( $this->get_field_default_value() ),
			$this->get_rows(),
			$this->get_columns(),
			$this->get_field_id()
		) ;
		if ( $this->get_field_help_text() ) {
			printf (
				'<br><small>%1$s</small>',
				$this->get_field_help_text()
			);
		}
		printf ( '</td></tr>' );

	}
}
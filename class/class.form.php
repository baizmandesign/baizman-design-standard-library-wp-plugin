<?php
/**
 * Form class.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

class form {

	/**
	 * @var
	 */
	private $form_name ;
	/**
	 * @var
	 */
	private $form_action ;
	/**
	 * @var
	 */
	private $form_nonce ;
	/**
	 * @var
	 */
	private $form_post_direct ;

	/**
	 * @var array
	 */
	private $form_sections = [] ;

	/**
	 * @var array
	 */
	private $form_fields = [] ;

	/**
	 * @var array
	 */
	private $form_database_settings = [] ;

	/**
	 * @var array
	 */
	private $form_default_settings = [] ;

	/**
	 * @var
	 */
	private $settings_fields_option_group ;

	/**
	 * @var
	 */
	private $settings_fields_page ;

	/**
	 * @var array
	 */
	private $table_classes = [] ;

	/**
	 * form constructor.
	 *
	 * @param $form_name
	 */
	public function __construct( $form_name ) {
		$this->set_form_name ( $form_name ) ;
		$this->add_table_class ( 'form-table' );
		$this->add_table_class ( $form_name.'-table' );
	}

	/**
	 * @param $form_name
	 */
	public function set_form_name ( $form_name ):void {
		$this->form_name = $form_name ;
	}

	/**
	 * @return mixed
	 */
	public function get_form_name () {
		return $this->form_name ;
	}

	/**
	 * @param $form_action
	 */
	public function set_form_action ( $form_action ):void {
		$this->form_action = $form_action ;
	}

	/**
	 * @return mixed
	 */
	public function get_form_action () {
		return $this->form_action ;
	}

	/**
	 * @param $form_nonce
	 */
	public function set_form_nonce ( $form_nonce ):void {
		$this->form_nonce = $form_nonce ;
	}

	/**
	 * @return mixed
	 */
	public function get_form_nonce () {
		return $this->form_nonce ;
	}

	/**
	 * @param $form_post_direct
	 */
	public function set_form_post_direct ( $form_post_direct ):void {
		$this->form_post_direct = $form_post_direct ;
	}

	/**
	 * @return mixed
	 */
	public function get_form_post_direct () {
		return $this->form_post_direct ;
	}

	/**
	 * @param $form_section
	 */
	public function add_form_section ( $form_section ):void {
		$this->form_section[] = $form_section ;
	}

	/**
	 * @return mixed
	 */
	public function get_form_sections () {
		return $this->form_section ;
	}

	/**
	 * @return array
	 */
	public function get_form_default_settings(): array {
		return $this->form_default_settings;
	}

	/**
	 * @param array $form_default_settings
	 */
	public function set_form_default_settings( array $form_default_settings ): void {
		$this->form_default_settings = $form_default_settings;
	}

	/**
	 * @return array
	 */
	public function get_form_database_settings (): array {
		return $this->form_database_settings ;
	}

	/**
	 * @param $form_database_settings
	 */
	public function set_form_database_settings ( array $form_database_settings ):void {
		$this->form_database_settings = $form_database_settings ;
	}

	/**
	 * @return mixed
	 */
	public function get_settings_fields_option_group() {
		return $this->settings_fields_option_group;
	}

	/**
	 * @param mixed $settings_fields_option_group
	 */
	public function set_settings_fields_option_group( $settings_fields_option_group ): void {
		$this->settings_fields_option_group = $settings_fields_option_group;
	}

	/**
	 * @return mixed
	 */
	public function get_settings_fields_page() {
		return $this->settings_fields_page;
	}

	/**
	 * @param mixed $settings_fields_page
	 */
	public function set_settings_fields_page( $settings_fields_page ): void {
		$this->settings_fields_page = $settings_fields_page;
	}

	/**
	 * @return array
	 */
	public function get_form_fields() {
		return $this->form_fields;
	}

	/**
	 * @param array $form_fields
	 */
	public function add_form_field( $form_field ) {
		$this->form_fields[] = $form_field;
	}

	/**
	 * @return array
	 */
	public function get_table_classes() {
		return $this->table_classes;
	}

	/**
	 * @param $table_class
	 */
	public function add_table_class( $table_class ) {
		$this->table_classes[] = $table_class;
	}

	/**
	 * Render form.
	 */
	public function render_form ():void {
		printf ( '<form method="post" action="%s">', admin_url( 'admin-post.php' ) );
		// FIXME: resolve the two nonces! (and other fields)
		// https://developer.wordpress.org/apis/handbook/settings/
		settings_fields ( $this->get_settings_fields_option_group() );
//		do_settings_sections ( $this->get_settings_fields_page() ) ;
		// is the field below needed?

		printf (
			'<table class="%1$s">',
			implode( ' ', $this->get_table_classes( ) )
		) ;

		foreach ( $this->get_form_fields() as $form_field ) {
			$form_field->print_form_field() ;
		}

		printf ('</table>') ;

        wp_nonce_field ( 'bzmndsgn_save_config' ); // store nonce elsewhere?

        submit_button ( ) ;

	}

}
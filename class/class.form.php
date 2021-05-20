<?php
/**
 * Form class.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design ;

class form {

	/**
	 * @var
	 */
	private $form_name ;

	/**
	 * @var array
	 */
	private $form_fields = [] ;

	/**
	 * @var array
	 */
	private $form_database_settings = [] ;

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
//		add_action( 'admin_init', [ $this, 'site_config_settings' ] );
//		add_action( 'admin_post_update_bdsl', [$this,'save_form_data'] );

		// TODO: replace function with variable.
//		if ( is_multisite() ) {
//			add_action( 'network_admin_edit_bzmndsgn_save_network_config_settings', [$this, 'save_network_form_data'] );
//			add_action( 'admin_init', [$this, 'network_config_settings'] );
//		}

	}

	/**
	 * @param $form_name
	 */
	public function set_form_name ( $form_name ):void {
		$this->form_name = $form_name ;
	}

	/**
	 * @return array
	 */
	public function get_form_database_settings (): array {
		return $this->form_database_settings ;
	}

	/**
	 * @param mixed $settings_fields_option_group
	 */
	// FIXME: replace with call to bdsl constant, and revise calls to instantiate form.
	public function set_settings_fields_option_group( $settings_fields_option_group ): void {
		$this->settings_fields_option_group = $settings_fields_option_group;
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
		
		printf (
			'<table class="%1$s">',
			implode( ' ', $this->get_table_classes( ) )
		) ;

		foreach ( $this->get_form_fields() as $form_field ) {
			$form_field->print_form_field() ;
		}

		printf ('</table>') ;

		// Print checkbox fields in an invisible field.
		$checkbox_fields = [] ;
		foreach ( $this->get_form_fields() as $form_field ) {
			if ( $form_field->get_field_type() == 'checkbox' ) {
				$checkbox_fields[] = $form_field->get_field_input_name();
			}
		}

		if ( count ( $checkbox_fields ) > 0 ) {
			printf ( '<input type="hidden" name="single_checkboxes" value="%s" />', implode ( ',', $checkbox_fields ) ) ;
		}

		// Print checkbox groups names in an invisible field.
		$checkbox_groups = [] ;
		foreach ( $this->get_form_fields() as $form_field ) {
			if ( $form_field->get_field_type() == 'checkbox_group' ) {
				$checkbox_groups[] = $form_field->get_checkbox_input_name();
			}
		}

		if ( count ( $checkbox_groups ) > 0 ) {
			printf ( '<input type="hidden" name="checkbox_groups" value="%s" />', implode ( ',', $checkbox_groups ) ) ;
		}

		// Important to prevent nonce conflicts with other plugins, especially my own.
		// Action value must match hook. update_bdsl --> admin_post_update_bdsl
		printf ( '<input type="hidden" name="action" value="update_bdsl" />' ) ;
		printf ( '<input type="hidden" name="option_page" value="bzmndsgn-standard-library-plugin-settings-group">' ) ;
        wp_nonce_field ( bdsl::nonce_name );

        submit_button ( ) ;

	}
	/**
	 * Register per-site settings.
	 */
	public static function site_config_settings() {
		foreach ( preferences::get_default_database_options ( ) as $key => $value ) {
			register_setting( bdsl::settings_group, $key );
		}
	}

	/**
	 * Register network settings.
	 */
	public static function network_config_settings() {
		// Network Settings.

		foreach ( preferences::get_default_network_database_options() as $key => $value ) {
			register_setting( bdsl::network_settings_group, $key );
		}
	}

	/**
	 * Save per-site settings to database.
	 */
	public static function save_form_data () {
		// Check that user has proper security level.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have permission to update these settings.' );
		}

		// Check referrer.
		check_admin_referer( bdsl::nonce_name );

		// Store updated settings.
		$updated_options = [];

		foreach ( preferences::get_database_options() as $option => $value ) {
			if ( isset ( $_POST[ $option ] ) ) {
				$updated_options[ $option ] = stripslashes_deep( $_POST[ $option ] ); // sanitize_option, sanitize_textarea_field
			}
		}

		$checkboxes = [];
		// Identify the checkboxes. Get them from the hidden field, "single_checkboxes".
		if ( isset ( $_POST['single_checkboxes'] ) ) {
			$checkboxes = explode( ',', $_POST['single_checkboxes'] );
		}

		// Force the values for checkboxes.
		if ( count( $checkboxes ) > 0 ) {
			foreach ( $checkboxes as $checkbox ) {
				if ( isset ( $_POST[ $checkbox ] ) ) {
					// The field is checked. Set field value to '1'.
					$updated_options[ $checkbox ] = '1';
				} else {
					// The field was unchecked. Set field value to '0'.
					$updated_options[ $checkbox ] = '0';
				}
			}
		}

		$checkbox_groups = [];
		// Identify checkbox groups. Get them from the hidden field, "checkbox_groups".
		if ( isset ( $_POST['checkbox_groups'] ) ) {
			$checkbox_groups = explode( ',', $_POST['checkbox_groups'] );
		}

		// Force the values for checkbox groups.
		if ( count( $checkbox_groups ) > 0 ) {
			foreach ( $checkbox_groups as $checkbox_group ) {
				if ( isset ( $_POST[ $checkbox_group ] ) ) {
					// A field in the group is checked. Set the field value to its array in the $_POST variable.
					$updated_options[ $checkbox_group ] = $_POST[ $checkbox_group ];
				} else {
					// No fields in the group are checked. Set the field value to an empty array.
					$updated_options[ $checkbox_group ] = [];
				}
			}
		}

		// Update the options.
		$merged_options = wp_parse_args( $updated_options, preferences::get_database_options() );
		preferences::set_database_options( $merged_options );

		utility::form_redirect( 'The settings have been saved.' );

		exit;
	}

	/**
	 * Save network settings to database.
	 */
	public static function save_network_form_data() {

		// Check that user has proper security level.
		if ( ! current_user_can( 'manage_network_options' ) ) {
			wp_die( 'You do not have permission to update the network-level settings.' );
		}

		// Check referrer.
		check_admin_referer( 'bzmndsgn_save_network_config' );

		// Get current settings.
		$bzmndsgn_network_config_options_database = get_site_option( bdsl::multisite_config_options_key );

		// Store updated settings.
		$updated_options = array ();

		foreach ( $bzmndsgn_network_config_options_database as $option => $value ) {
			if ( isset ( $_POST[ $option ] ) ) {
				$updated_options[ $option ] = stripslashes_deep( $_POST[ $option ] ); // sanitize_option, sanitize_textarea_field
			}
		}

		// Update the options.
		$merged_options = wp_parse_args( $updated_options, $bzmndsgn_network_config_options_database );
		update_site_option( bdsl::multisite_config_options_key, $merged_options );

		// Get the referring page from the query string ($_GET['page']).
		$referrer   = $_POST['_wp_http_referer'];
		$link_parts = parse_url( $referrer );
		$query      = $link_parts['query'];
		parse_str( $query, $query_array );

		// Redirect with success=1 query string.
		wp_redirect(
			add_query_arg(
				array (
					'page'    => $query_array['page'],
					'message' => '1',
				),
				network_admin_url( 'admin.php' )
			)
		);

		exit;

	}

}
<?php
/**
 * Class menu. Implements a top-level menu in the WP dashboard.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design ;

class menu {

	/**
	 * @var
	 */
	private $page_title ;
	/**
	 * @var
	 */
	private $menu_title ;
	/**
	 * @var
	 */
	private $capability ;
	/**
	 * @var
	 */
	private $menu_slug ;
	/**
	 * @var
	 */
	private $function ;
	/**
	 * @var
	 */
	private $icon_url ;

	/**
	 * menu constructor.
	 *
	 * @param $page_title
	 * @param $menu_slug
	 * @param $callback
	 * @param bool $is_parent_menu
	 * @param string $icon_url
	 * @param string $capability
	 */
	public function __construct( $page_title, $menu_slug, $callback, $is_parent_menu = false, string $icon_url = '', string $capability = 'manage_options' ) {
		$this->set_page_title( $page_title );
		$this->set_menu_title( $page_title );
		$this->set_capability ( $capability ) ;
		$this->set_menu_slug( $menu_slug );
		$this->set_function( $callback );
		$this->set_icon_url( $icon_url );

		if ( $is_parent_menu ) {
			$this->add_hook();
		}

	}

	private function add_hook () {
		if (bdsl::show_dashboard_interface) {
			add_action( 'admin_menu', [ $this, 'render_menu' ], 1 );
		}
	}

	/**
	 * @param $page_title
	 */
	public function set_page_title ( $page_title ) {
		$this->page_title = $page_title ;
	}

	/**
	 * @return mixed
	 */
	public function get_page_title ( ) {
		return $this->page_title ;
	}

	/**
	 * @param $menu_title
	 */
	public function set_menu_title ( $menu_title ) {
		$this->menu_title = $menu_title ;
	}

	/**
	 * @return mixed
	 */
	public function get_menu_title ( ) {
		return $this->menu_title ;
	}

	/**
	 * @param $capability
	 */
	public function set_capability ( $capability ) {
		$this->capability = $capability ;
	}

	/**
	 * @return mixed
	 */
	public function get_capability ( ) {
		return $this->capability ;
	}

	/**
	 * @param $menu_slug
	 */
	public function set_menu_slug ( $menu_slug ) {
		$this->menu_slug = $menu_slug ;
	}

	/**
	 * @return mixed
	 */
	public function get_menu_slug ( ) {
		return $this->menu_slug ;
	}

	/**
	 * @param $function
	 */
	public function set_function ( $function ) {
		$this->function = $function ;
	}

	/**
	 * @return mixed
	 */
	public function get_function ( ) {
		return $this->function ;
	}

	/**
	 * @param $icon_url
	 */
	public function set_icon_url ( $icon_url ) {
		$this->icon_url = $icon_url ;
	}

	/**
	 * @return mixed
	 */
	public function get_icon_url ( ) {
		return $this->icon_url ;
	}

	/**
	 * Render the menu.
	 */
	public function render_menu ( ) {

		add_menu_page (
			$this->get_page_title(),
			$this->get_menu_title(),
			$this->get_capability(),
			$this->get_menu_slug(),
			$this->get_function(),
			$this->get_icon_url()
		) ;

	}
}
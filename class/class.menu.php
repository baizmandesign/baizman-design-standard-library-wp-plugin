<?php
/**
 * Class menu. Implements a top-level menu in the WP dashboard.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

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
	 * @var
	 */
	private $parent_menu_slug ;

	// Array of submenu items.
	/**
	 * @var array
	 */
	private $submenu_items = [] ;

	/**
	 * menu constructor.
	 *
	 * @param $page_title
	 * @param $menu_slug
	 * @param string $icon_url
	 * @param string $capability
	 */
	public function __construct( $page_title, $menu_slug, $icon_url = '', $capability = 'manage_options' ) {
		$this->set_page_title( $page_title );
		$this->set_menu_title( $page_title );
		$this->set_capability ( $capability ) ;
		$this->set_menu_slug( $menu_slug );
		$this->set_function( $menu_slug );
		$this->set_icon_url( $icon_url );
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
	 * @param submenu $submenu_item
	 */
	public function add_submenu_item ( submenu $submenu_item ) {
		$this->submenu_items[] = $submenu_item ;
	}

	/**
	 * @return array
	 */
	public function get_submenus () {
		return $this->submenu_items ;
	}

	/**
	 * Render the menu and submenu(s).
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

		foreach ( $this->get_submenus( ) as $submenu_item ) {
			$submenu_item->render_submenu ( $this->get_menu_slug() ) ;
		}
	}

}
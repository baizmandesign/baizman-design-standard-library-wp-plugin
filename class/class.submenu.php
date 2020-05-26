<?php
/**
 * Class submenu. Implements a submenu item in the WP dashboard.
 */

class submenu extends menu {

	/**
	 * submenu constructor.
	 *
	 * @param $page_title
	 * @param $menu_slug
	 */
	public function __construct( $page_title, $menu_slug ) {
		parent::__construct ( $page_title, $menu_slug );
	}

	/**
	 * Render the submenu.
	 * @param $parent_menu_slug
	 */
	protected function render_submenu ( $parent_menu_slug ) {

		add_submenu_page (
			$parent_menu_slug, // parent slug
			$this->get_page_title(),
			$this->get_menu_title(),
			$this->get_capability(),
			$this->get_menu_slug(),
			$this->get_function()
		) ;
		// no dashboard icon.

	}

}
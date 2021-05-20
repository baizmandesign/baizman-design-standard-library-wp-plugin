<?php
/**
 * Class submenu. Implements a submenu item in the WP dashboard.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design ;

class submenu extends menu {

	private bool $is_submenu = true ;

	/**
	 * submenu constructor.
	 *
	 * @param $page_title
	 * @param $menu_slug
	 * @param $callback
	 */
	public function __construct( $page_title, $menu_slug, $callback) {
		parent::__construct ( $page_title, $menu_slug, $callback);
		// add_action( 'admin_menu', [ $this, 'render_submenu' ], 1 );
		$this->add_hook();
	}

	private function add_hook () {
		add_action( 'admin_menu', [ $this, 'render_submenu' ], 1 );
	}

	/**
	 * Render the submenu.
	 */
	public function render_submenu ( ) {

		add_submenu_page (
			bdsl::parent_menu_slug,
			$this->get_page_title(),
			$this->get_menu_title(),
			$this->get_capability(),
			$this->get_menu_slug(),
			$this->get_function()
		) ;
		// no dashboard icon.

	}

}
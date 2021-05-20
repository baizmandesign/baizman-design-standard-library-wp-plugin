<?php
/**
 * Page class for a generic admin page.
 * @package Baizman Design Standard Library
 * @version 0.1
 */

namespace baizman_design\page ;

use baizman_design\bdsl;
use baizman_design\menu;
use baizman_design\preferences;
use baizman_design\submenu;
use baizman_design\utility;

abstract class page {

	public object $plugin ;

	private string $page_title ;

	private string $page_slug ;

	private string $capability = 'manage_options' ;

	private bool $is_parent_menu ;

	public function __construct ( $plugin, $page_title, $page_slug, $is_parent_menu = false ) {

		$this->plugin = $plugin ;
		$this->page_title = $page_title ;
		$this->page_slug = $page_slug ;
		$this->is_parent_menu = $is_parent_menu ;

		if ( $this->is_parent_menu ) {
			$this->add_menu() ;
		}
		$this->add_submenu() ;
	}

	private function get_function ( ) {
		return $this->render_page() ;
	}

	abstract function render_page ( ) ;

	public function add_menu () {
		$menu = new menu ( bdsl::plugin_name, $this->page_slug, [] , true );
	}
	public function add_submenu ( ) {
		$submenu = new submenu ( $this->page_title, $this->page_slug, [$this, 'render_page'] );

		 // $this->plugin->parent_menu->add_submenu_item( $submenu );
	}

}
<?php
/**
 * Class for plugin updater.
 * @package Baizman Design Standard Library
 * @version 0.1
 * @link https://wordpress.stackexchange.com/questions/13/updates-for-a-private-plugin
 */

namespace baizman_design ;

class updater {

	public static string $update_domain = 'wp.baizmandesign.com' ;
	public static function add_filter () {
		add_filter('update_plugins_'.self::$update_domain, [ __CLASS__,'check_for_updates' ], 10, 3);
	}

	public static function check_for_updates( $update, $plugin_data, $plugin_file ){

			static $response = false;

			if ( empty( $plugin_data['UpdateURI'] ) || ! empty( $update ) ) {
				return $update;
			}

			if ( $response === false ) {
				$response = wp_remote_get( $plugin_data['UpdateURI'] );
			}

			if ( empty( $response['body'] ) ) {
				return $update;
			}

			$custom_plugins_data = json_decode( $response['body'], true );

			file_put_contents('json.txt',print_r($custom_plugins_data,true),FILE_APPEND);

			if ( ! empty( $custom_plugins_data[ $plugin_file ] ) ) {
				return $custom_plugins_data[ $plugin_file ];
			}
			else {
				return $update;
			}

		}

}
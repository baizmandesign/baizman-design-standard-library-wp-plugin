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
				// https://rudrastyh.com/wordpress/check-license-key-in-plugin-updates.html
				$response = wp_remote_get(
					add_query_arg (
					[
						'referrer_domain' => urlencode ( $_SERVER['HTTP_HOST'] ),
					    'wp_plugin_url' => urlencode (WP_PLUGIN_URL ),
						'current_version' => urlencode ( $plugin_data['Version']),
					],
					$plugin_data['UpdateURI'] ) );
			}

			if ( is_wp_error($response)) {
				return $update;
			}

			if ( empty( $response['body'] ) ) {
				return $update;
			}

			$custom_plugins_data = json_decode( $response['body'], true );

			if ( ! empty( $custom_plugins_data[ $plugin_file ] ) ) {
				return $custom_plugins_data[ $plugin_file ];
			}
			else {
				return $update;
			}

		}

}
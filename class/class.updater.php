<?php
/**
 * Class for plugin updater.
 * @package Baizman Design Standard Library
 * @version 0.1
 * @link https://wordpress.stackexchange.com/questions/13/updates-for-a-private-plugin
 * @link https://rudrastyh.com/wordpress/self-hosted-plugin-update.html
 */

namespace baizman_design ;

class updater {

	public static string $production_update_domain = 'wp.baizmandesign.com' ;
	public static string $localhost_update_domain = 'localhost' ;
	public static int $localhost_update_port = 8000 ;

	public static function add_filter () {
		// don't change the filter name for localhost over-ride
		add_filter('update_plugins_'.self::$production_update_domain, [ __CLASS__,'check_for_updates' ], 10, 3);
		add_filter( 'plugins_api', [ __CLASS__, 'plugin_modal_info' ], 20, 3);
	}

	public static function check_for_updates( $update, $plugin_data, $plugin_file ){

		static $response = false;

		if ( empty( $plugin_data['UpdateURI'] ) || ! empty( $update ) ) {
			return $update;
		}

		if ( $response === false ) {

			// over-ride production hostname
			if ( wp_get_environment_type() == 'local' ) {
				$update_uri = str_replace (
					'https://'. self::$production_update_domain,
					'http://' . self::$localhost_update_domain.':'. self::$localhost_update_port,
					$plugin_data['UpdateURI'] ) ;
			}
			else {
				$update_uri = $plugin_data['UpdateURI'] ;
			}

			// https://rudrastyh.com/wordpress/check-license-key-in-plugin-updates.html
			// to quell wp cli warning about an undefined array key
			$referrer_domain = $_SERVER['HTTP_HOST'] ?? '[unknown domain]' ;
			$remote_get_url = add_query_arg (
				[
					'asset' => urlencode (preferences::$plugin_slug),
					'referrer_domain' => urlencode ( $referrer_domain ),
					'wp_plugin_url' => urlencode (WP_PLUGIN_URL ),
					'current_version' => urlencode ( $plugin_data['Version'] ),
				],
				$update_uri );

			$response = wp_remote_get(
				// FIXME: esc_url() broke url here.
				$remote_get_url,
				[
					'timeout' => 10,
					'headers' => [ 'Accept' => 'application/json', ]
				]
			);
		}

		if ( is_wp_error ( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) || empty ( wp_remote_retrieve_body( $response ) ) ) {
			return $update;
		}

		$custom_plugin_data = json_decode(wp_remote_retrieve_body( $response ),true);

		if ( ! empty( $custom_plugin_data[ $plugin_file ] ) ) {
			return $custom_plugin_data[ $plugin_file ];
		}
		else {
			return $update;
		}

	}

	/**
	 * @param $result
	 * @param $action
	 * @param $args
	 *
	 * @return \stdClass
	 */
	public static function plugin_modal_info ( $result, $action, $args ) {

		// do nothing if this is not about getting plugin information
		if ( $action !== 'plugin_information' ) {
			return $result;
		}

		// do nothing if it is not our plugin
		if ( preferences::$plugin_slug !== $args->slug ) {
			return $result;
		}

		$update = self::check_for_updates( '', get_plugin_data(preferences::$plugin_file_path), preferences::$plugin_basename ) ;

		if( ! $update ) {
			return $result;
		}

		$result = new \stdClass();

		$result->name = $update['name'];
		$result->slug = $update['slug'];
		$result->version = $update['version'];
		$result->tested = $update['tested'];
		$result->requires = $update['requires'];
		$result->author = $update['author'];
		$result->author_profile = ''; //$update['author_profile'];
		$result->download_link = ''; //$update['download_url'];
		$result->trunk = ''; //$update['download_url'];
		$result->homepage = $update['homepage'];
		$result->requires_php = $update['requires_php'];
		$result->last_updated = $update['last_updated'];

		$result->sections = [
			'description' => $update['sections']['description'],
			'installation' => $update['sections']['installation'],
			'changelog' => $update['sections']['changelog'],
		];

		if ( ! empty( $update['banners'] ) ) {
			$result->banners = [
				'low' => $update['banners']['low'],
				'high' => $update['banners']['high'],
			];
		}

		return $result;

	}

}
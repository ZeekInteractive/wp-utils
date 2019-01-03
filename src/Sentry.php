<?php

namespace Zeek\WP_Util;

class ErrorHandling {
	private static $client;

	public static $exclusions = [
		'Parameter 1 to wp_default_scripts() expected to be a reference, value given',
		'Parameter 1 to wp_default_styles() expected to be a reference, value given',
	];

	static function init() {
		$sentry_url = get_env_value( 'SENTRY_URL' );

		if ( empty( $sentry_url ) ) {
			return false;
		}

		if ( ! empty( self::$client ) ) {
			return self::$client;
		}

		$exclusions = self::$exclusions;

		$options = [
			'send_callback' => function ( $data ) use ( $exclusions ) {

				if ( 'error' === $data['level'] ) {
					return $data;
				}

				$value = $data['exception']['values'][0]['value'] ?? null;

				if ( empty( $value ) ) {
					return $data;
				}

				if ( in_array( $value, $exclusions ) ) {
					return false;
				}

				return $data;
			},
		];

		self::$client = new \Raven_Client( $sentry_url, $options );
		self::$client->install();

		return self::$client;
	}

	/**
	 * @return \Raven_Client
	 */
	static function get_client() {
		if ( empty( self::$client ) ) {
			self::init();
		}

		return self::$client;
	}
	
	
	static function capture( $exception ) {
		if ( empty( self::$client ) ) {
			return;
		}

		self::$client->captureException( $exception );
	}
}

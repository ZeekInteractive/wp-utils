<?php

namespace Zeek\WP_Util;

class ErrorHandling {
	private static $client;

	static function init() {
		$sentry_url = get_env_value( 'SENTRY_URL' );

		if ( empty( $sentry_url ) ) {
			return false;
		}

		if ( ! empty( self::$client ) ) {
			return self::$client;
		}

		self::$client = new \Raven_Client( $sentry_url );
		self::$client->install();

		return self::$client;
	}

	static function get_client() {
		if ( empty( self::$client ) ) {
			self::init();
		}

		return self::$client;
	}
}

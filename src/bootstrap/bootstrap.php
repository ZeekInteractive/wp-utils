<?php

namespace Zeek\WP_Util;

use Zeek\WpSentry\WpSentry;
use function A7\autoload;

function bootstrap( $dir ) {
	/**
	 * Load .env file and set up constants
	 */
	Constants::init( $dir );

	/**
	 * Add Sentry Error Logging
	 */
	WpSentry::init();

	/**
	 * Enable WP Util Behaviors
	 */
	new Behaviors();

	/**
	 * Autoload all php files in /src/
	 */
	autoload( $dir . '/src' );

	/**
	 * Kick off third party integrations as dictated by our .env variables
	 */
	new ThirdParty\Init();

	/**
	 * Load /bin folder for any wp cli commands safely
	 */
	bootstrap_wp_cli_commands( $dir );
}

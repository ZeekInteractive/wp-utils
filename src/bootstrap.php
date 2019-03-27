<?php

namespace Zeek\WP_Util;

use Zeek\WP_Util\ErrorHandling;
use function A7\autoload;
use function Zeek\WP_Util\get_env_value;

function bootstrap( $dir ) {
	/**
	 * Load .env file and set up constants
	 */
	Constants::init( $dir );

	/**
	 * Add Sentry Error Logging
	 */
	ErrorHandling::init();

	/**
	 * Enable WP Util Behaviors
	 */
	new \Zeek\WP_Util\Behaviors();

	/**
	 * Autoload all php files in /src/
	 * // @todo ensure this path exists
	 */
	autoload( $dir . '/src' );
}
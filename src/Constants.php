<?php

namespace Zeek\WP_Util;

use Arrilot\DotEnv\DotEnv;

class Constants
{
	private static $env_vars = [
		'ACF_LITE'              => true,
		'DISABLE_WP_CRON'       => false,
		'FILE_MOD_ALLOWED'      => false,
		'SENTRY_URL'            => null,
	];

	public static function init($dir)
	{
		// Set usable constants
		define('APP_URL', plugin_dir_url($dir));
		define('APP_PATH', dirname($dir).'/');

		/**
		 * Load dotenv if .env file is present
		 */
		if (file_exists($dir.'/.env.php')) {
			try {
				DotEnv::load($dir.'/.env.php');
				DotEnv::copyVarsToEnv();
			} catch (\Throwable $e ) {
				error_log('ENV load fail: Unable to properly load .env.php file, check that it is formed correctly');
			}
		}

		/**
		 * Set each environmental variable as a constant if not already defined
		 * This helps set constants for wordpress core type functionality
		 *
		 * Everything else should already be accessible via the `env()` function
		 */
		foreach (self::$env_vars as $key => $default) {
			self::env_set($key, $default);
		}
	}

	private static function env_set($key, $default)
	{
		// Check if we have env value
		$env_value = env($key, $default);

		if (! isset($env_value)) {
			return;
		}

		// if not defined
		if (defined($key)) {
			return;
		}

		define($key, $env_value);
	}
}

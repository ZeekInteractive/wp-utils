<?php

namespace Zeek\WP_Util;

use Arrilot\DotEnv\DotEnv;

class Constants
{
	private static $env_vars = [
		'ENVIRONMENT'		   => 'production',
		'ACF_LITE'			  => true,
		'DISABLE_WP_CRON'	   => false,
		'FILE_MOD_ALLOWED'	  => false,
		'SENTRY_URL'			=> null,
	];

	public static function init($dir)
	{
		// Set usable constants
		define('APP_URL', plugin_dir_url($dir) . 'app/');
		define('APP_PATH', $dir.'/');

		$envPath = APP_PATH.'.env.php';

		/**
		 * Load dotenv if .env file is present
		 */
		if (file_exists($envPath)) {
			try {
				DotEnv::load($envPath);
				DotEnv::copyVarsToEnv();
			} catch (\Throwable $e ) {
				error_log('ENV load fail: Unable to properly load .env.php file, check that it is formed correctly');
			}

			// For backwards compatibility, we will not throw errors unless ENVIRONMENT is actually defined in .env.php
			if (env("ENVIRONMENT")){

				// wp_get_environment_type() uses WP_ENVIRONMENT_TYPE, so update to match if not already defined
				if (!defined('WP_ENVIRONMENT_TYPE')){
					define('WP_ENVIRONMENT_TYPE',env('ENVIRONMENT'));
				}

				// Ensure env('ENVIRONMENT') and wp_get_environment_type() agree as these are the
				// expected ways to access the environment name.
				if ( env('ENVIRONMENT') !== wp_get_environment_type() ){
					throw new \Error("Conflicting environment names. "
						. "env('ENVIRONMENT') is '" . env("ENVIRONMENT") . "'. "
						. "wp_get_environment_type() is '".wp_get_environment_type()."'. "
						. "They must agree."
					);
				}
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

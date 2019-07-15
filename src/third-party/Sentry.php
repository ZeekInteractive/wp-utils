<?php

namespace Zeek\WP_Util;

class ErrorHandling {
	const SENTRY_JS_SDK_VERSION = '4.5.3';

	private static $client;

	public static $exclusions = [
		'Parameter 1 to wp_default_scripts() expected to be a reference, value given',
		'Parameter 1 to wp_default_styles() expected to be a reference, value given',
		'Parameter 1 to wp_default_packages() expected to be a reference, value given',
	];

	/**
	 * @var string
	 */
	private static $sentry_url;

	/**
	 * Initialize error reporting.
	 *
	 * @return bool Whether error reporting was successfully initialized.
	 */
	public static function init() {
		static::$sentry_url = get_env_value( 'SENTRY_URL' );

		if ( empty( static::$sentry_url ) ) {
			return false;
		}

		static::init_php();

		if ( boolval( get_env_value( 'SENTRY_JS' ) ) ) {
			static::init_js();
		}

		return true;
	}

	public static function init_js() {
		add_action( 'wp_enqueue_scripts', [ __NAMESPACE__ . '\ErrorHandling', 'js_enqueue_sentry_sdk' ] );
		add_action( 'wp_head', [ __NAMESPACE__ . '\ErrorHandling', 'js_init_sentry_sdk' ] );
	}

	public static function init_php() {

		if ( ! empty( self::$client ) ) {
			return self::$client;
		}

		$exclusions = self::$exclusions;

		$options = [
			'send_callback' => function ( $data ) use ( $exclusions ) {

				if ( static::lite_mode_enabled() && ! ( $data['force'] ?? false ) ) {
					return false;
				}

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

		self::$client = new \Raven_Client( static::$sentry_url, $options );
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

		self::$client->captureException( $exception, [ 'force' => true ] );
	}

	/**
	 * Enqueue the Sentry Javascript SDK.
	 */
	public static function js_enqueue_sentry_sdk() {
		wp_enqueue_script(
			'sentry',
			sprintf( 'https://browser.sentry-cdn.com/%s/bundle.min.js', static::SENTRY_JS_SDK_VERSION )
		);
	}

	/**
	 * Initialize the Sentry Javascript SDK.
	 */
	public static function js_init_sentry_sdk() {
		?>
		<script>
			Sentry.init( { dsn: <?php echo wp_json_encode( static::$sentry_url ); ?> } );
		</script>
		<?php
	}

	/**
	 * Whether lite mode is enabled.
	 *
	 * @return bool
	 */
	public static function lite_mode_enabled() {
		return boolval( get_env_value( 'ERROR_HANDLING_LITE_MODE_ENABLED' ) );
	}
}

<?php

namespace Zeek\WP_Util;

use function A7\autoload;

function bootstrap_wp_cli_commands( $dir ) {

	// Check to see if we're currently running WP CLI
	if ( ! defined( 'WP_CLI' ) ) {
		return;
	}

	if ( true !== WP_CLI ) {
		return;
	}

	if ( ! class_exists( 'WP_CLI' ) ) {
		return;
	}

	// Looks like we're running wp-cli, so we can safely load the /bin folder
	autoload( $dir . '/bin' );
}
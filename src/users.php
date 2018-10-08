<?php

namespace Zeek\WP_Util;

/**
 * Recursive function to find a unique username.
 *
 * Increments a counter which is appended to the given username until a unique username is found.
 *
 * @param string $username The username to be used as the base of the generated username.
 * @param int    $i        The counter to test. Normally only used by the function calling itself recursively.
 *
 * @return string The generated unique username.
 */
function generate_unique_username( string $username, int $i = 1 ) {

	$new_username = $username . $i;

	if ( ! username_exists( $new_username ) ) {
		return $new_username;
	}

	return call_user_func_array( __FUNCTION__, [ $username, $i + 1 ] );
}

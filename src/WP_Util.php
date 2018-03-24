<?php

namespace Zeek\WP_Util;

class WP_Util {

	/**
	 * Returns the current URL.
	 *
	 * @link http://wordpress.stackexchange.com/a/126534
	 *
	 * @return string
	 */
	static function get_current_url() {
		return home_url( add_query_arg( null, null ) );
	}
}
<?php

namespace Zeek\WP_Util;

class Behaviors {
	public function __construct() {

		/**
		 * Disables File Modifications by default.
		 * This ignores the constant that is set (possibly in wp-config.php) to get around
		 * hosts that do not respect changes in the constant.
		 *
		 * This utilizes the 'file_mod_allowed' filter to directly return the value.
		 *
		 * This can be overridden by setting an environmental variable of 'FILE_MOD_ALLOWED'
		 */
		add_filter( 'file_mod_allowed', [ $this, 'file_mod_allowed' ], 999 );
	}

	function file_mod_allowed() {
		if ( true === env( 'FILE_MOD_ALLOWED' ) ) {
			return true;
		}

		return false;
	}
}
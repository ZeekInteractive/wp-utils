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

		/**
		 * Disables the 'Update WordPress' admin nag notice
		 */
		add_filter( 'admin_init', [ $this, 'disable_update_nag'] );

		/**
		 * Removes the 'Thank you for creating with WordPress' message 
		 * Removes the 'Version x.x.x | Get Version x.x.x'
		 * From the bottom of all admin pages
		 */
		add_filter( 'admin_init', [ $this, 'remove_admin_footer_text_version'] );
	}

	function file_mod_allowed() {
		if ( true === env( 'FILE_MOD_ALLOWED' ) ) {
			return true;
		}

		return false;
	}

	function disable_update_nag() {
		remove_action( 'admin_notices', 'update_nag', 3 );
		remove_action( 'network_admin_notices', 'update_nag', 3 );
	}

	function remove_admin_footer_text_version() {
		add_filter( 'admin_footer_text', '__return_empty_string', 200 );
		add_filter( 'update_footer', '__return_empty_string', 200 );
	}
}

<?php

namespace Zeek\WP_Util\ThirdParty;

class Init {
	public function __construct() {

		/**
		 * This is where we can kick off / filter all allowable integrations
		 *
		 * For now we'll just hardcode the initializations
		 */

		new GoogleTagManager();
	}
}
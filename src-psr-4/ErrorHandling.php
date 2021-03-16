<?php

namespace Zeek\WP_Util;

use Zeek\WP_Util\Interfaces\ErrorHandling as ErrorHandlingInterface;

class ErrorHandling implements ErrorHandlingInterface {
	public static function capture( \Exception $exception ) : void {
		$test = 1;
	}
}

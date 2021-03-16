<?php

namespace Zeek\WP_Util;

use Zeek\WP_Util\Interfaces\ErrorHandling as ErrorHandlingInterface;
use Zeek\WpSentry\WpSentry;

class ErrorHandling implements ErrorHandlingInterface {
	public static function capture( \Exception $exception ) : void {
		$sentry = WpSentry::getInstance();

		$sentry->captureException($exception);
	}
}

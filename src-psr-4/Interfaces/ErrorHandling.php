<?php

namespace Zeek\WP_Util\Interfaces;

interface ErrorHandling {
	public static function capture( \Exception $exception ) : void;
}

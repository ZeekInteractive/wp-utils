<?php

namespace Zeek\WP_Util;

class IsACFLoadableTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	public function testIsACFLoadable() {

		$this->assertEquals( false, is_acf_loadable() );
	}
}
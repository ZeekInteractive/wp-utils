<?php

class IsACFLoadableTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	public function testIsACFLoadable() {

		$this->assertEquals( false, \Zeek\WP_Util\Misc::is_acf_loadable() );
	}
}
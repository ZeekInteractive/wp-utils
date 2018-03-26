<?php

namespace Zeek\WP_Util;

class GetCurrentUrlCleanTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
		\WP_Mock::setUp();
	}

	protected function _after() {
		\WP_Mock::tearDown();
	}

	public function testGetCurrentURLClean() {

		$base_url = 'http://example.com/foo';

		\WP_Mock::userFunction( 'home_url', array(
			'times'  => 2,
			'return' => $base_url
		) );

		\WP_Mock::userFunction( 'add_query_arg', array(
			'times'  => 1,
			'return' => ''
		) );

		$this->assertEquals( $base_url, get_current_url_clean() );
	}
}
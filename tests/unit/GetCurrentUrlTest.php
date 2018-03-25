<?php

class GetCurrentUrlTest extends \Codeception\Test\Unit {
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

	public function testGetCurrentUrl() {
		$url = 'http://example.com/foo';

		\WP_Mock::userFunction( 'home_url', array(
			'times'  => 1,
			'return' => $url
		) );

		\WP_Mock::userFunction( 'add_query_arg', array(
			'times'  => 1,
			'return' => ''
		) );

		$this->assertEquals( $url, \Zeek\WP_Util\Misc::get_current_url() );
	}
}
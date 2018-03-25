<?php

class GetCurrentDateTimeTest extends \Codeception\Test\Unit {
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

	public function testGetCurrentDateTime() {
		$timezone = new \DateTimeZone( 'America/Chicago' );

		\WP_Mock::userFunction( 'get_option', [
			'times'  => 1,
			'return' => 'America/Chicago',
		] );

		$datetime_assert = new \DateTime( 'now', $timezone );

		$this->assertEquals(
			$datetime_assert->getTimestamp(),
			\Zeek\WP_Util\WP_Util::get_current_datetime()->getTimestamp() )
		;
	}
}
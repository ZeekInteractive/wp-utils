<?php

class GetSiteTimeZoneTest extends \Codeception\Test\Unit
{
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

	public function testGetSiteTimeZone() {
		$assert_timezone = new \DateTimeZone( 'America/Chicago' );

		\WP_Mock::userFunction( 'get_option', [
			'times'  => 1,
			'return' => 'America/Chicago',
		] );

		$site_timezone = \Zeek\WP_Util\Misc::get_site_timezone();

		$this->assertEquals( $assert_timezone, $site_timezone );
	}

	public function testGetSiteTimeZoneDefault() {
		$assert_timezone = new \DateTimeZone( 'UTC' );

		\WP_Mock::userFunction( 'get_option', [
			'times'  => 1,
			'return' => '',
		] );

		$site_timezone = \Zeek\WP_Util\Misc::get_site_timezone();

		$this->assertEquals( $assert_timezone, $site_timezone );
	}
}
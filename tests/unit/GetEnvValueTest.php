<?php

namespace Zeek\WP_Util;

class GetEnvValueTest extends \Codeception\Test\Unit {
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

	public function testGetEnvValueFromENV() {

		$_ENV[ 'SOME_ENV_KEY_1' ] = 'somevalue';

		$this->assertEquals( 'somevalue', get_env_value( 'SOME_ENV_KEY_1' ) );
	}

	public function testGetEnvValueFromConstant() {

		define( 'SOME_ENV_KEY_2', 'another_value' );

		$this->assertEquals( 'another_value', get_env_value( 'SOME_ENV_KEY_2' ) );
	}

	public function testGetNonexistentEnvValue() {

		$this->assertEquals( null, get_env_value( 'SOME_ENV_KEY_4' ) );
	}
}
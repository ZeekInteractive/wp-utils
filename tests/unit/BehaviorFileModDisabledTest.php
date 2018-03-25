<?php

class BehaviorFileModDisabledTest extends \Codeception\Test\Unit {
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

	public function testFilterForFileModSet() {
		\WP_Mock::userFunction( 'add_filter', [
			'return_arg' => 0
		] );
	}

	public function testFileModAllowed() {
		$behaviors = new Zeek\WP_Util\Behaviors();

		\Arrilot\DotEnv\DotEnv::set( 'FILE_MOD_ALLOWED', true );

		$this->assertEquals( true, $behaviors->file_mod_allowed() );
	}

	public function testFileModNotAllowed() {
		$behaviors = new Zeek\WP_Util\Behaviors();

		\Arrilot\DotEnv\DotEnv::set( 'FILE_MOD_ALLOWED', false );

		$this->assertEquals( false, $behaviors->file_mod_allowed() );
	}
}
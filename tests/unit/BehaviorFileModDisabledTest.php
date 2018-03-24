<?php

class BehaviorFileModDisabledTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
	}

	protected function _after() {
	}

	// tests
	public function testFilterForFileModSet() {
		\WP_Mock::userFunction( 'add_filter', [
			'return_arg' => 0
		] );

	}
}
<?php

namespace Zeek\WP_Util;

class RemoveFiltersForAnonymousClassTest extends \Codeception\Test\Unit {
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

	public function testRemoveFiltersWithNoMatch() {
		global $wp_filter;

		$wp_filter = null;

		$remove_filter = remove_filters_for_anonymous_class( 'test_nonexistent_hook', 'NoClass', 'method_name', 10 );

		$this->assertSame( false, $remove_filter );
	}

	public function testRemoveFiltersForAnonymousClass() {
		global $wp_filter;

		$test_class = \Mockery::mock( 'ClassName' );

		$test_class_name = get_class( $test_class );

		$test_hook = \Mockery::mock( '\WP_Hook' );

		$callback = [
			'012308140810381' => [
				'function' => [
					0 => $test_class,
					1 => 'test_method',
				],
				'accepted_args' => 1,
			],
		];

		$test_hook->callbacks = [
			10 => $callback,
		];

		$wp_filter['test_hook'] = $test_hook;

		$remove_filter = remove_filters_for_anonymous_class( 'test_hook', $test_class_name, 'test_method', 10 );

		$this->assertEquals( true, $remove_filter );
	}
}
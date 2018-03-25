<?php

namespace Zeek\WP_Util;

class InitTermTest extends \Codeception\Test\Unit {
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

	public function testTermAlreadyInitialized() {

		$term_mock = \Mockery::mock( 'WP_Term' );
		$term_mock->ID = 123;

		\WP_Mock::userFunction( 'get_term_by', array(
			'times'  => 1,
			'args'   => [
				'slug',
				'test_slug',
				'example_taxonomy'
			],
			'return' => $term_mock
		) );

		$term = init_term( 'test_slug', 'example_taxonomy' );

		$this->assertEquals( $term_mock->ID, $term );
	}


	public function testInsertNewTerm() {

		$term_mock = \Mockery::mock( 'WP_Term' );
		$term_mock->ID = 123;

		\WP_Mock::userFunction( 'get_term_by', array(
			'times'  => 1,
			'args'   => [
				'slug',
				'test_slug',
				'example_taxonomy'
			],
			'return' => false
		) );

		\WP_Mock::userFunction( 'wp_insert_term', [
			'times'  => 1,
			'args' => [
				'test_slug',
				'example_taxonomy'
			],
			'return' => [
				'term_id' => 123,
				'term_taxonomy_id' => 4321,
			],
		] );

		$term = init_term( 'test_slug', 'example_taxonomy' );

		$this->assertEquals( [
			'term_id' => 123,
			'term_taxonomy_id' => 4321,
		], $term );
	}
}
<?php

namespace Zeek\WP_Util;

class GetMetaKeyFromMetaValueTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
		\WP_Mock::setUp();
	}

	protected function _after() {
		\WP_Mock::tearDown();
		\Mockery::close();
	}

	public function testGetMetaKeyFromMetaValue() {
		global $wpdb;

		$wpdb = \Mockery::mock( '\WPDB' );

		$wpdb->postmeta = 'wp_postmeta';

		$sql = "SELECT
					pm.meta_key
				FROM
					wp_postmeta as pm
				WHERE
					pm.post_id = 43 AND
					pm.meta_value = some_value_34";

		$wpdb->shouldReceive( 'prepare' )
		     ->andReturn( $sql );

		$wpdb->shouldReceive( 'get_var' )
		     ->andReturn( 'some_meta_key' );

		\WP_Mock::passthruFunction( 'sanitize_text_field' );

		$meta_key = get_meta_key_from_meta_value( 43, 'some_value_34' );

		$this->assertEquals( 'some_meta_key', $meta_key );
	}
}
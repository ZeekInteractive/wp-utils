<?php

namespace Zeek\WP_Util;

class GetRawPostMetaValueTest extends \Codeception\Test\Unit {
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

	public function testGetRawPostMetaValue() {
		global $wpdb;

		$wpdb = \Mockery::mock( '\WPDB' );

		$wpdb->postmeta = 'wp_postmeta';

		$sql = "SELECT 
					meta_value
				FROM 
					wp_postmeta
				WHERE 
					post_id = 52 AND
					meta_key = a_valid_key
				LIMIT 1";

		$wpdb->shouldReceive( 'prepare' )
		     ->andReturn( $sql );

		$wpdb->shouldReceive( 'get_var' )
		     ->andReturn( 'legit_value' );

		$meta_value = get_raw_post_meta_value( 52, 'a_valid_key' );

		$this->assertSame( 'legit_value', $meta_value );
	}
}
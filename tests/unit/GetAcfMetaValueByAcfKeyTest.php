<?php

namespace Zeek\WP_Util;

class GetAcfMetaValueByAcfKeyTest extends \Codeception\Test\Unit {
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

	public function testGetAcfMetaValueByAcfKey() {
		global $wpdb;

		$wpdb           = \Mockery::mock( '\WPDB' );
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
		     ->andReturn( '_some_meta_key' );

		\WP_Mock::passthruFunction( 'sanitize_text_field' );

		\WP_Mock::userFunction( 'get_post_meta', [
			'times' => 1,
			'args' => [
				43,
				'some_meta_key',
				true
			],
			'return' => 'real_value',
		] );

		$meta_value = get_acf_meta_value_by_acf_key( '_some_meta_key', 43 );

		$this->assertEquals( 'real_value', $meta_value );
	}

	public function testGetAcfMetaValueReturnFalseWithEmptyLookupKey() {
		global $wpdb;

		$wpdb           = \Mockery::mock( '\WPDB' );
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
		     ->andReturn( '' );

		\WP_Mock::passthruFunction( 'sanitize_text_field' );

		$meta_value = get_acf_meta_value_by_acf_key( '_some_meta_key', 43 );

		$this->assertSame( false, $meta_value );
	}

	public function testGetAcfValueInvalidKey() {
		global $wpdb;

		$wpdb           = \Mockery::mock( '\WPDB' );
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

		$meta_value = get_acf_meta_value_by_acf_key( '_some_meta_key', 43 );

		$this->assertSame( false, $meta_value );
	}
}
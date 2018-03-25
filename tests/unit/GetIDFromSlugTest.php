<?php

class GetIDFromSlugTest extends \Codeception\Test\Unit {
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

	public function testGetIDFromSlug() {
		global $wpdb;

		$wpdb        = Mockery::mock( '\WPDB' );
		$wpdb->posts = 'wp_posts';
		$wpdb->shouldReceive( 'get_var' )
		     ->andReturn( 32 );

		WP_Mock::userFunction( 'wp_cache_set', [
			'return' => false
		] );

		WP_Mock::userFunction( 'wp_cache_get', [
			'return' => false
		] );

		WP_Mock::passthruFunction( 'sanitize_text_field' );


		$id = \Zeek\WP_Util\Database::get_id_from_slug( 'test_post' );

		$this->assertEquals( 32, $id );
	}

	public function testNoPostWithThatSlug() {
		global $wpdb;

		$wpdb        = Mockery::mock( '\WPDB' );
		$wpdb->posts = 'wp_posts';
		$wpdb->shouldReceive( 'get_var' )
		     ->andReturn( false );

		WP_Mock::userFunction( 'wp_cache_set', [
			'return' => false
		] );

		WP_Mock::userFunction( 'wp_cache_get', [
			'return' => false
		] );

		WP_Mock::passthruFunction( 'sanitize_text_field' );


		$id = \Zeek\WP_Util\Database::get_id_from_slug( 'test_post' );

		$this->assertEquals( null, $id );
	}
}
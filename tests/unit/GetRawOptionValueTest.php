<?php

class GetRawOptionValueTest extends \Codeception\Test\Unit
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
		\Mockery::close();
	}

	public function testGetRawOptionValue() {
		global $wpdb;

		$wpdb           = Mockery::mock( '\WPDB' );
		$wpdb->options = 'wp_options';

		$sql = "SELECT 
					option_value 
				FROM 
					wp_options
				WHERE 
					option_name = option_name_slug
				LIMIT 1";

		$wpdb->shouldReceive( 'prepare' )
		     ->andReturn( $sql );

		$wpdb->shouldReceive( 'get_var' )
		     ->andReturn( 'an_option_value' );

		$option_value = \Zeek\WP_Util\Database::get_raw_option_value( 'option_name_slug' );

		$this->assertSame( 'an_option_value', $option_value );

	}
}
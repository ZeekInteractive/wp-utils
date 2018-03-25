<?php

class GetInlineSvgTest extends \Codeception\Test\Unit
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
	}

	public function testGetInlineNonexistentSvg() {

		WP_Mock::userFunction( 'get_template_directory', [
			'times' => 1,
			'return' => codecept_data_dir( '' )
		] );

		$svg = \Zeek\WP_Util\Misc::get_inline_svg( 'svg-nonexistent.svg' );

		$this->assertEquals( null, $svg );
	}

	public function testGetInlineSvg() {

		$svg_file_path = codecept_data_dir( 'svg.svg' );
		if ( file_exists( $svg_file_path ) ) {
			unlink( $svg_file_path );
		}

		$svg_file = fopen( $svg_file_path, 'w+' );

		fwrite( $svg_file, 'test_content_of_svg' );
		fclose( $svg_file );

		WP_Mock::userFunction( 'get_template_directory', [
			'times' => 1,
			'return' => codecept_data_dir( '' )
		] );

		$svg = \Zeek\WP_Util\Misc::get_inline_svg( 'svg.svg' );

		$this->assertEquals( 'test_content_of_svg', $svg );

		if ( file_exists( $svg_file_path ) ) {
			unlink( $svg_file_path );
		}
	}
}
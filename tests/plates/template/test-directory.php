<?php
/**
 * Class DirectoryTest
 *
 * @package Tektonik
 */

use Tektonik\Plates\Template\Directory;

class DirectoryTest extends WP_UnitTestCase {
	private $directory;
	private $path;

	public function setUp() {
		$this->directory = new Directory();
		$this->path      = TEST_DATA_DIR . DIRECTORY_SEPARATOR . 'tektonik';
	}

	public function testCanCreateInstance() {
		$this->assertInstanceOf( 'Tektonik\Plates\Template\Directory', $this->directory );
	}

	public function testSetDirectory() {

		$this->assertInstanceOf( 'Tektonik\Plates\Template\Directory', $this->directory->set( array( $this->path ) ) );
		$this->assertEquals( $this->directory->get(), array( $this->path ) );
	}

	public function testSetNullDirectory() {
		$this->assertInstanceOf( 'Tektonik\Plates\Template\Directory', $this->directory->set( null ) );
		$this->assertEquals( $this->directory->get(), null );
	}

	public function testGetDirectory() {
		$this->assertEquals( $this->directory->get(), null );
	}
}

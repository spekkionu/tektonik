<?php
/**
 * Class FolderTest
 *
 * @package Tektonik
 */

use Tektonik\Plates\Template\Folder;

class FolderTest extends WP_UnitTestCase {
	private $folder;
	public function setUp()
	{
		$this->path      = TEST_DATA_DIR . DIRECTORY_SEPARATOR . 'tektonik';
		$this->folder = new Folder('folder', TEST_DATA_DIR . DIRECTORY_SEPARATOR . 'tektonik');
	}
	public function testCanCreateInstance()
	{
		$this->assertInstanceOf('Tektonik\Plates\Template\Folder', $this->folder);
	}
	public function testSetAndGetName()
	{
		$this->folder->set_name('name');
		$this->assertEquals($this->folder->get_name(), 'name');
	}
	public function testSetAndGetPath()
	{
		$this->folder->set_path($this->path . DIRECTORY_SEPARATOR . 'plugin');
		$this->assertEquals($this->folder->get_path(), $this->path . DIRECTORY_SEPARATOR . 'plugin');
	}
	public function testSetInvalidPath()
	{
		$this->setExpectedException('InvalidArgumentException');
		$this->folder->set_path($this->path . DIRECTORY_SEPARATOR . 'does_not_exist' );
	}
	public function testSetAndGetFallback()
	{
		$this->assertEquals($this->folder->get_fallback(), false);
		$this->folder->set_fallback(true);
		$this->assertEquals($this->folder->get_fallback(), true);
	}

}

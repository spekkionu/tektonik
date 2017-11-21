<?php
/**
 * Class FoldersTest
 *
 * @package Tektonik
 */

use Tektonik\Plates\Template\Folders;

class FoldersTest extends WP_UnitTestCase {
	private $folders;
	public function setUp()
	{
		$this->path      = TEST_DATA_DIR . DIRECTORY_SEPARATOR . 'tektonik';
		$this->folders = new Folders();
	}
	public function testCanCreateInstance()
	{
		$this->assertInstanceOf('Tektonik\Plates\Template\Folders', $this->folders);
	}
	public function testAddFolder()
	{
		$this->assertInstanceOf('Tektonik\Plates\Template\Folders', $this->folders->add('name', $this->path));
		$this->assertEquals($this->folders->get('name')->get_path(), $this->path);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testAddFolderWithNamespaceConflict()
	{
		$this->folders->add('name', $this->path);
		$this->folders->add('name', $this->path);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testAddFolderWithInvalidDirectory()
	{
		$this->folders->add('name', $this->path . DIRECTORY_SEPARATOR . 'does_not_exist' );
	}
	public function testRemoveFolder()
	{
		$this->folders->add('folder', $this->path);
		$this->assertEquals($this->folders->exists('folder'), true);
		$this->assertInstanceOf('Tektonik\Plates\Template\Folders', $this->folders->remove('folder'));
		$this->assertEquals($this->folders->exists('folder'), false);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testRemoveFolderWithInvalidDirectory()
	{
		$this->folders->remove('name');
	}
	public function testGetFolder()
	{
		$this->folders->add('name', $this->path);
		$this->assertInstanceOf('Tektonik\Plates\Template\Folder', $this->folders->get('name'));
		$this->assertEquals($this->folders->get('name')->get_path(), $this->path);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testGetNonExistentFolder()
	{
		$this->assertInstanceOf('Tektonik\Plates\Template\Folder', $this->folders->get('name'));
	}
	public function testFolderExists()
	{
		$this->assertEquals($this->folders->exists('name'), false);
		$this->folders->add('name', $this->path);
		$this->assertEquals($this->folders->exists('name'), true);
	}
}

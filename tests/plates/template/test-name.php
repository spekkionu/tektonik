<?php
/**
 * Class NameTest
 *
 * @package Tektonik
 */

use Tektonik\Plates\Template\Name;

class NameTest extends WP_UnitTestCase {
	private $engine;
	private $path;
	public function setUp()
	{
		$this->path      = TEST_DATA_DIR . DIRECTORY_SEPARATOR . 'tektonik';
		$this->engine = new \Tektonik\Plates\Engine(array($this->path));
		$this->engine->add_folder('folder', $this->path . DIRECTORY_SEPARATOR . 'plugin', true);
	}
	public function testCanCreateInstance()
	{
		$this->assertInstanceOf('Tektonik\Plates\Template\Name', new Name($this->engine, 'template'));
	}
	public function testGetEngine()
	{
		$name = new Name($this->engine, 'template');
		$this->assertInstanceOf('Tektonik\Plates\Engine', $name->get_engine());
	}
	public function testGetName()
	{
		$name = new Name($this->engine, 'template');
		$this->assertEquals($name->get_name(), 'template');
	}
	public function testGetFolder()
	{
		$name = new Name($this->engine, 'folder::template');
		$folder = $name->get_folder();
		$this->assertInstanceOf('Tektonik\Plates\Template\Folder', $folder);
		$this->assertEquals($name->get_folder()->get_name(), 'folder');
	}
	public function testGetFile()
	{
		$name = new Name($this->engine, 'template');
		$this->assertEquals($name->get_file(), 'template.phtml');
	}
	public function testGetPath()
	{
		$name = new Name($this->engine, 'template');
		$this->assertEquals($name->get_path(), $this->path . DIRECTORY_SEPARATOR . 'template.phtml');
	}
	public function testGetPathWithFolder()
	{
		$name = new Name($this->engine, 'folder::template');
		$this->assertEquals($name->get_path(), $this->path . DIRECTORY_SEPARATOR.'template.phtml' );
	}
	public function testGetPathWithFolderFallback()
	{
		$name = new Name($this->engine, 'folder::fallback');
		$this->assertEquals($name->get_path(), $this->path . DIRECTORY_SEPARATOR . 'fallback.phtml');
	}
	public function testTemplateExists()
	{
		$name = new Name($this->engine, 'template');
		$this->assertEquals($name->does_path_exist(), true);
	}
	public function testTemplateDoesNotExist()
	{
		$name = new Name($this->engine, 'missing');
		$this->assertEquals($name->does_path_exist(), false);
	}
	public function testParse()
	{
		$name = new Name($this->engine, 'template');
		$this->assertEquals($name->get_name(), 'template');
		$this->assertEquals($name->get_folder(), null);
		$this->assertEquals($name->get_file(), 'template.phtml');
	}
	public function testParseWithNoDefaultDirectory()
	{
		$this->setExpectedException('LogicException', 'The default directory has not been defined.');
		$this->engine->set_directory(null);
		$name = new Name($this->engine, 'template');
		$name->get_path();
	}
	public function testParseWithEmptyTemplateName()
	{
		$this->setExpectedException('LogicException', 'The template name cannot be empty.');
		$name = new Name($this->engine, '');
	}
	public function testParseWithFolder()
	{
		$name = new Name($this->engine, 'folder::template');
		$this->assertEquals($name->get_name(), 'folder::template');
		$this->assertEquals($name->get_folder()->get_name(), 'folder');
		$this->assertEquals($name->get_file(), 'template.phtml');
	}
	public function testParseWithFolderAndEmptyTemplateName()
	{
		$this->setExpectedException('LogicException', 'The template name cannot be empty.');
		$name = new Name($this->engine, 'folder::');
	}
	public function testParseWithInvalidName()
	{
		$this->setExpectedException('LogicException', 'Do not use the folder namespace separator "::" more than once.');
		$name = new Name($this->engine, 'folder::template::wrong');
	}
	public function testParseWithNoFileExtension()
	{
		$this->engine->set_file_extension(null);
		$name = new Name($this->engine, 'template.tpl');
		$this->assertEquals($name->get_name(), 'template.tpl');
		$this->assertEquals($name->get_folder(), null);
		$this->assertEquals($name->get_file(), 'template.tpl');
	}
}

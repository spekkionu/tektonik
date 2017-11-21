<?php

use Tektonik\Plates\Engine;

/**
 * Class EngineTest
 *
 * @package Tektonik
 */

class EngineTest extends WP_UnitTestCase {
	private $path;

	private $engine;

	public function setUp() {
		$this->path   = TEST_DATA_DIR . DIRECTORY_SEPARATOR . 'tektonik';
		$this->engine = new Engine( array($this->path) );
	}

	public function testCanCreateInstance() {
		$this->assertInstanceOf( 'Tektonik\Plates\Engine', $this->engine );
	}

	public function testSetDirectory() {
		$this->assertInstanceOf( 'Tektonik\Plates\Engine', $this->engine->set_directory(array($this->path )) );
		$this->assertEquals( $this->engine->get_directory(), array($this->path) );
	}

	public function testSetNullDirectory() {
		$this->assertInstanceOf( 'Tektonik\Plates\Engine', $this->engine->set_directory( null ) );
		$this->assertEquals( $this->engine->get_directory(), null );
	}

	public function testGetDirectory() {
		$this->assertEquals( $this->engine->get_directory(), array($this->path) );
	}

	public function testSetFileExtension() {
		$this->assertInstanceOf( 'Tektonik\Plates\Engine', $this->engine->set_file_extension( 'tpl' ) );
		$this->assertEquals( $this->engine->get_file_extension(), 'tpl' );
	}

	public function testSetNullFileExtension() {
		$this->assertInstanceOf( 'Tektonik\Plates\Engine', $this->engine->set_file_extension( null ) );
		$this->assertEquals( $this->engine->get_file_extension(), null );
	}

	public function testGetFileExtension() {
		$this->assertEquals( $this->engine->get_file_extension(), 'phtml' );
	}

	public function testAddFolder() {

		$this->assertInstanceOf( 'Tektonik\Plates\Engine',
			$this->engine->add_folder( 'folder', $this->path . DIRECTORY_SEPARATOR . 'plugin' ));
		$this->assertEquals( $this->engine->get_folders()->get( 'folder' )->get_path(), $this->path . DIRECTORY_SEPARATOR . 'plugin' );
	}

	public function testAddFolderWithNamespaceConflict() {
		$this->setExpectedException( 'LogicException', 'The template folder "name" is already being used.' );
		$this->engine->add_folder( 'name', $this->path );
		$this->engine->add_folder( 'name', $this->path );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testAddFolderWithInvalidDirectory() {
		$this->engine->add_folder( 'namespace', $this->path . DIRECTORY_SEPARATOR  . 'does_not_exist' );
	}

	public function testRemoveFolder() {

		$this->engine->add_folder( 'folder', $this->path . DIRECTORY_SEPARATOR . 'plugin' );
		$this->assertEquals( $this->engine->get_folders()->exists( 'folder' ), true );
		$this->assertInstanceOf( 'Tektonik\Plates\Engine', $this->engine->remove_folder( 'folder' ) );
		$this->assertEquals( $this->engine->get_folders()->exists( 'folder' ), false );
	}

	public function testGetFolders() {
		$this->assertInstanceOf( 'Tektonik\Plates\Template\Folders', $this->engine->get_folders() );
	}

	public function testAddData() {
		$this->engine->add_data( array( 'name' => 'Jonathan' ) );
		$data = $this->engine->get_data();
		$this->assertEquals( $data['name'], 'Jonathan' );
	}

	public function testAddDataWithTemplate() {
		$this->engine->add_data( array( 'name' => 'Jonathan' ), 'template' );
		$data = $this->engine->get_data( 'template' );
		$this->assertEquals( $data['name'], 'Jonathan' );
	}

	public function testAddDataWithTemplates() {
		$this->engine->add_data( array( 'name' => 'Jonathan' ), array( 'template1', 'template2' ) );
		$data = $this->engine->get_data( 'template1' );
		$this->assertEquals( $data['name'], 'Jonathan' );
	}

	public function testRegisterFunction() {
		$this->engine->register_function( 'uppercase', 'strtoupper' );
		$this->assertInstanceOf( 'Tektonik\Plates\Template\Func', $this->engine->get_function( 'uppercase' ) );
		$this->assertEquals( $this->engine->get_function( 'uppercase' )->get_callback(), 'strtoupper' );
	}

	public function testDropFunction() {
		$this->engine->register_function( 'uppercase', 'strtoupper' );
		$this->assertEquals( $this->engine->does_function_exist( 'uppercase' ), true );
		$this->engine->drop_function( 'uppercase' );
		$this->assertEquals( $this->engine->does_function_exist( 'uppercase' ), false );
	}

	public function testDropInvalidFunction() {
		$this->setExpectedException( 'LogicException',
			'The template function "some_function_that_does_not_exist" was not found.' );
		$this->engine->drop_function( 'some_function_that_does_not_exist' );
	}

	public function testGetFunction() {
		$this->engine->register_function( 'uppercase', 'strtoupper' );
		$function = $this->engine->get_function( 'uppercase' );
		$this->assertInstanceOf( 'Tektonik\Plates\Template\Func', $function );
		$this->assertEquals( $function->get_name(), 'uppercase' );
		$this->assertEquals( $function->get_callback(), 'strtoupper' );
	}

	public function testGetInvalidFunction() {
		$this->setExpectedException( 'LogicException',
			'The template function "some_function_that_does_not_exist" was not found.' );
		$this->engine->get_function( 'some_function_that_does_not_exist' );
	}

	public function testDoesFunctionExist() {
		$this->engine->register_function( 'uppercase', 'strtoupper' );
		$this->assertEquals( $this->engine->does_function_exist( 'uppercase' ), true );
	}

	public function testDoesFunctionNotExist() {
		$this->assertEquals( $this->engine->does_function_exist( 'some_function_that_does_not_exist' ), false );
	}

	public function testLoadExtension() {
		$this->assertEquals( $this->engine->does_function_exist( 'uri' ), false );
		$this->assertInstanceOf( 'Tektonik\Plates\Engine',
			$this->engine->load_extension( new TestExtension( ) ) );
		$this->assertEquals( $this->engine->does_function_exist( 'test' ), true );
	}

	public function testLoadExtensions() {
		$this->assertEquals( $this->engine->does_function_exist( 'test' ), false );
		$this->assertInstanceOf(
			'Tektonik\Plates\Engine',
			$this->engine->load_extensions(
				array(
					new TestExtension( )
				)
			)
		);
		$this->assertEquals( $this->engine->does_function_exist( 'test' ), true );
	}

	public function testGetTemplatePath() {
		$this->assertEquals( $this->engine->path( 'template' ), $this->path . DIRECTORY_SEPARATOR . 'template.phtml' );
	}

	public function testTemplateExists() {
		$this->assertEquals( $this->engine->exists( 'does_not_exist' ), false );

		$this->assertEquals( $this->engine->exists( 'template' ), true );
	}

	public function testMakeTemplate() {
		$this->assertInstanceOf( 'Tektonik\Plates\Template\Template', $this->engine->make( 'template' ) );
	}

	public function testRenderTemplate() {
		$this->assertEquals( trim('Hello World'), trim($this->engine->render( 'layout' )) );
	}
}

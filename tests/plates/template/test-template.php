<?php
/**
 * Class TemplateTest
 *
 * @package Tektonik
 */

use Tektonik\Plates\Template\Template;

class TemplateTest extends WP_UnitTestCase {
	private $template;
	private $engine;
	private $path;
	public function setUp()
	{
		$this->path      = TEST_DATA_DIR . DIRECTORY_SEPARATOR . 'tektonik';
		$this->engine = new \Tektonik\Plates\Engine(array($this->path));
		$this->engine->register_function('uppercase', 'strtoupper');
		$this->template = new \Tektonik\Plates\Template\Template($this->engine, 'template');
	}
	public function testCanCreateInstance()
	{
		$this->assertInstanceOf('Tektonik\Plates\Template\Template', $this->template);
	}
	public function testCanCallFunction()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'upper');
		$this->assertEquals($template->render(), 'JONATHAN');
	}
	public function testAssignData()
	{
		$this->template->data(array('name' => 'Jonathan'));
		$this->assertEquals($this->template->render(), 'Hello Jonathan');
	}
	public function testGetData()
	{
		$data = array('name' => 'Jonathan');
		$this->template->data($data);
		$this->assertEquals($this->template->data(), $data);
	}
	public function testExists()
	{
		$this->assertEquals($this->template->exists(), true);
	}
	public function testDoesNotExist()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'does_not_exist');
		$this->assertEquals($template->exists(), false);
	}
	public function testGetPath()
	{
		$this->assertEquals($this->template->path(), $this->path . DIRECTORY_SEPARATOR . 'template.phtml');
	}
	public function testRenderWithData()
	{
		$this->assertEquals($this->template->render(array('name' => 'Jonathan')), 'Hello Jonathan');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testRenderDoesNotExist()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'does_not_exist');
		$template->render();
	}
	public function testRenderException()
	{
		$this->setExpectedException('Exception', 'error');
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'error');
		$template->render();
	}
	public function testLayout()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'extends');
		$this->assertEquals(trim($template->render()), 'Hello World');
	}
	public function testSection()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'section');
		$this->assertEquals($template->render(), 'Hello World');
	}
	public function testReplaceSection()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'section-replace');
		$this->assertEquals($template->render(), 'See this instead!');
	}
	public function testStartSectionWithInvalidName()
	{
		$this->setExpectedException('LogicException', 'The section name "content" is reserved.');
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'content');
		$template->render();
	}
	public function testNestSectionWithinAnotherSection()
	{
		$this->setExpectedException('LogicException', 'You cannot nest sections within other sections.');
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'nested');
		$template->render();
	}
	public function testStopSectionBeforeStarting()
	{
		$this->setExpectedException('LogicException', 'You must start a section before you can stop it.');
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'stopped');
		$template->render();
	}
	public function testSectionDefaultValue()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'section-default');
		$this->assertEquals($template->render(), 'Default value');
	}
	public function testNullSection()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'section-null');
		$this->assertEquals($template->render(), 'NULL');
	}
	public function testPushSection()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'section-push');
		$this->assertEquals(trim($template->render()), '<script src="example1.js"></script><script src="example2.js"></script>');
	}
	public function testPushWithMultipleSections()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'section-multiple');
		$this->assertEquals(trim($template->render()), 'test<script src="example1.js"></script><script src="example2.js"></script>');
	}
	public function testFetchFunction()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'fetched');
		$this->assertEquals(trim($template->render()), 'Hello World');
	}
	public function testInsertFunction()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'insert');
		$this->assertEquals(trim($template->render()), 'Hello World');
	}
	public function testBatchFunction()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'batch');
		$this->assertEquals($template->render(), 'jonathan');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testBatchFunctionWithInvalidFunction()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'batch-error');
		$template->render();
	}
	public function testEscapeFunction()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'escape');
		$this->assertEquals($template->render(), '&lt;strong&gt;Jonathan&lt;/strong&gt;');
	}
	public function testEscapeFunctionBatch()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'escape-batch');
		$this->assertEquals($template->render(), '&gt;GNORTS/&lt;NAHTANOJ&gt;GNORTS&lt;');
	}
	public function testEscapeShortcutFunction()
	{
		$template = new \Tektonik\Plates\Template\Template($this->engine, 'escape-short');
		$this->assertEquals($template->render(), '&lt;strong&gt;Jonathan&lt;/strong&gt;');
	}
}

<?php
/**
 * Class FunctionsTest
 *
 * @package Tektonik
 */

use Tektonik\Plates\Template\Functions;

class FunctionsTest extends WP_UnitTestCase {
	private $functions;
	public function setUp()
	{
		$this->functions = new Functions();
	}
	public function testCanCreateInstance()
	{
		$this->assertInstanceOf('Tektonik\Plates\Template\Functions', $this->functions);
	}
	public function testAddAndGetFunction()
	{
		$this->assertInstanceOf('Tektonik\Plates\Template\Functions', $this->functions->add('upper', 'strtoupper'));
		$this->assertEquals($this->functions->get('upper')->get_callback(), 'strtoupper');
	}
	public function testAddFunctionConflict()
	{
		$this->setExpectedException('LogicException', 'The template function name "upper" is already registered.');
		$this->functions->add('upper', 'strtoupper');
		$this->functions->add('upper', 'strtoupper');
	}
	public function testGetNonExistentFunction()
	{
		$this->setExpectedException('LogicException', 'The template function "foo" was not found.');
		$this->functions->get('foo');
	}
	public function testRemoveFunction()
	{
		$this->functions->add('upper', 'strtoupper');
		$this->assertEquals($this->functions->exists('upper'), true);
		$this->functions->remove('upper');
		$this->assertEquals($this->functions->exists('upper'), false);
	}
	public function testRemoveNonExistentFunction()
	{
		$this->setExpectedException('LogicException', 'The template function "foo" was not found.');
		$this->functions->remove('foo');
	}
	public function testFunctionExists()
	{
		$this->assertEquals($this->functions->exists('upper'), false);
		$this->functions->add('upper', 'strtoupper');
		$this->assertEquals($this->functions->exists('upper'), true);
	}
}

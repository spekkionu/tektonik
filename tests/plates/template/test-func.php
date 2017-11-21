<?php
/**
 * Class FuncTest
 *
 * @package Tektonik
 */

use Tektonik\Plates\Template\Func;

class FuncTest extends WP_UnitTestCase {
	private $function;
	public function setUp()
	{
		$this->function = new Func('uppercase', function ($string) {
			return strtoupper($string);
		});
	}
	public function testCanCreateInstance()
	{
		$this->assertInstanceOf('Tektonik\Plates\Template\Func', $this->function);
	}
	public function testSetAndGetName()
	{
		$this->assertInstanceOf('Tektonik\Plates\Template\Func', $this->function->set_name('test'));
		$this->assertEquals($this->function->get_name(), 'test');
	}
	public function testSetInvalidName()
	{
		$this->setExpectedException('LogicException', 'Not a valid function name.');
		$this->function->set_name('invalid-function-name');
	}
	public function testSetAndGetCallback()
	{
		$this->assertInstanceOf('Tektonik\Plates\Template\Func', $this->function->set_callback('strtolower'));
		$this->assertEquals($this->function->get_callback(), 'strtolower');
	}
	public function testSetInvalidCallback()
	{
		$this->setExpectedException('LogicException', 'Not a valid function callback.');
		$this->function->set_callback(null);
	}
	public function testFunctionCall()
	{
		$this->assertEquals($this->function->call(null, array('Jonathan')), 'JONATHAN');
	}
	public function testExtensionFunctionCall()
	{
		$extension = $this->getMockBuilder('Tektonik\Plates\Extension\ExtensionInterface')
		                  ->setMethods(array('register', 'foo'))
		                  ->getMock();
		$extension->method('foo')->willReturn('bar');
		$this->function->set_callback(array($extension, 'foo'));
		$this->assertEquals($this->function->call(null), 'bar');
	}
}

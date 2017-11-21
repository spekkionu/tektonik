<?php
/**
 * Class DataTest
 *
 * @package Tektonik
 */

use Tektonik\Plates\Template\Data;

/**
 * Sample test case.
 */
class DataTest extends WP_UnitTestCase {

	private $template_data;

	public function setUp()
	{
		parent::setUp();
		$this->template_data = new Data();
	}

	public function test_instance() {
		$this->assertInstanceOf( 'Tektonik\\Plates\\Template\\Data', $this->template_data );
	}
	public function test_add_data_to_all_templates()
	{
		$this->template_data->add(array('name' => 'Jonathan'));
		$data = $this->template_data->get();
		$this->assertEquals($data['name'], 'Jonathan');
	}
	public function test_add_data_to_one_template()
	{
		$this->template_data->add(array('name' => 'Jonathan'), 'template');
		$data = $this->template_data->get('template');
		$this->assertEquals($data['name'], 'Jonathan');
	}
	public function test_add_data_to_one_template_again()
	{
		$this->template_data->add(array('firstname' => 'Jonathan'), 'template');
		$this->template_data->add(array('lastname' => 'Reinink'), 'template');
		$data = $this->template_data->get('template');
		$this->assertEquals($data['lastname'], 'Reinink');
	}
	public function test_add_data_to_some_templates()
	{
		$this->template_data->add(array('name' => 'Jonathan'), array('template1', 'template2'));
		$data = $this->template_data->get('template1');
		$this->assertEquals($data['name'], 'Jonathan');
	}
	public function test_add_data_with_invalid_template_file_fype()
	{
		$this->setExpectedException('InvalidArgumentException', 'The templates variable must be null, an array or a string, integer given.');
		$this->template_data->add(array('name' => 'Jonathan'), 123);
	}
}

<?php
/**
 * Class ParamsTest
 *
 * @package Tektonik
 */

use Tektonik\Plates\Template\Params;

class ParamsTest extends WP_UnitTestCase {
	/** @var  Params */
	private $params;

	public function setUp() {
		$this->params = new Params();
	}

	public function test_constructor() {
		$this->assertInstanceOf( 'Tektonik\Plates\Template\Params', $this->params );
	}

	public function test_getter_and_setter() {
		$this->params->set( 'name', 'Bob' );
		$this->assertEquals( 'Bob', $this->params->get( 'name' ) );
	}

	public function test_using_like_array() {
		$this->params['name'] = 'Bob';
		$this->assertEquals( 'Bob', $this->params['name'] );
		unset( $this->params['name'] );
		$this->assertFalse( isset( $this->params['name'] ) );
	}

	public function test_checking_variable_existance() {
		$this->assertFalse( $this->params->exists( 'name' ) );
		$this->params->set( 'name', 'Bob' );
		$this->assertTrue( $this->params->exists( 'name' ) );
	}

	public function test_removing_variable() {
		$this->params->set( 'name', 'Bob' );
		$this->assertTrue( $this->params->exists( 'name' ) );
		$this->params->remove( 'name' );
		$this->assertFalse( $this->params->exists( 'name' ) );
	}

	public function test_counting_variables() {
		$this->params->set( 'name', 'Bob' );
		$this->params->set( 'age', 32 );
		$this->assertEquals( 2, $this->params->count() );
		$this->assertEquals( 2, count( $this->params ) );
	}

	public function test_replacing_all_params() {
		$this->params->set( 'name', 'Bob' );
		$this->params->set( 'age', 32 );
		$this->params->replace( array(
			'name' => 'Steve',
			'age'  => 53,
		) );
		$this->assertEquals( 'Steve', $this->params->get( 'name' ) );
		$this->assertEquals( 53, $this->params->get( 'age' ) );
	}

	public function test_returning_all_params() {
		$this->params->set( 'name', 'Bob' );
		$this->params->set( 'age', 32 );
		$this->assertArraySubset( array( 'name' => 'Bob', 'age' => 32 ), $this->params->all() );
		$this->assertCount( 2, $this->params->all() );
	}
}

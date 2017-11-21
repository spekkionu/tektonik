<?php

use Tektonik\Plates\Engine;
use Tektonik\Plates\Extension\ExtensionInterface;

class TestExtension implements ExtensionInterface {

	/**
	 * Registers the extension.
	 *
	 * @param Engine $engine The engine instance.
	 *
	 * @return void
	 */
	public function register( Engine $engine ) {
		$engine->register_function('test', array($this, 'run_test'));
	}

	/**
	 * @param $var
	 *
	 * @return bool
	 */
	public function run_test( $var ) {
		return true;
	}
}

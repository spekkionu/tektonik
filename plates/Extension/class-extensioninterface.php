<?php
/**
 * Plates extension interface
 *
 * @package Tektonik
 */

namespace Tektonik\Plates\Extension;

use Tektonik\Plates\Engine;

/**
 * A common interface for extensions.
 */
interface ExtensionInterface {

	/**
	 * Registers the extension.
	 *
	 * @param Engine $engine The engine instance.
	 *
	 * @return void
	 */
	public function register( Engine $engine);
}

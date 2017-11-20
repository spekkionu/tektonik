<?php
/**
 * Function class
 *
 * @package Tektonik
 */

namespace Tektonik\Plates\Template;

use Tektonik\Plates\Extension\ExtensionInterface;
use InvalidArgumentException;

/**
 * A template function.
 */
class Func {
	/**
	 * The function name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The function callback.
	 *
	 * @var callable
	 */
	protected $callback;

	/**
	 * Create new Func instance.
	 *
	 * @param string   $name The function name.
	 * @param callable $callback The function callback.
	 */
	public function __construct( $name, $callback ) {
		$this->set_name( $name );
		$this->set_callback( $callback );
	}

	/**
	 * Set the function name.
	 *
	 * @param  string $name The function name.
	 *
	 * @return Func
	 * @throws InvalidArgumentException When function name is not valid.
	 */
	public function set_name( $name ) {
		if ( preg_match( '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $name ) !== 1 ) {
			throw new InvalidArgumentException(
				'Not a valid function name.'
			);
		}

		$this->name = $name;

		return $this;
	}

	/**
	 * Get the function name.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Set the function callback
	 *
	 * @param  callable $callback The function callback.
	 *
	 * @return Func
	 * @throws InvalidArgumentException When callback is not callable.
	 */
	public function set_callback( $callback ) {
		if ( ! is_callable( $callback, true ) ) {
			throw new InvalidArgumentException(
				'Not a valid function callback.'
			);
		}

		$this->callback = $callback;

		return $this;
	}

	/**
	 * Get the function callback.
	 *
	 * @return callable
	 */
	public function get_callback() {
		return $this->callback;
	}

	/**
	 * Call the function.
	 *
	 * @param  Template $template The template that is calling the function.
	 * @param  array    $arguments The arguments to pass to the function.
	 *
	 * @return mixed
	 */
	public function call( Template $template = null, array $arguments = [] ) {
		if ( is_array( $this->callback ) && isset( $this->callback[0] ) && $this->callback[0] instanceof ExtensionInterface ) {
			$this->callback[0]->template = $template;
		}

		return call_user_func_array( $this->callback, $arguments );
	}
}

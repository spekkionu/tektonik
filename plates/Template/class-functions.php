<?php
/**
 * Function collection class
 *
 * @package Tektonik
 */

namespace Tektonik\Plates\Template;

use InvalidArgumentException;

/**
 * A collection of template functions.
 */
class Functions {

	/**
	 * Array of template functions.
	 *
	 * @var array
	 */
	protected $functions = array();

	/**
	 * Add a new template function.
	 *
	 * @param  string   $name     The function name.
	 * @param  callback $callback The function callback.
	 *
	 * @return Functions
	 * @throws InvalidArgumentException When the function is already registered.
	 */
	public function add( $name, $callback ) {
		if ( $this->exists( $name ) ) {
			throw new InvalidArgumentException(
				'The template function name "' . $name . '" is already registered.'
			);
		}

		$this->functions[ $name ] = new Func( $name, $callback );

		return $this;
	}

	/**
	 * Remove a template function.
	 *
	 * @param  string $name The function name.
	 *
	 * @return Functions
	 * @throws InvalidArgumentException When the function was not registered.
	 */
	public function remove( $name ) {
		if ( ! $this->exists( $name ) ) {
			throw new InvalidArgumentException(
				'The template function "' . $name . '" was not found.'
			);
		}

		unset( $this->functions[ $name ] );

		return $this;
	}

	/**
	 * Get a template function.
	 *
	 * @param  string $name The function name.
	 *
	 * @return Func
	 * @throws InvalidArgumentException When the function was not registered.
	 */
	public function get( $name ) {
		if ( ! $this->exists( $name ) ) {
			throw new InvalidArgumentException( 'The template function "' . $name . '" was not found.' );
		}

		return $this->functions[ $name ];
	}

	/**
	 * Check if a template function exists.
	 *
	 * @param  string $name The function name.
	 * @return boolean
	 */
	public function exists( $name ) {
		return isset( $this->functions[ $name ] );
	}
}

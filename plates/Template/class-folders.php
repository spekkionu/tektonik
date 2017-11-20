<?php
/**
 * Folder collection class
 *
 * @package Tektonik
 */

namespace Tektonik\Plates\Template;

use InvalidArgumentException;

/**
 * A collection of template folders.
 */
class Folders {

	/**
	 * Array of template folders.
	 *
	 * @var array
	 */
	protected $folders = array();

	/**
	 * Add a template folder.
	 *
	 * @param  string  $name The folder name.
	 * @param  string  $path The path to the folder.
	 * @param  boolean $fallback Folder fallback status.
	 *
	 * @return Folders
	 * @throws InvalidArgumentException When trying to add a folder that already exists.
	 */
	public function add( $name, $path, $fallback = false ) {
		if ( $this->exists( $name ) ) {
			throw new InvalidArgumentException( 'The template folder "' . $name . '" is already being used.' );
		}

		$this->folders[ $name ] = new Folder( $name, $path, $fallback );

		return $this;
	}

	/**
	 * Remove a template folder.
	 *
	 * @param  string $name The folder name.
	 *
	 * @return Folders
	 * @throws InvalidArgumentException When the folder does not exist.
	 */
	public function remove( $name ) {
		if ( ! $this->exists( $name ) ) {
			throw new InvalidArgumentException( 'The template folder "' . $name . '" was not found.' );
		}

		unset( $this->folders[ $name ] );

		return $this;
	}

	/**
	 * Get a template folder.
	 *
	 * @param  string $name The folder name.
	 *
	 * @return Folder
	 * @throws InvalidArgumentException When the folder does not exist.
	 */
	public function get( $name ) {
		if ( ! $this->exists( $name ) ) {
			throw new InvalidArgumentException( 'The template folder "' . $name . '" was not found.' );
		}

		return $this->folders[ $name ];
	}

	/**
	 * Check if a template folder exists.
	 *
	 * @param  string $name The folder name.
	 * @return boolean
	 */
	public function exists( $name ) {
		return isset( $this->folders[ $name ] );
	}
}

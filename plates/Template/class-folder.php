<?php
/**
 * Folder class
 *
 * @package Tektonik
 */

namespace Tektonik\Plates\Template;

use InvalidArgumentException;

/**
 * A template folder.
 */
class Folder {

	/**
	 * The folder name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The folder path.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * The folder fallback status.
	 *
	 * @var boolean
	 */
	protected $fallback;

	/**
	 * Create a new Folder instance.
	 *
	 * @param string  $name The folder name.
	 * @param string  $path The path to the folder.
	 * @param boolean $fallback The folder fallback status.
	 */
	public function __construct( $name, $path, $fallback = false ) {
		$this->set_name( $name );
		$this->set_path( $path );
		$this->set_fallback( $fallback );
	}

	/**
	 * Set the folder name.
	 *
	 * @param  string $name The folder name.
	 * @return Folder
	 */
	public function set_name( $name ) {
		$this->name = $name;

		return $this;
	}

	/**
	 * Get the folder name.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Set the folder path.
	 *
	 * @param  string $path The path to the folder.
	 *
	 * @return Folder
	 * @throws InvalidArgumentException When the path does not exist.
	 */
	public function set_path( $path ) {
		if ( ! is_dir( $path ) ) {
			throw new InvalidArgumentException( 'The specified directory path "' . $path . '" does not exist.' );
		}

		$this->path = $path;

		return $this;
	}

	/**
	 * Get the folder path.
	 *
	 * @return string
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * Set the folder fallback status.
	 *
	 * @param  boolean $fallback The folder fallback status.
	 * @return Folder
	 */
	public function set_fallback( $fallback ) {
		$this->fallback = $fallback;

		return $this;
	}

	/**
	 * Get the folder fallback status.
	 *
	 * @return boolean
	 */
	public function get_fallback() {
		return $this->fallback;
	}
}

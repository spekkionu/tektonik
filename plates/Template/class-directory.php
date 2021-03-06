<?php
/**
 * Directory class
 *
 * @package Tektonik
 */

namespace Tektonik\Plates\Template;

/**
 * Default template directory.
 */
class Directory {

	/**
	 * Template directory path.
	 *
	 * @var array
	 */
	protected $paths;

	/**
	 * Create new Directory instance.
	 *
	 * @param array $paths Pass null to disable the default directory.
	 */
	public function __construct( array $paths = null ) {
		$this->set( $paths );
	}

	/**
	 * Set path to templates directory.
	 *
	 * @param  array|null $paths Pass null to disable the default directory.
	 * @return Directory
	 */
	public function set( array $paths = null ) {
		$this->paths = $paths;

		return $this;
	}

	/**
	 * Get path to templates directory.
	 *
	 * @return array
	 */
	public function get() {
		return $this->paths;
	}
}

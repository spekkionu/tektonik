<?php
/**
 * Template name class
 *
 * @package Tektonik
 */

namespace Tektonik\Plates\Template;

use Tektonik\Plates\Engine;
use InvalidArgumentException;

/**
 * A template name.
 */
class Name {
	/**
	 * Instance of the template engine.
	 *
	 * @var Engine
	 */
	protected $engine;

	/**
	 * The original name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The parsed template folder.
	 *
	 * @var Folder
	 */
	protected $folder;

	/**
	 * The parsed template filename.
	 *
	 * @var string
	 */
	protected $file;

	/**
	 * Create a new Name instance.
	 *
	 * @param Engine $engine The engine instance.
	 * @param string $name The template name.
	 */
	public function __construct( Engine $engine, $name ) {
		$this->set_engine( $engine );
		$this->set_name( $name );
	}

	/**
	 * Set the engine.
	 *
	 * @param  Engine $engine The engine instance.
	 *
	 * @return Name
	 */
	public function set_engine( Engine $engine ) {
		$this->engine = $engine;

		return $this;
	}

	/**
	 * Get the engine.
	 *
	 * @return Engine
	 */
	public function get_engine() {
		return $this->engine;
	}

	/**
	 * Set the original name and parse it.
	 *
	 * @param  string $name The template name.
	 *
	 * @return Name
	 * @throws InvalidArgumentException When the template name is invalid.
	 */
	public function set_name( $name ) {
		$this->name = $name;

		$parts = explode( '::', $this->name );

		if ( count( $parts ) === 1 ) {
			$this->set_file( $parts[0] );
		} elseif ( count( $parts ) === 2 ) {
			$this->set_folder( $parts[0] );
			$this->set_file( $parts[1] );
		} else {
			throw new InvalidArgumentException(
				'The template name "' . $this->name . '" is not valid. ' .
				'Do not use the folder namespace separator "::" more than once.'
			);
		}

		return $this;
	}

	/**
	 * Get the original name.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Set the parsed template folder.
	 *
	 * @param  string $folder The folder name.
	 *
	 * @return Name
	 */
	public function set_folder( $folder ) {
		$this->folder = $this->engine->get_folders()->get( $folder );

		return $this;
	}

	/**
	 * Get the parsed template folder.
	 *
	 * @return string
	 */
	public function get_folder() {
		return $this->folder;
	}

	/**
	 * Set the parsed template file.
	 *
	 * @param  string $file The template file.
	 *
	 * @return Name
	 * @throws InvalidArgumentException When no template name is provided.
	 */
	public function set_file( $file ) {
		if ( '' === $file ) {
			throw new InvalidArgumentException(
				'The template name "' . $this->name . '" is not valid. ' .
				'The template name cannot be empty.'
			);
		}

		$this->file = $file;

		if ( null !== $this->engine->get_file_extension() ) {
			$this->file .= '.' . $this->engine->get_file_extension();
		}

		return $this;
	}

	/**
	 * Get the parsed template file.
	 *
	 * @return string
	 */
	public function get_file() {
		return $this->file;
	}

	/**
	 * Resolve template path.
	 *
	 * @return string
	 */
	public function get_path() {
		if ( null === $this->folder ) {
			foreach ( $this->get_default_directory() as $directory ) {
				$path = $directory . DIRECTORY_SEPARATOR . $this->file;
				if ( is_file( $path ) ) {
					return $path;
				}
			}
			$directories = $this->get_default_directory();
			$directory   = array_shift( $directories );

			return $directory . DIRECTORY_SEPARATOR . $this->file;

		}
		foreach ( $this->get_default_directory() as $directory ) {
			$path = $directory . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . $this->folder->get_name() . DIRECTORY_SEPARATOR . $this->file;
			if ( is_file( $path ) ) {
				return $path;
			}
		}

		$path = $this->folder->get_path() . DIRECTORY_SEPARATOR . $this->file;

		if ( ! is_file( $path ) && $this->folder->get_fallback() ) {
			foreach ( $this->get_default_directory() as $directory ) {
				$path = $directory . DIRECTORY_SEPARATOR . $this->file;
				if ( is_file( $path ) ) {
					return $path;
				}
			}
		}

		return $path;

	}

	/**
	 * Check if template path exists.
	 *
	 * @return boolean
	 */
	public function does_path_exist() {
		return is_file( $this->get_path() );
	}

	/**
	 * Get the default templates directory.
	 *
	 * @return array
	 * @throws InvalidArgumentException When default directory is not defined.
	 */
	protected function get_default_directory() {
		$directory = $this->engine->get_directory();

		if ( null === $directory ) {
			throw new InvalidArgumentException(
				'The template name "' . $this->name . '" is not valid. ' .
				'The default directory has not been defined.'
			);
		}

		return $directory;
	}
}

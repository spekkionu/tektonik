<?php
/**
 * Plates engine
 *
 * @package Tektonik
 */

namespace Tektonik\Plates;

use Tektonik\Plates\Extension\ExtensionInterface;
use Tektonik\Plates\Template\Data;
use Tektonik\Plates\Template\Directory;
use Tektonik\Plates\Template\Folders;
use Tektonik\Plates\Template\Func;
use Tektonik\Plates\Template\Functions;
use Tektonik\Plates\Template\Name;
use Tektonik\Plates\Template\Template;

/**
 * Template API and environment settings storage.
 */
class Engine {
	/**
	 * Default template directory.
	 *
	 * @var Directory
	 */
	protected $directory;

	/**
	 * Template file extension.
	 *
	 * @var string
	 */
	protected $file_extension;

	/**
	 * Collection of template folders.
	 *
	 * @var Folders
	 */
	protected $folders;

	/**
	 * Collection of template functions.
	 *
	 * @var Functions
	 */
	protected $functions;

	/**
	 * Collection of preassigned template data.
	 *
	 * @var Data
	 */
	protected $data;

	/**
	 * Create new Engine instance.
	 *
	 * @param array  $directory      The directory or array of directories to look for templates.
	 * @param string $file_extension The file extension for templates.
	 */
	public function __construct( array $directory = null, $file_extension = 'phtml' ) {
		$this->directory      = new Directory( $directory );
		$this->file_extension = $file_extension;
		$this->folders        = new Folders();
		$this->functions      = new Functions();
		$this->data           = new Data();
	}

	/**
	 * Set path to templates directory.
	 *
	 * @param  array|null $directory Pass null to disable the default directory.
	 *
	 * @return Engine
	 */
	public function set_directory( array $directory = null ) {
		$this->directory->set( $directory );

		return $this;
	}

	/**
	 * Get path to templates directory.
	 *
	 * @return array
	 */
	public function get_directory() {
		return $this->directory->get();
	}

	/**
	 * Set the template file extension.
	 *
	 * @param  string|null $file_extension Pass null to manually set it.
	 *
	 * @return Engine
	 */
	public function set_file_extension( $file_extension ) {
		$this->file_extension = $file_extension;

		return $this;
	}

	/**
	 * Get the template file extension.
	 *
	 * @return string
	 */
	public function get_file_extension() {
		return $this->file_extension;
	}

	/**
	 * Add a new template folder for grouping templates under different namespaces.
	 *
	 * @param  string  $name The folder namespace.
	 * @param  string  $directory The path to the folder.
	 * @param  boolean $fallback The folder fallback status.
	 *
	 * @return Engine
	 */
	public function add_folder( $name, $directory, $fallback = false ) {
		$this->folders->add( $name, $directory, $fallback );

		return $this;
	}

	/**
	 * Remove a template folder.
	 *
	 * @param  string $name The folder namespace.
	 *
	 * @return Engine
	 */
	public function remove_folder( $name ) {
		$this->folders->remove( $name );

		return $this;
	}

	/**
	 * Get collection of all template folders.
	 *
	 * @return Folders
	 */
	public function get_folders() {
		return $this->folders;
	}

	/**
	 * Add preassigned template data.
	 *
	 * @param  array             $data The data to set.
	 * @param  null|string|array $templates The templates to add data to.
	 *
	 * @return Engine
	 */
	public function add_data( array $data, $templates = null ) {
		$this->data->add( $data, $templates );

		return $this;
	}

	/**
	 * Get all preassigned template data.
	 *
	 * @param  null|string $template The template to get the data for.
	 *
	 * @return array
	 */
	public function get_data( $template = null ) {
		return $this->data->get( $template );
	}

	/**
	 * Register a new template function.
	 *
	 * @param  string   $name     The function name.
	 * @param  callback $callback The function callback.
	 *
	 * @return Engine
	 */
	public function register_function( $name, $callback ) {
		$this->functions->add( $name, $callback );

		return $this;
	}

	/**
	 * Remove a template function.
	 *
	 * @param  string $name The name of the function.
	 *
	 * @return Engine
	 */
	public function drop_function( $name ) {
		$this->functions->remove( $name );

		return $this;
	}

	/**
	 * Get a template function.
	 *
	 * @param  string $name The name of the function.
	 *
	 * @return Func
	 */
	public function get_function( $name ) {
		return $this->functions->get( $name );
	}

	/**
	 * Check if a template function exists.
	 *
	 * @param  string $name The name of the function.
	 *
	 * @return boolean
	 */
	public function does_function_exist( $name ) {
		return $this->functions->exists( $name );
	}

	/**
	 * Load an extension.
	 *
	 * @param  ExtensionInterface $extension The extention to register.
	 *
	 * @return Engine
	 */
	public function load_extension( ExtensionInterface $extension ) {
		$extension->register( $this );

		return $this;
	}

	/**
	 * Load multiple extensions.
	 *
	 * @param  array $extensions The extensions to load.
	 *
	 * @return Engine
	 */
	public function load_extensions( array $extensions = array() ) {
		foreach ( $extensions as $extension ) {
			$this->load_extension( $extension );
		}

		return $this;
	}

	/**
	 * Get a template path.
	 *
	 * @param  string $name The name of the template.
	 *
	 * @return string
	 */
	public function path( $name ) {
		$name = new Name( $this, $name );

		return $name->get_path();
	}

	/**
	 * Check if a template exists.
	 *
	 * @param  string $name The name of the template.
	 *
	 * @return boolean
	 */
	public function exists( $name ) {
		$name = new Name( $this, $name );

		return $name->does_path_exist();
	}

	/**
	 * Create a new template.
	 *
	 * @param  string $name The name of the template.
	 *
	 * @return Template
	 */
	public function make( $name ) {
		return new Template( $this, $name );
	}

	/**
	 * Create a new template and render it.
	 *
	 * @param  string $name The template to render.
	 * @param  array  $data The params to pass to the template.
	 *
	 * @return string
	 */
	public function render( $name, array $data = array() ) {
		return $this->make( $name )->render( $data );
	}
}

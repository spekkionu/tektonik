<?php
/**
 * Template class
 *
 * @package Tektonik
 */

namespace Tektonik\Plates\Template;

use Exception;
use Tektonik\Plates\Engine;
use InvalidArgumentException;
use Throwable;

/**
 * Container which holds template data and provides access to template functions.
 */
class Template {

	/**
	 * Instance of the template engine.
	 *
	 * @var Engine
	 */
	protected $engine;

	/**
	 * The name of the template.
	 *
	 * @var Name
	 */
	protected $name;

	/**
	 * The data assigned to the template.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * An array of section content.
	 *
	 * @var array
	 */
	protected $sections = array();

	/**
	 * The name of the section currently being rendered.
	 *
	 * @var string
	 */
	protected $section_name;

	/**
	 * Whether the section should be appended or not.
	 *
	 * @var boolean
	 */
	protected $append_section;

	/**
	 * The name of the template layout.
	 *
	 * @var string
	 */
	protected $layout_name;

	/**
	 * The data assigned to the template layout.
	 *
	 * @var array
	 */
	protected $layout_data;

	/**
	 * Create new Template instance.
	 *
	 * @param Engine $engine The engine instance.
	 * @param string $name   The template name.
	 */
	public function __construct( Engine $engine, $name ) {
		$this->engine = $engine;
		$this->name   = new Name( $engine, $name );

		$this->data( $this->engine->get_data( $name ) );
	}

	/**
	 * Magic method used to call extension functions.
	 *
	 * @param  string $name      The function name.
	 * @param  array  $arguments The function arguments.
	 *
	 * @return mixed
	 */
	public function __call( $name, $arguments ) {
		return $this->engine->get_function( $name )->call( $this, $arguments );
	}

	/**
	 * Assign or get template data.
	 *
	 * @param  array $data The variables to assign.
	 *
	 * @return mixed
	 */
	public function data( array $data = null ) {
		if ( null === $data ) {
			return $this->data;
		}

		$this->data = array_merge( $this->data, $data );
	}

	/**
	 * Check if the template exists.
	 *
	 * @return boolean
	 */
	public function exists() {
		return $this->name->does_path_exist();
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function path() {
		return $this->name->get_path();
	}

	/**
	 * Render the template and layout.
	 *
	 * @param  array $data The vatiables to assign.
	 *
	 * @throws Throwable When an error is raised inside the template.
	 * @throws Exception When an exception is thrown inside the template.
	 * @throws InvalidArgumentException When template cannot be found.
	 * @return string
	 */
	public function render( array $data = array() ) {
		$this->data( $data );
		unset( $data );
		extract( $this->data, EXTR_OVERWRITE );

		if ( ! $this->exists() ) {
			throw new InvalidArgumentException(
				'The template "' . $this->name->get_name() . '" could not be found at "' . $this->path() . '".'
			);
		}

		try {
			$level = ob_get_level();
			ob_start();

			include $this->path();

			$content = ob_get_clean();

			if ( isset( $this->layout_name ) ) {
				$layout           = $this->engine->make( $this->layout_name );
				$layout->sections = array_merge( $this->sections, array( 'content' => $content ) );
				$content          = $layout->render( array_merge( $this->data, $this->layout_data ) );
			}

			return $content;
		} catch ( Throwable $e ) {
			while ( ob_get_level() > $level ) {
				ob_end_clean();
			}

			throw $e;
		} catch ( Exception $e ) {
			while ( ob_get_level() > $level ) {
				ob_end_clean();
			}

			throw $e;
		}
	}

	/**
	 * Set the template's layout.
	 *
	 * @param  string $name The layout name.
	 * @param  array  $data The variables to pass to the layout.
	 */
	public function layout( $name, array $data = array() ) {
		$this->layout_name = $name;
		$this->layout_data = $data;
	}

	/**
	 * Start a new section block.
	 *
	 * @param  string $name Section name.
	 *
	 * @throws InvalidArgumentException When already inside a section.
	 */
	public function start( $name ) {
		if ( 'content' === $name ) {
			throw new InvalidArgumentException(
				'The section name "content" is reserved.'
			);
		}

		if ( $this->section_name ) {
			throw new InvalidArgumentException( 'You cannot nest sections within other sections.' );
		}

		$this->section_name = $name;

		ob_start();
	}

	/**
	 * Start a new append section block.
	 *
	 * @param  string $name The section name.
	 */
	public function push( $name ) {
		$this->append_section = true;

		$this->start( $name );
	}

	/**
	 * Stop the current section block.
	 *
	 * @throws InvalidArgumentException When the section is not started.
	 */
	public function stop() {
		if ( null === $this->section_name ) {
			throw new InvalidArgumentException(
				'You must start a section before you can stop it.'
			);
		}

		if ( ! isset( $this->sections[ $this->section_name ] ) ) {
			$this->sections[ $this->section_name ] = '';
		}

		$this->sections[ $this->section_name ] = $this->append_section ? $this->sections[ $this->section_name ] . ob_get_clean() : ob_get_clean();
		$this->section_name                    = null;
		$this->append_section                  = false;
	}

	/**
	 * Alias of stop().
	 */
	public function end() {
		$this->stop();
	}

	/**
	 * Returns the content for a section block.
	 *
	 * @param  string $name    Section name.
	 * @param  string $default Default section content.
	 *
	 * @return string|null
	 */
	public function section( $name, $default = null ) {
		if ( ! isset( $this->sections[ $name ] ) ) {
			return $default;
		}

		return $this->sections[ $name ];
	}

	/**
	 * Fetch a rendered template.
	 *
	 * @param  string $name The template name.
	 * @param  array  $data The variables to pass to the template.
	 *
	 * @return string
	 */
	public function fetch( $name, array $data = array() ) {
		return $this->engine->render( $name, array_merge( $this->data, $data ) );
	}

	/**
	 * Output a rendered template.
	 *
	 * @param  string $name The partial name.
	 * @param  array  $data The variables to pass to the partial.
	 */
	public function insert( $name, array $data = array() ) {
		echo $this->fetch( $name, $data );
	}

	/**
	 * Apply multiple functions to variable.
	 *
	 * @param  mixed  $var The variable.
	 * @param  string $functions The functions to apply to the variable.
	 *
	 * @return mixed
	 * @throws InvalidArgumentException When the function cannot be found.
	 */
	public function batch( $var, $functions ) {
		foreach ( explode( '|', $functions ) as $function ) {
			if ( $this->engine->does_function_exist( $function ) ) {
				$var = call_user_func( [ $this, $function ], $var );
			} elseif ( is_callable( $function ) ) {
				$var = call_user_func( $function, $var );
			} else {
				throw new InvalidArgumentException(
					'The batch function could not find the "' . $function . '" function.'
				);
			}
		}

		return $var;
	}

	/**
	 * Escape string.
	 *
	 * @param  string      $string The string to escape.
	 * @param  null|string $functions Additional functions to batch.
	 *
	 * @return string
	 */
	public function escape( $string, $functions = null ) {
		if ( $functions ) {
			$string = $this->batch( $string, $functions );
		}

		return esc_html( $string );
	}

	/**
	 * Alias to escape function.
	 *
	 * @param  string      $string The string to escape.
	 * @param  null|string $functions Additional functions to batch.
	 *
	 * @return string
	 */
	public function e( $string, $functions = null ) {
		return $this->escape( $string, $functions );
	}

	/**
	 * Returns template name.
	 *
	 * @return Name
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Replaces template name.
	 *
	 * @param string $name The template name.
	 */
	public function replace_name( $name ) {
		$this->name = new Name( $this->engine, $name );
	}
}

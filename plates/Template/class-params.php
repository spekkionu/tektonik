<?php
/**
 * Template parameters class class
 *
 * @package Tektonik
 */

namespace Tektonik\Plates\Template;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Serializable;
use Traversable;

/**
 * Class Params
 *
 * @package Tektonik\Plates\Template
 */
class Params implements ArrayAccess, Countable, IteratorAggregate, Serializable {
	/**
	 * The parameter variables.
	 *
	 * @var array
	 */
	private $params;

	/**
	 * Params constructor.
	 *
	 * @param array $params The array of parameters.
	 */
	public function __construct( array $params = array() ) {
		$this->params = $params;
	}

	/**
	 * Returns a single parameter value
	 *
	 * @param string $name The variable name.
	 *
	 * @return array
	 */
	public function get( $name ) {
		return $this->offsetGet( $name );
	}

	/**
	 * Sets a single parameter value
	 *
	 * @param string $name The variable name.
	 * @param mixed  $value The variable value.
	 */
	public function set( $name, $value ) {
		$this->offsetSet( $name, $value );
	}

	/**
	 * Removes a variable.
	 *
	 * @param string $name The variable name.
	 */
	public function remove( $name ) {
		$this->offsetUnset( $name );
	}

	/**
	 * Checks if a variable exists.
	 *
	 * @param string $name The variable name.
	 *
	 * @return bool
	 */
	public function exists( $name ) {
		return $this->offsetExists( $name );
	}

	/**
	 * Returns all parameter values.
	 *
	 * @return array
	 */
	public function all() {
		return $this->params;
	}

	/**
	 * Replaces all parameter values
	 *
	 * @param array $params The variable params.
	 */
	public function replace( array $params = array() ) {
		$this->params = $params;
	}

	/**
	 * Merges parameter values
	 *
	 * @param array $params The new params.
	 */
	public function merge( array $params = array() ) {
		$this->params = array_merge( $this->params, $params );
	}

	/**
	 * Checks if a variable is set.
	 *
	 * @param string $name The variable name.
	 *
	 * @return bool
	 */
	public function __isset( $name ) {
		return $this->offsetExists( $name );
	}

	/**
	 * Sets a variable.
	 *
	 * @param string $name The variable name.
	 * @param mixed  $value The variable value.
	 */
	public function __set( $name, $value ) {
		$this->offsetSet( $name, $value );
	}

	/**
	 * Returns a variable value.
	 *
	 * @param string $name The variable name.
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		return $this->offsetGet( $name );
	}

	/**
	 * Whether a offset exists
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param mixed $offset An offset to check for.
	 *
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	public function offsetExists( $offset ) {
		return array_key_exists( $offset, $this->params );
	}

	/**
	 * Offset to retrieve
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetget.php
	 *
	 * @param mixed $offset The offset to retrieve.
	 *
	 * @return mixed Can return all value types.
	 * @throws InvalidArgumentException When the variable does not exist.
	 * @since 5.0.0
	 */
	public function offsetGet( $offset ) {
		if ( ! array_key_exists( $offset, $this->params ) ) {
			throw new InvalidArgumentException( "Param {$offset} does not exist." );
		}

		return $this->params[ $offset ];
	}

	/**
	 * Offset to set
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetset.php
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value  The value to set.
	 *
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetSet( $offset, $value ) {
		$this->params[ $offset ] = $value;
	}

	/**
	 * Offset to unset
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetUnset( $offset ) {
		unset( $this->params[ $offset ] );
	}

	/**
	 * Count elements of an object
	 *
	 * @link  http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 *
	 * The return value is cast to an integer.
	 * @since 5.1.0
	 */
	public function count() {
		return count( $this->params );
	}

	/**
	 * Retrieve an external iterator
	 *
	 * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 * @since 5.0.0
	 */
	public function getIterator() {
		return new ArrayIterator( $this->params );
	}

	/**
	 * String representation of object
	 *
	 * @link  http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 * @since 5.1.0
	 */
	public function serialize() {
		return serialize( $this->params );
	}

	/**
	 * Constructs the object
	 *
	 * @link  http://php.net/manual/en/serializable.unserialize.php
	 *
	 * @param string $serialized The string representation of the object.
	 *
	 * @return void
	 * @since 5.1.0
	 */
	public function unserialize( $serialized ) {
		$this->params = unserialize( $serialized );
	}
}

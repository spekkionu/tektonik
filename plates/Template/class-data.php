<?php
/**
 * Data class
 *
 * @package Tektonik
 */

namespace Tektonik\Plates\Template;

use InvalidArgumentException;

/**
 * Preassigned template data.
 */
class Data {

	/**
	 * Variables shared by all templates.
	 *
	 * @var array
	 */
	protected $shared_variables = array();

	/**
	 * Specific template variables.
	 *
	 * @var array
	 */
	protected $template_variables = array();

	/**
	 * Add template data.
	 *
	 * @param  array             $data      Template data.
	 * @param  null|string|array $templates Templates to add data to.
	 *
	 * @return Data
	 * @throws InvalidArgumentException When invalid templates variable.
	 */
	public function add( array $data, $templates = null ) {
		if ( null === $templates ) {
			return $this->share_with_all( $data );
		}

		if ( is_array( $templates ) ) {
			return $this->share_with_some( $data, $templates );
		}

		if ( is_string( $templates ) ) {
			return $this->share_with_some( $data, array( $templates ) );
		}

		throw new InvalidArgumentException(
			'The templates variable must be null, an array or a string, ' . gettype( $templates ) . ' given.'
		);
	}

	/**
	 * Add data shared with all templates.
	 *
	 * @param  array $data Data to add to all templates.
	 * @return Data
	 */
	public function share_with_all( $data ) {
		$this->shared_variables = array_merge( $this->shared_variables, $data );

		return $this;
	}

	/**
	 * Add data shared with some templates.
	 *
	 * @param  array $data Data to share.
	 * @param  array $templates Templates to share data with.
	 * @return Data
	 */
	public function share_with_some( $data, array $templates ) {
		foreach ( $templates as $template ) {
			if ( isset( $this->template_variables[ $template ] ) ) {
				$this->template_variables[ $template ] = array_merge( $this->template_variables[ $template ], $data );
			} else {
				$this->template_variables[ $template ] = $data;
			}
		}

		return $this;
	}

	/**
	 * Get template data.
	 *
	 * @param  null|string $template Template to get data for.
	 * @return array
	 */
	public function get( $template = null ) {
		if ( isset( $template, $this->template_variables[ $template ] ) ) {
			return array_merge( $this->shared_variables, $this->template_variables[ $template ] );
		}

		return $this->shared_variables;
	}
}

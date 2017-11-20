<?php
/**
 * Main Tektonik class
 *
 * @package Akismet
 */

use Tektonik\Plates\Engine;
use Tektonik\Plates\Template\Params;

/**
 * Class Tektonik
 */
class Tektonik {
	/**
	 * Plates engine instance
	 *
	 * @var Engine
	 */
	private static $plates;

	/**
	 * Initializes Plates engine.
	 *
	 * @return Engine
	 */
	public static function instance() {
		if ( ! self::$plates ) {
			self::$plates = new Engine(
				self::get_default_template_directories(),
				'phtml'
			);
			do_action( 'tektonik_init', self::$plates );
		}

		return self::$plates;
	}

	/**
	 * Clears out initialized instance of engine to help for testing
	 */
	public static function clear_instance() {
		self::$plates = null;
	}

	/**
	 * Returns default template directory
	 *
	 * @return array
	 */
	private static function get_default_template_directories() {
		$directories = [];
		if ( is_dir( get_stylesheet_directory() . '/tektonik' ) ) {
			$directories[] = get_stylesheet_directory() . '/tektonik';
		}
		if ( is_dir( get_template_directory() . '/tektonik' ) ) {
			$directories[] = get_template_directory() . '/tektonik';
		}
		if ( ! $directories ) {
			$directories[] = plugin_dir_path( __FILE__ ) . 'tektonik';
		}

		return array_unique( $directories );
	}

	/**
	 * Adds plugin template directory as namespace
	 *
	 * Call from your plugin file to activate
	 * Tektonik::addPlugin( 'pluginname', plugin_dir_path( __FILE__ ) )
	 *
	 * @param string $namespace Plugin namespace.
	 * @param string $directory Path to the plugin directory. Should end in slash. Recommended to use plugin_dir_path().
	 *
	 * @see plugin_dir_path
	 */
	public static function add_plugin( $namespace, $directory ) {
		self::instance()->add_folder( $namespace, $directory . 'tektonik', false );
	}

	/**
	 * Renders template and returns as string
	 *
	 * Fetch a theme template
	 * Tektonik::fetch('templatename', ['name' => 'Bob']);
	 *
	 * Fetch a plugin template
	 * Tektonik::fetch('pluginname::templatename', ['name' => 'Bob']);
	 *
	 * @param string $name The template to render.
	 * @param array  $params The parameters to pass to the template.
	 *
	 * @return string
	 */
	public static function fetch( $name, array $params = [] ) {
		$template = self::instance()->make( $name );
		$template = apply_filters( 'tektonik_template', $template );

		$obj = new Params( $params );
		$obj = apply_filters( 'tektonik_render', $obj, $template );

		return $template->render( $obj->getParams() );
	}

	/**
	 * Renders template and prints
	 *
	 * Render a theme template
	 * Tektonik::render('templatename', ['name' => 'Bob']);
	 *
	 * Render a plugin template
	 * Tektonik::render('pluginname::templatename', ['name' => 'Bob']);
	 *
	 * @param string $name The template to render.
	 * @param array  $params The parameters to pass to the template.
	 */
	public static function render( $name, array $params = [] ) {
		echo self::fetch( $name, $params );
	}
}

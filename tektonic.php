<?php
/*
Plugin Name: Tektonik
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: PHP Template Engine for WordPress
Version:     20170405
Author:      Jonathan Bernardi
Author URI:  https://developer.wordpress.org/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: tektonik
*/

use Tektonik\Plates\Template\Params;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

spl_autoload_register( function ( $class ) {
	if ( ! preg_match( "~^Tektonik\\\\Plates\\\\~", $class ) ) {
		return;
	}

	$path = __DIR__ . DIRECTORY_SEPARATOR . 'plates' . DIRECTORY_SEPARATOR . str_replace( "\\", DIRECTORY_SEPARATOR,
			preg_replace( "~^Tektonik\\\\Plates\\\\~", "", $class ) ) . '.php';
	if ( is_file( $path ) ) {
		include $path;
	}
} );

class Tektonik {
	/**
	 * @var Tektonik\Plates\Engine
	 */
	private static $plates;

	/**
	 * @return \Tektonik\Plates\Engine
	 */
	public static function instance() {
		if ( ! self::$plates ) {
			self::$plates = new Tektonik\Plates\Engine(
				self::getDefaultTemplateDirectories(),
				'phtml'
			);
			do_action( 'tektonic_init', self::$plates );
		}

		return self::$plates;
	}

	/**
	 * Returns default template directory
	 *
	 * @return array
	 */
	private static function getDefaultTemplateDirectories() {
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
	 * @param string $namespace Plugin namespace
	 * @param string $directory Path to the plugin directory. Should end in slash. Recommended to use plugin_dir_path()
	 *
	 * @see plugin_dir_path
	 */
	public static function addPlugin( $namespace, $directory ) {
		self::instance()->addFolder( $namespace, $directory . 'tektonik', false );
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
	 * @param string $name
	 * @param array  $params
	 *
	 * @return string
	 * @throws \Throwable
	 * @throws \Exception
	 */
	public static function fetch( $name, array $params = [] ) {

		$template = self::instance()->make( $name );
		do_action( 'tektonic_template', $template );
		$obj = new Params( $params );
		do_action( 'tektonic_render', $template, $obj );

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
	 * @param string $name
	 * @param array  $params
	 *
	 * @throws \Exception
	 * @throws \Throwable
	 */
	public static function render( $name, array $params = [] ) {
		echo self::fetch( $name, $params );
	}
}

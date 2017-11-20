<?php
/**
 * Loads plates classes
 *
 * @package Tektonik
 */

namespace Tektonik\Plates;

/**
 * Class Tektonik_Autoloader
 */
class Autoloader {

	/**
	 * Plates classes
	 *
	 * @var array
	 */
	private static $classes = array();

	/**
	 * Class Constructor
	 */
	public static function register() {
		self::$classes = array(
			'Tektonik\\Plates\\Engine'                        => __DIR__ . DIRECTORY_SEPARATOR . 'class-engine.php',
			'Tektonik\\Plates\\Extension\\ExtensionInterface' => __DIR__ . DIRECTORY_SEPARATOR . 'Extension' . DIRECTORY_SEPARATOR . 'class-extensioninterface.php',
			'Tektonik\\Plates\\Template\\Data'                => __DIR__ . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . 'class-data.php',
			'Tektonik\\Plates\\Template\\Directory'           => __DIR__ . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . 'class-directory.php',
			'Tektonik\\Plates\\Template\\Folder'              => __DIR__ . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . 'class-folder.php',
			'Tektonik\\Plates\\Template\\Folders'             => __DIR__ . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . 'class-folders.php',
			'Tektonik\\Plates\\Template\\Func'                => __DIR__ . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . 'class-func.php',
			'Tektonik\\Plates\\Template\\Functions'           => __DIR__ . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . 'class-functions.php',
			'Tektonik\\Plates\\Template\\Name'                => __DIR__ . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . 'class-name.php',
			'Tektonik\\Plates\\Template\\Params'              => __DIR__ . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . 'class-params.php',
			'Tektonik\\Plates\\Template\\Template'            => __DIR__ . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . 'class-template.php',
		);

		spl_autoload_register( '\\Tektonik\\Plates\\Autoloader::autoload' );
	}

	/**
	 * Load the class
	 *
	 * @param string $class The class to load.
	 */
	public static function autoload( $class ) {
		if ( ! array_key_exists( $class, self::$classes ) ) {
			return;
		}
		include self::$classes[ $class ];
	}
}

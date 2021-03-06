<?php
/**
 * Plugin Name: Tektonik
 * Plugin URI:  https://github.com/spekkionu/tektonik
 * GitHub Plugin URI: spekkionu/tektonik
 * Description: PHP Template Engine for WordPress
 * Version:     20190307
 * Author:      Jonathan Bernardi
 * Author URI:  https://developer.wordpress.org/
 * GitHub Plugin URI: spekkionu/tektonik
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tektonik
 * Requires PHP:      5.6
 *
 * @package Tektonik
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once __DIR__ . DIRECTORY_SEPARATOR . 'plates' . DIRECTORY_SEPARATOR . 'class-autoloader.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'class-tektonik.php';

Tektonik\Plates\Autoloader::register();

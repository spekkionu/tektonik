<?php
/**
 * Class SampleTest
 *
 * @package Tektonik
 */

use Tektonik\Plates\Engine;

/**
 * Sample test case.
 */
class TektonikTest extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		$this->build();
	}

	private function build() {
		Tektonik::clear_instance();
		Tektonik::instance()->set_directory( [ __DIR__ . '/_data/tektonik' ] );
	}

	public function test_instance() {
		$tektonik = Tektonik::instance();
		$this->assertInstanceOf( Engine::class, $tektonik );
	}

	public function test_default_folders_are_set() {
		Tektonik::clear_instance();
		$folders = Tektonik::instance()->get_directory();
		$this->assertCount( 1, $folders );
		$this->assertStringEndsWith( 'tektonik' . DIRECTORY_SEPARATOR . 'tektonik', $folders[0] );
	}

	public function test_add_plugin_support() {
		Tektonik::add_plugin( 'test', __DIR__ . '/_data/' );
		$folders = Tektonik::instance()->get_folders();
		$this->assertTrue( $folders->exists( 'test' ) );
	}

	public function test_fetch_template() {
		$content = Tektonik::fetch( 'template', array( 'name' => 'Steve' ) );
		$this->assertEquals( 'Hello Steve', trim( $content ) );
	}

	public function test_render_template() {
		ob_start();
		Tektonik::render( 'template', array( 'name' => 'Bob' ) );
		$content = ob_get_clean();
		$this->assertEquals( 'Hello Bob', trim( $content ) );
	}

	public function test_plugin_template() {
		Tektonik::clear_instance();
		Tektonik::add_plugin( 'testplugin', __DIR__ . '/_data/testplugin/' );
		$content = Tektonik::fetch( 'testplugin::template' );
		$this->assertEquals( 'plugin', trim( $content ) );
	}

	public function test_theme_templates_override_plugin_templates() {
		Tektonik::add_plugin( 'testplugin', __DIR__ . '/_data/testplugin/' );
		$content = Tektonik::fetch( 'testplugin::template' );
		$this->assertEquals( 'theme', trim( $content ) );
	}

	public function test_init_action_allows_modification_of_engine() {
		Tektonik::clear_instance();
		add_action( 'tektonik_init', function ($engine) {
			$engine->set_file_extension('tpl');
		} );
		$extension = Tektonik::instance()->get_file_extension();
		$this->assertEquals('tpl', $extension);
	}

	public function test_template_filter_allows_template_modification() {
		add_filter( 'tektonik_template', function ($template) {
			$template->replace_name('replaced');
			return $template;
		} );
		$content = Tektonik::fetch( 'template' );
		$this->assertEquals( 'replaced', trim( $content ) );
	}

	public function test_render_filter_allows_parameter_modification() {
		add_filter( 'tektonik_render', function ($params) {
			$params->set('name', 'Bill');
			return $params;
		} );
		$content = Tektonik::fetch( 'template', array( 'name' => 'Bob' ) );
		$this->assertEquals( 'Hello Bill', trim( $content ) );
	}
}

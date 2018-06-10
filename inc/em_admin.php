<?php 

require_once 'em_documentation.php';
require_once 'em_settings.php';
require_once 'em_customizer.php';

final class Emtheme_admin {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {

		Emtheme_documentation::get_instance();
		Emtheme_settings::get_instance();
		Emtheme_customizer::get_instance();

		$this->admin_hooks();
	}

	private function admin_hooks() {
		
		add_action('admin_enqueue_scripts', array($this, 'enqueue_sands'));
	
	}

	public function enqueue_sands() {

        wp_enqueue_style('admin-style', get_theme_file_uri().'/assets/css/admin/theme.css', array(), '0.0.1');
        // wp_enqueue_script('admin-script', get_theme_file_uri().'/assets/js/admin/theme.js', array('jQuery'), '0.0.1', true);
        wp_enqueue_script('emscript', get_theme_file_uri().'/assets/js/admin/theme.js', array(), '0.0.1', true);

	}
}
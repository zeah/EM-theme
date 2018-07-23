<?php 

require_once 'em_documentation.php';
require_once 'em_settings.php';
require_once 'em_customizer.php';
require_once 'em_sitemap.php';


/**
 * Singleton class
 *
 * Admin area pages, customizer and content editor 
 */
final class Emtheme_admin {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {

		/* EM Documentation admin page */
		Emtheme_documentation::get_instance();

		/* Settings sub-pages */
		Emtheme_settings::get_instance();

		/* Customizer */
		Emtheme_customizer::get_instance();

		/* Sitemap feature */
		Emtheme_sitemap::get_instance();

		/* hooks for users on admin pages */
		$this->admin_hooks();
	}

	private function admin_hooks() {
		
		/* admin js and css */
		add_action('admin_enqueue_scripts', array($this, 'enqueue_sands'));

		/* 	adding css to tinymce 
			CSS from customizer -> additional css gets shown in tinymce editor */
		add_filter('tiny_mce_before_init', array($this, 'add_to_tinymce')); 

	}

	public function enqueue_sands() {

		/* admin css */
        wp_enqueue_style('admin-style', get_theme_file_uri().'/assets/css/admin/theme.css', array(), '0.0.1');
        
        /* admin js */
        wp_enqueue_script('emscript', get_theme_file_uri().'/assets/js/admin/theme.js', array(), '0.0.1', true);

	}

	/**
	 * adding stylesheet, inline style and google fonts link to tinymce iframe
	 * @param {void} $options goes through a wp filter
	 */
	public function add_to_tinymce($options) {

		// getting the content font settings
		$fonts = get_theme_mod('emtheme_font');
		$family = isset($fonts['content_family']) ? esc_attr($fonts['content_family']) : 'Roboto';
		$weight = isset($fonts['content_weight']) ? esc_attr(str_replace('italic', 'i', ':'.$fonts['content_weight'])) : '';
		$size = isset($fonts['content_size']) ? floatval($fonts['content_size']) : '16';
		$lineheight = isset($fonts['content_lineheight']) ? floatval($fonts['content_lineheight']) : 1.3;

		// adding external css
		$options['content_css'] = get_template_directory_uri() . '/assets/css/admin/editor.css,http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $family).$weight;

		// adding content font and css from "additional css"
		$options['content_style'] = 'body { font-family: \''.$family.'\'; font-size: '.$size.'px; line-height: '.$lineheight.'}'.preg_replace('/\s+/', ' ', wp_get_custom_css());

		return $options; 
	}
}
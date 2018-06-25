<?php 

require_once 'em_documentation.php';
require_once 'em_settings.php';
require_once 'em_customizer.php';
require_once 'em_sitemap.php';
// require_once 'em_seo.php';

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
		Emtheme_sitemap::get_instance();
		// Emtheme_seo::get_instance();

		$this->admin_hooks();
	}

	private function admin_hooks() {
		
		add_action('admin_enqueue_scripts', array($this, 'enqueue_sands'));
		add_filter('tiny_mce_before_init', array($this, 'add_to_tinymce')); 

	}

	public function enqueue_sands() {

        wp_enqueue_style('admin-style', get_theme_file_uri().'/assets/css/admin/theme.css', array(), '0.0.1');
        // wp_enqueue_script('admin-script', get_theme_file_uri().'/assets/js/admin/theme.js', array('jQuery'), '0.0.1', true);
        wp_enqueue_script('emscript', get_theme_file_uri().'/assets/js/admin/theme.js', array(), '0.0.1', true);

	}

	/**
	 * adding stylesheet, inline style and google fonts link to tinymce iframe
	 * @param {void} $options goes through a wp filter
	 */
	public function add_to_tinymce($options) {

		$fonts = get_theme_mod('emtheme_font');

		$family = isset($fonts['content_family']) ? esc_attr($fonts['content_family']) : 'Roboto';
		$weight = isset($fonts['content_weight']) ? esc_attr(str_replace('italic', 'i', ':'.$fonts['content_weight'])) : '';
		$size = isset($fonts['content_size']) ? floatval($fonts['content_size']) : '16';
		$lineheight = isset($fonts['content_lineheight']) ? floatval($fonts['content_lineheight']) : 1.3;

		$options['content_css'] = get_template_directory_uri() . '/assets/css/admin/editor.css,http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $family).$weight;

		$options['content_style'] = 'body { font-family: \''.$family.'\'; font-size: '.$size.'px; line-height: '.$lineheight.'}'.preg_replace('/\s+/', ' ', wp_get_custom_css());

		return $options; 
	}
}
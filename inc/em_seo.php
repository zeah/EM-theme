<?php 


final class Emtheme_seo {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->admin_hooks();
	}

	private function admin_hooks() {
		add_action('add_meta_boxes', array($this, 'add_meta_box'));
		add_filter('seo_meta', array($this, 'add_seo_meta'));
	}

	public function add_meta_box() {

		$types = apply_filters('seo_meta', []);

		add_meta_box(
			'theme_seo_meta',
			'SEO Options',
			array($this, 'seo_callback'),
			'page'
		);
	}

	public function seo_callback($post) {


		$html = '<h3>Custom Title</h3><input type="text" name="emtheme_seo[custom_title]">';
		$html .= '<h3>Meta Description</h3><input type="text" name="emtheme_seo[meta_description]">';



		echo $html;
	}

	public function add_seo_meta($data) {

		array_push($data, 'page', 'post');

		return $data;
	}


}
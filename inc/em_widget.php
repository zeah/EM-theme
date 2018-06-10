<?php 


/**
 * 
 */
final class Emtheme_widget {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->hooks();
	}

	private function hooks() {
		add_action('widgets_init', array($this, 'register_widget'));
	}

	public function register_widget() {

		register_sidebar(array(
			'name'			=> _x('Default Template Sidebar', 'widget name', 'emtheme'),
			'id'			=> 'emtheme-def-template',
			'description'	=> _x('A right column is added next to content used with default template.', 'widget description','emtheme'),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		));

	}
}
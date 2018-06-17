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
			'name'			=> _x('Default Template Right', 'widget name', 'emtheme'),
			'id'			=> 'default-template-right',
			'description'	=> _x('A RIGHT column is added next to content used with default template.', 'widget description','emtheme'),
			'before_widget' => '<div class="default-template-right-widget default-template-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		));

		
		register_sidebar(array(
			'name'			=> _x('Default Template Left', 'widget name', 'emtheme'),
			'id'			=> 'default-template-left',
			'description'	=> _x('A LEFT column is added next to content used with default template.', '','emtheme'),
			'before_widget' => '<div class="default-template-left-widget default-template-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		));

		
		register_sidebar(array(
			'name'			=> _x('Default Template Top', 'widget name', 'emtheme'),
			'id'			=> 'default-template-top',
			'description'	=> _x('An element is added on top of the content used with default template.', '','emtheme'),
			'before_widget' => '<div class="default-template-top-widget default-template-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		));


		register_sidebar(array(
			'name'			=> _x('Default Template Bottom', 'widget name', 'emtheme'),
			'id'			=> 'default-template-bottom',
			'description'	=> _x('An element is added below the content used with default template.', '','emtheme'),
			'before_widget' => '<div class="default-template-bottom-widget default-template-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		));

	}
}
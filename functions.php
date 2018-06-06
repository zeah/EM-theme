<?php 

/* */

/* content area is 1280px wide with 20px padding */
if (!isset($content_width)) $content_width = 1240;

if (! function_exists('emtheme_setup')) {
    function emtheme_setup() {

    	//  add thumbnails to posts/pages
		add_theme_support('post-thumbnails');

		// add image size to array of images when uploading
        add_image_size('em_main_column_image', 970);
        add_image_size('em_content_image', 1240);

        // activating theme functions
        EM_functions::get_instance();





        // Emtheme_Admin::get_instance();

        // /* page-edit page stuff (meta boxes and saving) */
        // Emtheme_Page::get_instance();

        // /* redirecting pages */
        // // Emtheme_redirect::get_instance();

        // /* shortcodes ([col]) */
        // Emtheme_ShortCode::get_instance();

        // /* css, sitemap, filters */
        // Emtheme_function::get_instance();

        // Emtheme_customizer::get_instance();

        // Emtheme_link::get_instance();

        // Emtheme_Widget::get_instance();
    }
}
add_action('after_setup_theme', 'emtheme_setup');

/* non-specific functions */
final class EM_functions {
	private static $instance = null;

	/* singleton */
	public static function get_instance() {
		if  (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}


	private function __construct() {

		// hooks for admin only
		if (is_admin()) $this->admin_hooks();

		// hooks for all
		$this->wp_hooks();

	}


	/* hooks only needed when in admin area */
	private function admin_hooks() {
		/* adding image to dropdown selector when inserting media into post */
        add_filter('image_size_names_choose', array($this, 'add_image_size'));
	}


	/* hooks for all */
	private function wp_hooks() {

		/* register navigation area */
        add_action('init', array($this, 'register_nav'));


        /* adding styles and scripts for front-end (sands = Scripts AND Styles) */ 
        if (!is_admin()) add_action('wp_enqueue_scripts', array($this, 'add_sands'));
	}


	/*
		Adding theme specific image sizes.

		Content area : 1280px width - 20px padding -> 1240px

		@return new array for image selector 
	*/
	public function add_image_size($data) {
		return array_merge($data, array(
			'em_main_column_image' => 'Main Column Size',
			'em_content_image' => 'Content Width'
		));
	}

	public function register_nav() {
        register_nav_menu('header-menu', __('Header Menu'));
	}


	/*
		adding front end styles and scripts
	*/
	public function add_frontend_sands() {
		/* adding script for mobile nav and search feature */
        wp_enqueue_script('front_end_theme', get_theme_file_uri().'/assets/pub/js/emtheme.js', array(), '0.0.1', true);

        wp_enqueue_style('style', get_theme_file_uri().'/assets/pub/css/theme.css', array(), '0.0.1', '(min-width: 1280px)');
        wp_enqueue_style('style', get_theme_file_uri().'/assets/pub/css/theme-mobile.css', array(), '0.0.1', '(max-width: 1279px)');

	}


}
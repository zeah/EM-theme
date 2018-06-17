<?php 

require_once 'inc/em_css.php';
require_once 'inc/em_widget.php';
require_once 'inc/em_admin.php';

/* */

/* content area is 1280px wide with 20px padding */
// (1280px on chrome's very large font size setting equals to 1920px wide content.)
if (!isset($content_width)) $content_width = 1240;

if (! function_exists('emtheme_setup')) {
    function emtheme_setup() {

    	// internationalizing
    	load_theme_textdomain('emtheme', get_template_directory().'/languages');

    	//  add thumbnails to posts/pages
		add_theme_support('post-thumbnails');

		add_theme_support( 'custom-logo' );

		add_theme_support( 'custom-background' );

		// add image size to array of images when uploading
        add_image_size('em_main_column_image', 910);
        add_image_size('em_content_image', 1220);

        // activating theme functions
        Emtheme_functions::get_instance();

        // registering sidebars (widgets)
        Emtheme_widget::get_instance();

        Emtheme_admin::get_instance();

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
final class Emtheme_functions {
	private static $instance = null;

	/* singleton */
	public static function get_instance() {
		if  (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}


	private function __construct() {

		// hooks for all
		$this->hooks();

		/* hooks for admin else hooks for front-end */
		if (is_admin()) $this->admin_hooks();
		else 			$this->wp_hooks();
	}


	/* hooks for admin  */
	private function admin_hooks() {

		/* adding image to dropdown selector when inserting media into post */
        add_filter('image_size_names_choose', array($this, 'add_image_size'));

	}


	/* hooks for front-end */
	private function wp_hooks() {

		/* Scripts AND Styles */
        add_action('wp_enqueue_scripts', array($this, 'add_frontend_sands'));
		Emtheme_css::get_instance();

	}


	/* hooks for all */
	private function hooks() {

        
        // add_filter('pre_get_posts', array($this, 'set_search'));
		add_action('after_setup_theme', array($this, 'themename_custom_logo_setup'));

        /* disable emoji scripts */
        add_action('init', array($this, 'disable_emoji'));

		/* register navigation area */
        add_action('init', array($this, 'register_nav'));

	}


	/*
		Adding theme specific image sizes.

		Content area : 1280px width - 20px padding -> 1240px

		@return new array for image selector 
	*/
	public function add_image_size($data) {
		
		return array_merge($data, array(
			'em_main_column_image' => __('Main Column Size', 'emtheme'),
			'em_content_image' => __('Content Width', 'emtheme')
		));
	
	}


	/*
		Adding menu locations
	*/
	public function register_nav() {
     
     	/* the standard header menu */
        register_nav_menu('header-menu', __('Header Menu', 'emtheme'));
	
	}


	/*
		adding front end styles and scripts
	*/
	public function add_frontend_sands() {

		/* adding script for mobile nav and search feature */
        wp_enqueue_script('front-end-theme', get_theme_file_uri().'/assets/js/pub/theme.js', array('jquery'), '0.0.1', true);

        /* adding css files to front-end - desktop sizes vs others */
        wp_enqueue_style('style', get_theme_file_uri().'/assets/css/pub/theme.css', array(), '0.0.1', '(min-width: 1280px)');
        wp_enqueue_style('mobile-style', get_theme_file_uri().'/assets/css/pub/theme-mobile.css', array(), '0.0.1', '(max-width: 1279px)');

	}


	/* removes emojicon scripts and adds neccessary filters */
	public function disable_emoji() {
		remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');

        add_filter('tiny_mce_plugins', array($this, 'disable_emojicons_tinymce'));

        add_filter('emoji_svg_url', '__return_false');
	}

	/* filter to remove emojicons from editor */
	function disable_emojicons_tinymce($plugins) {
        if (is_array($plugins)) return array_diff($plugins, array('wpemoji'));
        else 					return array();
    }

    /* sets what to post types internal search uses */
    public function set_search($query) {
        if ($query->is_search) {
            if ($query->get('post_type') == 'user_request') return;
            if (!$query->get('post_type')) $query->set('post_type', array('page'));
            else $query->set('post_type', array_merge(array('page'), $query->get('post_type')));
        }
    }

	public function themename_custom_logo_setup() {
	    $defaults = array(
	        'height'      => 100,
	        'width'       => 400,
	        'flex-height' => true,
	        'flex-width'  => true,
	        'header-text' => array( 'site-title', 'site-description' ),
	    );
	}

}

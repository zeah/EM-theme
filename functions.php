<?php 

if (version_compare($GLOBALS['wp_version'], '4.9', '<')) return;

require_once 'inc/em_css.php';
require_once 'inc/em_widget.php';
require_once 'inc/em_admin.php';
require_once 'inc/em_google_font.php';
require_once 'inc/em_search.php';
require_once 'inc/em_page_seo.php';
require_once 'inc/em_filter.php';
require_once 'inc/em_counter.php';

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

		add_theme_support('custom-logo');

		add_theme_support('custom-background');

		add_post_type_support('page', 'excerpt');

		// add image size to array of images when uploading
        add_image_size('em_main_column_image', 910);
        add_image_size('em_content_image', 1220);

        add_filter('widget_text', 'do_shortcode');


        // activating theme functions
        Emtheme_functions::get_instance();

        // registering sidebars (widgets)
        Emtheme_widget::get_instance();

        Emtheme_admin::get_instance();

        Emtheme_search::get_instance();

		Emtheme_page_seo::get_instance();

		Emtheme_filter::get_instance();

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


/**
 * Adds personal access key with public access to increase GitHub API limit.
 * For the GitHub Updater Plugin.
 */
add_filter( 'github_updater_set_options',
	function () {
		return array( 
			'github_access_token' => 'b803351b389d6e1d0b7ccacc179b189bd027e2f9',
		);
	} );


/*  */
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

        add_action('wp_head', array($this, 'add_google_fonts'));
        add_action('admin_head', array($this, 'add_google_fonts'));

        /* adding iframe as allowed tag in posts */
        add_filter('wp_kses_allowed_html', array($this, 'custom_wpkses_post_tags'), 10, 2);

        add_filter('the_content', array($this, 'do_kses'), 10);
	}

	public function do_kses($data) {
		return wp_kses_post($data);
	}

	/**
	 * Add iFrame to allowed wp_kses_post tags
	 *
	 * @param string $tags Allowed tags, attributes, and/or entities.
	 * @param string $context Context to judge allowed tags by. Allowed values are 'post',
	 *
	 * @return mixed
	 */
	public function custom_wpkses_post_tags( $tags, $context ) {
		if ( 'post' === $context ) {
			$tags['iframe'] = array(
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
				'style'			  => true
			);
			
			// $tags['svg'] = array(
			// 	'class'	=> true
			// );

			// $tags['defs'] = array();

			// $tags['lineargradient'] = array(
			// 	'id'	=> true,
			// 	'x1'	=> true,
			// 	// 'X2'	=> true,
			// 	// 'y1'	=> true,
			// 	// 'y2'	=> true
			// );

			// $tags['stop'] = array(
			// 	'offset'	=> true,
			// 	'style'		=> true
			// );

			// $tags['circle'] = array(
			// 	'class'	=> true,
			// 	'cx'	=> true,
			// 	'cy'	=> true,
			// 	'r'		=> true
			// );

			// $tags['rect'] = array(
			// 	'class'	=> true,
			// 	'rx'	=> true,
			// 	'ry'	=> true,
			// 	'fill'	=> true,
			// );
		}
		return $tags;
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
        wp_enqueue_script('front-end-theme', get_theme_file_uri().'/assets/js/pub/theme.js', array('jquery'), '0.0.2', true);

        /* adding css files to front-end - desktop sizes vs others */
        wp_enqueue_style('style', get_theme_file_uri().'/assets/css/pub/theme.css', array(), '0.0.2', '(min-width: 1280px)');
        // wp_enqueue_style('style', get_theme_file_uri().'/assets/css/pub/theme.css', array(), '0.0.1', '(min-width: 1280px)');
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

	public function add_google_fonts() {

		$google = Emtheme_google_font::get_instance();

		echo $google->get_link();
	}

}

// add_filter('tiny_mce_before_init', 'add_tinymce_font'); 

// function add_tinymce_font($options) {

// 	$fonts = get_theme_mod('emtheme_font');

// 	$family = isset($fonts['content_family']) ? esc_attr($fonts['content_family']) : 'Roboto';
// 	$weight = isset($fonts['content_weight']) ? esc_attr(str_replace('italic', 'i', ':'.$fonts['content_weight'])) : '';

// 	$options['content_css'] = get_template_directory_uri() . '/assets/css/admin/editor.css,http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $family).$weight;

// 	$options['content_style'] = 'body { font-family: \''.$family.'\'; }';

// 	return $options; 
// }
// 
// 
// add_filter('search_first', 'tesr_fun');

// function tesr_fun($data) {
// 	global $post;

// 	$types = ['post', 'page']
// 	if (!in_array($post->post_type, $types)) return $data;
	


// 	return $data.' hi';
// }
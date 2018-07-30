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

		Emtheme_counter::get_instance();

		/* hooks for users on admin pages */
		$this->admin_hooks();
	}

	private function admin_hooks() {
		
		/* admin js and css */
		add_action('admin_enqueue_scripts', array($this, 'enqueue_sands'));

		/* 	adding css to tinymce 
			CSS from customizer -> additional css gets shown in tinymce editor */
		add_filter('tiny_mce_before_init', array($this, 'add_to_tinymce')); 


		add_filter( 'contextual_help', array($this, 'screen_info'), 10, 3 );


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
		$options['content_css'] = get_template_directory_uri() . '/assets/css/admin/editor.css,https://fonts.googleapis.com/css?family='.str_replace(' ', '+', $family).$weight;

		// adding content font and css from "additional css"
		$options['content_style'] = 'body { font-family: \''.$family.'\'; font-size: '.$size.'px; line-height: '.$lineheight.'}'.preg_replace('/\s+/', ' ', wp_get_custom_css());

		return $options; 
	}


	/**
	 * Adding screen names and hooks to "screen information" to help
	 * @return [type]                  [description]
	 */
	public function screen_info($contextual_help, $screen_id, $screen) {
		// The add_help_tab function for screen was introduced in WordPress 3.3.
	    if ( ! method_exists( $screen, 'add_help_tab' ) )
	        return $contextual_help;
	 
	    global $hook_suffix;
	 
	    // List screen properties
	    $variables = '<ul style="width:50%;float:left;"> <strong>Screen variables </strong>'
	        . sprintf( '<li> Screen id : %s</li>', $screen_id )
	        . sprintf( '<li> Screen base : %s</li>', $screen->base )
	        . sprintf( '<li>Parent base : %s</li>', $screen->parent_base )
	        . sprintf( '<li> Parent file : %s</li>', $screen->parent_file )
	        . sprintf( '<li> Hook suffix : %s</li>', $hook_suffix )
	        . '</ul>';
	 
	    // Append global $hook_suffix to the hook stems
	    $hooks = array(
	        "load-$hook_suffix",
	        "admin_print_styles-$hook_suffix",
	        "admin_print_scripts-$hook_suffix",
	        "admin_head-$hook_suffix",
	        "admin_footer-$hook_suffix"
	    );
	 
	    // If add_meta_boxes or add_meta_boxes_{screen_id} is used, list these too
	    if ( did_action( 'add_meta_boxes_' . $screen_id ) )
	        $hooks[] = 'add_meta_boxes_' . $screen_id;
	 
	    if ( did_action( 'add_meta_boxes' ) )
	        $hooks[] = 'add_meta_boxes';
	 
	    // Get List HTML for the hooks
	    $hooks = '<ul style="width:50%;float:left;"> <strong>Hooks </strong> <li>' . implode( '</li><li>', $hooks ) . '</li></ul>';
	 
	    // Combine $variables list with $hooks list.
	    $help_content = $variables . $hooks;
	 
	    // Add help panel
	    $screen->add_help_tab( array(
	        'id'      => 'wptuts-screen-help',
	        'title'   => 'Screen Information',
	        'content' => $help_content,
	    ));
	 
	    return $contextual_help;
	}
}
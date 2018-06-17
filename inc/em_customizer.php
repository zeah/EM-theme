<?php 

final class Emtheme_customizer {
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

		add_action('customize_register', array($this, 'colors'));
		add_action('customize_register', array($this, 'fonts'));
		add_action('customize_register', array($this, 'widgets'));

		add_action('customize_register', array($this, 'privacy_popup'));
		add_action('customize_register', array($this, 'footer_info'));

		add_action('customize_preview_init', array($this, 'customizer_sands'), 9999);
		add_action('customize_controls_enqueue_scripts', array($this, 'customizer_pane_sands'));


	}

	public function customizer_sands() {

		$fontFile = get_stylesheet_directory() . '/assets/cache/google-web-fonts.txt';
        $content = json_decode(file_get_contents($fontFile));

		wp_enqueue_script('cd_customizer', get_theme_file_uri().'/assets/js/admin/customizer.js', array('jquery','customize-preview'), '0.0.1', true);
	    wp_localize_script('cd_customizer', 'gfont', $content->items);
	}

	public function customizer_pane_sands() {
	
		$fontFile = get_stylesheet_directory() . '/assets/cache/google-web-fonts.txt';
        $content = json_decode(file_get_contents($fontFile));

		wp_enqueue_script('theme_customizer_pane', get_theme_file_uri().'/assets/js/admin/customizer-pane.js', array('jquery','customize-preview'), '0.0.1', true);
	    wp_localize_script('theme_customizer_pane', 'gfont', $content->items);
		wp_add_inline_style('admin-style', $this->get_css());
	}

	private function get_css() {
		$css = '#customize-control-theme_footer-active::before { content: \''._x('Footer Info', 'Customizer title', 'emtheme').'\'; }';

		$css .= '#customize-control-emtheme_color-submenu_bg::before { content: \''._x('Sub-menu', 'Customizer title', 'emtheme').'\'; }';

		$css .= '#customize-control-emtheme_color-nav_bg_top::before { content: \''._x('NAV COLORS\A\ANavbar', 'Customizer titles \A is css newline', 'emtheme').'\'; }';

		$css .= '#customize-control-emtheme_font-title_size::before { content: \''._x('Size', 'Customizer title for font size.', 'emtheme').'\';}';

		$css .= '#customize-control-emtheme_font-title_family::before { content: \''._x('Family', 'Customizer title for font family.', 'emtheme').'\';}';

		$css .= '#customize-control-emtheme_font-title_weight::before { content: \''._x('Weight', 'Customizer title for font weight.', 'emtheme').'\';}';

		$css .= '#customize-control-emtheme_font-content_lineheight::before { content: \''._x('Line-height', 'Customizer title for font settings', 'emtheme').'\';}';
		
		$css .= '#customize-control-emtheme_color-emtop_bg::before { content: \''._x('Header', 'Customizer title for header colors', 'emtheme').'\';}';

		$css .= '#customize-control-background_color::before { content: \''._x('Content', 'Customizer title for content.', 'emtheme').'\';}';

		$css .= '#customize-control-emtheme_color-footer_bg::before { content: \''._x('Footer', 'Customizer title for footer', 'emtheme').'\';}';

		return $css;
	}


	public function colors($cust) {



		$this->add($cust, 'emtheme_color[main_background]', 'colors', 'color', 
							_x('Content area', 'Customizer color', 'emtheme'), 
							'',
							'#ffffff', 
							50, 'sanitize_hex_color');

		$this->add($cust, 'emtheme_color[main_shadow]', 'colors', 'color', 
							_x('Content box shadow', 'Customizer color', 'emtheme'), 
							'',
							'', 
							51, 'sanitize_hex_color');


		$this->add($cust, 'emtheme_color[main_font]', 'colors', 'color', 
							_x('Font', 'Customizer title for font color', 'emtheme'), 
							'',
							'#000000', 
							52, 'sanitize_hex_color');
		

		$this->add($cust, 'emtheme_color[emtop_bg]', 'colors', 'color', 
							_x('Background', 'Customizer color', 'emtheme'), 
							'',
							'#ffffff', 
							70, 'sanitize_hex_color');
		
		$this->add($cust, 'emtheme_color[emtop_font]', 'colors', 'color', 
							_x('Font', 'Customizer color', 'emtheme'), 
							'',
							'#000000', 
							71, 'sanitize_hex_color');
		

		/* navbar bg top/solid color */
		$this->add($cust, 'emtheme_color[nav_bg_top]', 'colors', 'color', 
									_x('Background: Top/Solid', 'Title for customizer: navbar background color, either top color in linear-gradient or full color if only one set.', 'emtheme'), 
									'', 
									'#000000', 
									100, 'sanitize_hex_color');

		/* navbar bg middle color */
		$this->add($cust, 'emtheme_color[nav_bg_middle]', 'colors', 'color', 
									_x('Background: Middle', 'Title for customizer: navbar background color middle color in linear-gradient.', 'emtheme'), 
									'Gradient', 
									'', 
									101, 'sanitize_hex_color');

		/* navbar bg bottom color */
		$this->add($cust, 'emtheme_color[nav_bg_bottom]', 'colors', 'color', 
									_x('Background: Bottom', 'Title for customizer: navbar background color bottom color in linear-gradient.', 'emtheme'), 
									'Gradient', 
									'', 
									102, 'sanitize_hex_color');
		


		/* navbar hover top/solid color */
		$this->add($cust, 'emtheme_color[nav_bg_hover_top]', 'colors', 'color', 
									_x('Hover: Top/Solid', 'Title for customizer: navbar hover color, either top color in linear-gradient or full color if only one set.', 'emtheme'), 
									'', 
									'#335533', 
									103, 'sanitize_hex_color');

		/* navbar hover middle color */
		$this->add($cust, 'emtheme_color[nav_bg_hover_middle]', 'colors', 'color', 
									_x('Hover: Middle', 'Title for customizer: navbar hover color middle color in linear-gradient.', 'emtheme'), 
									'Gradient', 
									'', 
									104, 'sanitize_hex_color');

		/* navbar hover bottom color */
		$this->add($cust, 'emtheme_color[nav_bg_hover_bottom]', 'colors', 'color', 
									_x('Hover: Bottom', 'Title for customizer: navbar hover color bottom color in linear-gradient.', 'emtheme'), 
									'Gradient', 
									'', 
									105, 'sanitize_hex_color');


		$this->add($cust, 'emtheme_color[navbar_border]', 'colors', 'color', 
							_x('Border for top level', 'Customizer control for border color for navbar top level', 'emtheme'), 
							'',
							'#666666', 
							106, 'sanitize_hex_color');
		

		$this->add($cust, 'emtheme_color[navbar_active]', 'colors', 'color', 
							_x('Active page marker', 'Customizer control for active page marker on navbar', 'emtheme'), 
							'',
							'#363', 
							107, 'sanitize_hex_color');
		


		/* navbar submenu background color */
		$this->add($cust, 'emtheme_color[submenu_bg]', 'colors', 'color', 
									_x('Background', 'Customizer Control for navbar submenu background color', 'emtheme'), 
									'', 
									'#eeeeee', 
									120, 'sanitize_hex_color');
		

		$this->add($cust, 'emtheme_color[submenu_hover]', 'colors', 'color', 
							_x('Hover', 'Customizer control for background hover on submenu', 'emtheme'), 
							'',
							'#bbbbbb', 
							121, 'sanitize_hex_color');
		
		$this->add($cust, 'emtheme_color[submenu_border]', 'colors', 'color', 
							_x('Border', 'Customizer control for submenu border', 'emtheme'), 
							'',
							'#aaaaaa', 
							122, 'sanitize_hex_color');

		$this->add($cust, 'emtheme_color[submenu_font]', 'colors', 'color', 
							_x('Font', 'Customizer control for submenu font color', 'emtheme'), 
							'',
							'#000000', 
							123, 'sanitize_hex_color');
		
		


		/* navbar font color */
		$this->add($cust, 'emtheme_color[navbar_font]', 'colors', 'color', 
							_x('Font', 'Customizer control for font color in top level navbar', 'emtheme'), 
							'',
							'#ffffff', 
							110, 'sanitize_hex_color');




		$this->add($cust, 'emtheme_color[footer_bg]', 'colors', 'color', 
							_x('Background', 'Customizer title for footer background color setting', 'emtheme'), 
							'',
							'#000000', 
							80, 'sanitize_hex_color');
		
		$this->add($cust, 'emtheme_color[footer_font]', 'colors', 'color', 
							_x('Font', 'Customizer title for footer font color setting', 'emtheme'), 
							'',
							'#ffffff', 
							81, 'sanitize_hex_color');
		
	}


	public function fonts($cust) {


		$cust->add_section('theme_fonts', array(
			'title' => 'Fonts',
			'capability' => 'edit_theme_options',
			'priority' => 40
		));

		$args = array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		);

		/* family */
		$cust->add_setting('emtheme_font[title_family]', $args);
		$cust->add_setting('emtheme_font[navbar_family]', $args);
		$cust->add_setting('emtheme_font[content_family]', $args);


		$fontFile = get_stylesheet_directory() . '/assets/cache/google-web-fonts.txt';
        $content = json_decode(file_get_contents($fontFile));

        $families = [];
        foreach($content->items as $f) 
        	$families[$f->family] = $f->family;


		$cust->get_setting('emtheme_font[title_family]')->default = 'Roboto';
        $cust->add_control('emtheme_font[title_family]', array(
        	'section' => 'theme_fonts',
        	'type' => 'select',
        	'choices' => $families,
        	'priority' => 50,
        	'label' => 'Site Title'
        ));

		$cust->get_setting('emtheme_font[navbar_family]')->default = 'Roboto';
        $cust->add_control('emtheme_font[navbar_family]', array(
        	'section' => 'theme_fonts',
        	'type' => 'select',
        	'choices' => $families,
        	'priority' => 51,
        	'label' => 'Navbar'
        ));

		$cust->get_setting('emtheme_font[content_family]')->default = 'Open Sans';
        $cust->add_control('emtheme_font[content_family]', array(
        	'section' => 'theme_fonts',
        	'type' => 'select',
        	'choices' => $families,
        	'priority' => 52,
        	'label' => 'Content'
        ));


		/* settings for weights
		   controller gets added by JS 
		*/
	
		$args['default'] = '400';
		$cust->add_setting('emtheme_font[title_weight]', $args);
		$cust->add_setting('emtheme_font[navbar_weight]', $args);
		$cust->add_setting('emtheme_font[content_weight]', $args);



		/* sizes */
		$this->add($cust, 'emtheme_font[title_size]', 'theme_fonts', 'number', 
							_x('Site Title', 'Font size for title', 'emtheme'), 
							'',
							'40', 
							200, 'sanitize_text_field');

		$this->add($cust, 'emtheme_font[navbar_size]', 'theme_fonts', 'number', 
							_x('Navbar', 'Font size for navbar', 'emtheme'), 
							'',
							'20', 
							201, 'sanitize_text_field');

		$this->add($cust, 'emtheme_font[content_size]', 'theme_fonts', 'number', 
							_x('Content', 'Font size for content', 'emtheme'), 
							'',
							'16', 
							202, 'sanitize_text_field');


		// content lineheight
		$cust->add_setting('emtheme_font[content_lineheight]', array(
							'type' => 'theme_mod',
							'transport' => 'postMessage',
							'sanitize_callback' => 'sanitize_text_field',
							'default' => '1.3'	
						));

		$cust->add_control('emtheme_font[content_lineheight]', array(
							'section' => 'theme_fonts',
							'type' => 'number',
							'label' => _x('Content', 'Customizer title for lineheight settings', 'emtheme'),
							'priority' => 250, 
							'input_attrs' => array(
								'step' => 0.1 
							)
						));

	}

	public function widgets($cust) {

		$this->add($cust, 'emtheme_widget[leftone]', 'widgets', 'checkbox', 
							_x('label', 'tes', 'emtheme'), 
							'',
							'', 
							100, 'sanitize_text_field');
		
	}

	/**
	 * Register Customizer settings and controls for privacy window. 
	 * @param  WP_Customizer $cust Gets sent by wordpress core
	 * @return none
	 */
	public function privacy_popup($cust) {

		/* custom panel for customizer page */
		$cust->add_section('theme_privacy_section', array(
			'title' => 'Privacy Window',
			'capability' => 'edit_theme_options',
			'priority' => 201
		));


		/* adding customizer control for privacy window text */
		$this->add($cust, 'theme_privacy[text]', 'theme_privacy_section', 'textarea', 
							_x('Privacy Window Text', 'Customizer for privacy window', 'emtheme'), 
							_x('Accepts any html a regular post would.', 'Extra info for customizer for privacy window text', 'emtheme'), 
							_x('By continuing to use this site you agree to our terms of privacy.', 'Default value for privacy window text.', 'emtheme'), 
							2, 'wp_kses_post');
		
		/* adding customizer control for privacy background color */
		$this->add($cust, 'theme_privacy[bg]', 'theme_privacy_section', 'color', 
							_x('Background', 'Background color for privacy window', 'emtheme'), 
							_x('Sets background color for the privacy window.', 'description of customizer control for privacy window background color.', 'emtheme'), 
							'#eeeeee', 
							3, 'sanitize_hex_color');
		
		/* adding customizer control for privacy font color */
		$this->add($cust, 'theme_privacy[font]', 'theme_privacy_section', 'color', 
							_x('Text', 'customizer control for font color for privacy window.', 'emtheme'), 
							_x('Sets font color for privacy window.', 'description of customizer control for font color for privacy window.', 'emtheme'), 
							'#000000', 
							4, 'sanitize_hex_color');
		
		/* adding customizer control for privacy button text */
		$this->add($cust, 'theme_privacy[button_text]', 'theme_privacy_section', 'text', 
							_x('Button Text', 'Button text for privacy window', 'emtheme'), 
							'', 
							_x('OK', 'Default value for button in privacy window', 'emtheme'), 
							5, 'sanitize_text_field');
		
		/* adding customizer control for privacy button color */
		$this->add($cust, 'theme_privacy[button_bg]', 'theme_privacy_section', 'color', 
							_x('Button background', 'Customizer control for button color on privacy window.', 'emtheme'), 
							'', 
							'#aaaaaa', 
							6, 'sanitize_hex_color');
		
		/* adding customizer control for privacy window button font color */
		$this->add($cust, 'theme_privacy[button_font]', 'theme_privacy_section', 'color', 
							_x('Button text', 'customizer control for button font color on privacy window.', 'emtheme'), 
							'', 
							'#000000', 
							7, 'sanitize_hex_color');
		
	}

	/**
	 * Adds customizer tools in "Site Identity" on customizer page.
	 */
	public function footer_info($cust) {

		/* Checkbox for deactivating info footer html element */
		$this->add($cust, 'theme_footer[active]', 'title_tagline', 'checkbox', 
							_x('Deactivate Info Footer', 'check to deactivate info footer element', 'emtheme'), 
							_x('Check to deactivate info footer element', 'Description of checkbox to deactivate info footer element', 'emtheme'), 
							'', 
							159, 'sanitize_text_field');

		/* textarea for adding to info footer in left most position */
		$this->add($cust, 'theme_footer[social]', 'title_tagline', 'textarea', 
							_x('Social Info', 'front-end footer element', 'emtheme'), 
							_x('Is shown as the left-most column.', 'description of social info footer element', 'emtheme'), 
							'', 
							160, 'wp_kses_post');

		/* textarea for adding to info footer in center or right position */
		$this->add($cust, 'theme_footer[contact]', 'title_tagline', 'textarea', 
							_x('Contact Info', 'front-end footer element', 'emtheme'), 
							_x('Is shown in the middle or on the left.', 'description of contact footer element', 'emtheme'), 
							'', 
							161, 'wp_kses_post');

		/* textarea for adding to info footer in right position */
		$this->add($cust, 'theme_footer[aboutus]', 'title_tagline', 'textarea', 
							_x('About Us Info', 'front-end footer element', 'emtheme'), 
							_x('Is shown as the right-most column.', 'description of about us footer element', 'emtheme'), 
							'', 
							162, 'wp_kses_post');
		
	}


	/**
	 * Helper function for registering customizer control
	 * @param  WP_Customizer  $cust        relay WP_Customizer object
	 * @param  string  $setting     name of db value
	 * @param  string  $section     where to put the control on customizer page
	 * @param  string  $type        which type of controler (textarea, color, checkbox)
	 * @param  string  $label       titel or input label
	 * @param  string  $description description of controler
	 * @param  string  $default     default value of controller
	 * @param  integer $priority    order of showing controlllers
	 * @param  string  $sanitize    callback function to sanitize value
	 * @return none                 function has no return value
	 */
	private function add(	$cust = null, 
									$setting = null, 
									$section = null, 
									$type = 'text', 
									$label = '', 
									$description = '', 
									$default = '',
									$priority = 170, 
									$sanitize = 'sanitize_text_field'
								) {

		// requried parameters
		if (!$cust || !$setting || !$section) return false;

		// adding setting
		$cust->add_setting($setting, array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'default' => $default,
			'sanitize_callback' => $sanitize
		));

		if ($type != 'color') {
			$cust->add_control($setting, array(
				'type' => $type,
				'label' => $label,
				'description' => $description,
				'priority' => $priority,
				'section' => $section
			));
		}

		// special rule for color type
		elseif ($type == 'color') {

			$cust->add_control(
				new WP_Customize_Color_Control($cust, $setting,
				array(
					'label' => $label,
					'description' => $description,
					'priority' => $priority,
					'section' => $section
				)
			));
		}

	}

}
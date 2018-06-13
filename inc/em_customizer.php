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

		add_action('customize_register', array($this, 'nav_colors'));

		add_action('customize_register', array($this, 'privacy_popup'));
		add_action('customize_register', array($this, 'footer_info'));

		add_action('customize_preview_init', array($this, 'customizer_sands'), 9999);
		add_action('customize_controls_enqueue_scripts', array($this, 'customizer_pane_sands'));
	}

	public function customizer_sands() {
		wp_enqueue_script('cd_customizer', get_theme_file_uri().'/assets/js/admin/customizer.js', array('jquery','customize-preview'), '0.0.1', true);
	}

	public function customizer_pane_sands() {
		wp_enqueue_script('cd_customizer_pane', get_theme_file_uri().'/assets/js/admin/customizer-pane.js', array('jquery','customize-preview'), '0.0.1', true);
	}


	public function nav_colors($cust) {

		$this->register_cust($cust, 'emtheme_color[navbar_bg_top]', 'colors', 'color', 
									_x('Background: Top/Solid', 'Title for customizer: navbar background color, either top color in linear-image or full color if only one set.', 'emtheme'), 
									'', 
									'#000000', 
									100, 'sanitize_hex_color');

		$this->register_cust($cust, 'emtheme_color[navbar_bg_middle]', 'colors', 'color', 
									_x('Background: Middle', 'Title for customizer: navbar background color middle color in linear-image.', 'emtheme'), 
									'', 
									'', 
									101, 'sanitize_hex_color');

		$this->register_cust($cust, 'emtheme_color[navbar_bg_bottom]', 'colors', 'color', 
									_x('Background: Bottom', 'Title for customizer: navbar background color bottom color in linear-image.', 'emtheme'), 
									'', 
									'', 
									102, 'sanitize_hex_color');
		


		$this->register_cust($cust, 'emtheme_color[submenu_bg]', 'colors', 'color', 
									_x('Background', 'Customizer Control for navbar submenu background color', 'emtheme'), 
									'', 
									'#eeeeee', 
									120, 'sanitize_hex_color');
		

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
			'priorty' => 201
		));


		/* adding customizer control for privacy window text */
		$this->register_cust($cust, 'theme_privacy[text]', 'theme_privacy_section', 'textarea', 
									_x('Privacy Window Text', 'Customizer for privacy window', 'emtheme'), 
									_x('Accepts any html a regular post would.', 'Extra info for customizer for privacy window text', 'emtheme'), 
									_x('By continuing to use this site you agree to our terms of privacy.', 'Default value for privacy window text.', 'emtheme'), 
									2, 'wp_kses_post');
		
		/* adding customizer control for privacy background color */
		$this->register_cust($cust, 'theme_privacy[bg]', 'theme_privacy_section', 'color', 
									_x('Background', 'Background color for privacy window', 'emtheme'), 
									_x('Sets background color for the privacy window.', 'description of customizer control for privacy window background color.', 'emtheme'), 
									'#eeeeee', 
									3, 'sanitize_hex_color');
		
		/* adding customizer control for privacy font color */
		$this->register_cust($cust, 'theme_privacy[font]', 'theme_privacy_section', 'color', 
									_x('Text', 'customizer control for font color for privacy window.', 'emtheme'), 
									_x('Sets font color for privacy window.', 'description of customizer control for font color for privacy window.', 'emtheme'), 
									'#000000', 
									4, 'sanitize_hex_color');
		
		/* adding customizer control for privacy button text */
		$this->register_cust($cust, 'theme_privacy[button_text]', 'theme_privacy_section', 'text', 
									_x('Button Text', 'Button text for privacy window', 'emtheme'), 
									'', 
									_x('OK', 'Default value for button in privacy window', 'emtheme'), 
									5, 'sanitize_text_field');
		
		/* adding customizer control for privacy button color */
		$this->register_cust($cust, 'theme_privacy[button_bg]', 'theme_privacy_section', 'color', 
									_x('Button background', 'Customizer control for button color on privacy window.', 'emtheme'), 
									'', 
									'#aaaaaa', 
									6, 'sanitize_hex_color');
		
		/* adding customizer control for privacy window button font color */
		$this->register_cust($cust, 'theme_privacy[button_font]', 'theme_privacy_section', 'color', 
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
		$this->register_cust($cust, 'theme_footer[active]', 'title_tagline', 'checkbox', 
							_x('Deactivate Info Footer', 'check to deactivate info footer element', 'emtheme'), 
							_x('Check to deactivate info footer element', 'Description of checkbox to deactivate info footer element', 'emtheme'), 
							'', 
							159, 'sanitize_text_field');

		/* textarea for adding to info footer in left most position */
		$this->register_cust($cust, 'theme_footer[social]', 'title_tagline', 'textarea', 
							_x('Social Info', 'front-end footer element', 'emtheme'), 
							_x('Is shown as the left-most column.', 'description of social info footer element', 'emtheme'), 
							'', 
							160, 'wp_kses_post');

		/* textarea for adding to info footer in center or right position */
		$this->register_cust($cust, 'theme_footer[contact]', 'title_tagline', 'textarea', 
							_x('Contact Info', 'front-end footer element', 'emtheme'), 
							_x('Is shown in the middle or on the left.', 'description of contact footer element', 'emtheme'), 
							'', 
							161, 'wp_kses_post');

		/* textarea for adding to info footer in right position */
		$this->register_cust($cust, 'theme_footer[aboutus]', 'title_tagline', 'textarea', 
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
	private function register_cust(	$cust = null, 
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
					'priority' => $priority,
					'section' => $section
				)
			));
		}

	}

}
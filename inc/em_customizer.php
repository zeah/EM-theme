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

	public function privacy_popup($cust) {


		$cust->add_section('theme_privacy_section', array(
			'title' => 'Privacy Window',
			'capability' => 'edit_theme_options',
			'priorty' => 201
		));



		$cust->add_setting('theme_privacy[text]', array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_kses_post',
			'default' => 'By continuing to use this site you agree to our terms of privacy.'
		));

		$cust->add_control('theme_privacy[text]', array(
			'type' => 'textarea',
			'label' => _x('Privacy Window Text', 'Customizer for privacy window', 'emtheme'),
			'section' => 'theme_privacy_section',
		));


		$cust->add_setting('theme_privacy[bg]', array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
			'default' => '#eeeeee'
		));

		$cust->add_control(
			new WP_Customize_Color_Control($cust, 'theme_privacy[bg]',
			array(
				'label' => 'Background',
				'section' => 'theme_privacy_section',
			)
		));

		$cust->add_setting('theme_privacy[font]', array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
			'default' => '#000000'
		));

		$cust->add_control(
			new WP_Customize_Color_Control($cust, 'theme_privacy[font]',
			array(
				'label' => 'Text',
				'section' => 'theme_privacy_section',
			)
		));



		$cust->add_setting('theme_privacy[button_text]', array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_kses_post',
			'default' => 'OK'
		));

		$cust->add_control('theme_privacy[button_text]', array(
			'type' => 'text',
			'label' => _x('Button Text', 'Button text for privacy window', 'emtheme'),
			'section' => 'theme_privacy_section',
		));



		$cust->add_setting('theme_privacy[button_bg]', array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
			'default' => '#aaaaaa'
		));

		$cust->add_control(
			new WP_Customize_Color_Control($cust, 'theme_privacy[button_bg]',
			array(
				'label' => 'Button background',
				'section' => 'theme_privacy_section',
			)
		));

		$cust->add_setting('theme_privacy[button_font]', array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
			'default' => '#000000'
		));

		$cust->add_control(
			new WP_Customize_Color_Control($cust, 'theme_privacy[button_font]',
			array(
				'label' => 'Button text',
				'section' => 'theme_privacy_section',
			)
		));


	}


	public function footer_info($cust) {


		$cust->add_setting('theme_footer[active]', array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		));

		$cust->add_control('theme_footer[active]', array(
			'type' => 'checkbox',
			'description' => 'If checked, then footer info will not be shown at all.',
			'label' => 'Disable footer info',
			'priority' => 159,
			'section' => 'title_tagline'
		));


		$cust->add_setting('theme_footer[social]', array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_kses_post'
		));

		$cust->add_control('theme_footer[social]', array(
			'type' => 'textarea',
			'label' => 'Social Info',
			'priority' => 160,
			'section' => 'title_tagline'

		));


		$cust->add_setting('theme_footer[contact]', array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_kses_post'
		));

		$cust->add_control('theme_footer[contact]', array(
			'type' => 'textarea',
			'label' => 'Contact Info',
			'priority' => 161,
			'section' => 'title_tagline'

		));


		$cust->add_setting('theme_footer[aboutus]', array(
			'type' => 'theme_mod',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_kses_post'
		));

		$cust->add_control('theme_footer[aboutus]', array(
			'type' => 'textarea',
			'label' => 'About Us Info',
			'priority' => 162,
			'section' => 'title_tagline'

		));

	}

}
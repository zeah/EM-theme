<?php 


final class Emtheme_settings {
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
		// if (!is_admin()) return;

		add_action('admin_menu', array($this, 'add_menu'));
		add_action('admin_init', array($this, 'register_settings'));

		add_action('wp_footer', array($this, 'echo_scripts'));
	}


	public function add_menu() {
		// add_menu_page('EMSettings', 'Settings', 'manage_options', 'em-settings-page', array($this, 'settings_callback'), 'none', 262);
		add_submenu_page('options-general.php', 'SEO', 'SEO', 'manage_options', 'theme-settings-seo', array($this, 'seo_settings_callback'));
		add_submenu_page('options-general.php', 'Structured Data', 'Structured Data', 'manage_options', 'theme-settings-struc', array($this, 'struc_settings_callback'));
		add_submenu_page('', 'EM', 'EM', 'manage_options', 'theme-settings-em', array($this, 'em_settings_callback'));
	}

	public function sanitize($data) {
		if (!is_array($data)) return sanitize_text_field($data);

		$d = [];
		foreach($data as $key => $value)
			$d[$key] = $this->sanitize($value);

		return $d;
	}

	public function register_settings() {
		register_setting('theme-google-options', 'theme_google_scripts', ['sanitize_callback' => array($this, 'sanitize')]);

		add_settings_section('theme-google-settings', 'Google Scripts', array($this, 'google_settings'), 'theme-settings-seo');
		add_settings_field('theme-google-tagmanager', 'Tagmanager', array($this, 'google_tagmanager'), 'theme-settings-seo', 'theme-google-settings');
		add_settings_field('theme-google-adwords', 'Analytics', array($this, 'google_adwords'), 'theme-settings-seo', 'theme-google-settings');

		register_setting('theme-em-options', 'theme_em_stuff', ['sanitize_callback' => 'wp_kses_post']);

		add_settings_section('theme-em-settings', 'Theme stuff', array($this, 'em_settings'), 'theme-settings-em');
		add_settings_field('theme-em-tagmanager', 'Copyright', array($this, 'em_copyright'), 'theme-settings-em', 'theme-em-settings');


		register_setting('theme-struc-options', 'theme_struc_data', ['sanitize_callback' => 'sanitize_text_field']);
		add_settings_section('theme-struc-settings', 'Structured data', array($this, 'struc_settings'), 'theme-settings-struc');
		add_settings_field('theme-struc-business', 'Structured Data for front page', array($this, 'struc_business'), 'theme-settings-struc', 'theme-struc-settings');

	}

	public function seo_settings_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('theme-google-options');
		do_settings_sections('theme-settings-seo');
		submit_button('save');
		echo '</form>';
	}

	public function google_settings() {
		echo 'section';
	}

	public function google_tagmanager() {
		$value = get_option('theme_google_scripts');

		if (!is_array($value)) $value = [];
		
		echo '<input type="text" name="theme_google_scripts[tagmanager]" value="'.esc_attr($value['tagmanager']).'">';
	}

	public function google_adwords() {
		$value = get_option('theme_google_scripts');

		if (!is_array($value)) $value = [];
		
		echo '<input type="text" name="theme_google_scripts[adwords]" value="'.esc_attr($value['adwords']).'">';
	
		$this->echo_scripts();
	}


	public function echo_scripts() {

		$tag = get_option('theme_google_scripts');

		if (!is_array($tag)) $tag = [];


		if ($tag['tagmanager']) {
			$script = sprintf("\n<!-- Google Tag Manager -->
						<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
						new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
						j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
						'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
						})(window,document,'script','dataLayer','%1\$s');</script>
						<!-- End Google Tag Manager -->

						\n<!-- Google Tag Manager (noscript) -->
						<noscript><iframe src='https://www.googletagmanager.com/ns.html?id=%1\$s'
						height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
						<!-- End Google Tag Manager (noscript) -->

					", esc_js($tag['tagmanager']));

			echo $script;
		}

		if ($tag['adwords']) {
			$adwords = sprintf("<script async src='https://www.googletagmanager.com/gtag/js?id=%1\$s'></script>
		    <script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} 
		    gtag('js', new Date()); gtag('config', '%1\$s');</script>",
			esc_js($tag['adwords']));

			echo $adwords;
		}


		$struc = get_option('theme_struc_data');

		if ($struc && is_front_page()) {
			$struc = json_encode(json_decode($struc), JSON_PRETTY_PRINT);
			$script = '<script type="application/ld+json">'.str_replace('\\', '', $struc).'</script>';

			// only print if it is json
			if ($struc && $struc != 'null') echo $script;
		}
	}


	public function em_settings_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('theme-em-options');
		do_settings_sections('theme-settings-em');
		submit_button('save');
		echo '</form>';
	}

	public function em_settings() {
		echo '<b>Default value:</b> All rights reserved &lt;a href="https://www.effektivmarkedsforing.no"&gt;Effektiv Markedsføring&lt;/a&gt; © 2018 -';
	}

	public function em_copyright() {
		$data = get_option('theme_em_stuff');

		echo '<input name="theme_em_stuff" type="text" value="'.esc_attr($data).'">';
	}


	public function struc_settings_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('theme-struc-options');
		do_settings_sections('theme-settings-struc');
		submit_button('save');
		echo '</form>';
	}

	public function struc_settings() {
		echo 'Structured data for front page.<br><b>No HTML tags allowed and must be valid json.</b><br>Example of allowed value:<pre>{
    "@context": "http://schema.org",
    "@type": "Organization",
    "url": "http://www.example.com",
    "name": "Unlimited Ball Bearings Corp.",
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+1-401-555-1212",
        "contactType": "Customer service"
    }
}</pre>';
	}

	public function struc_business() {
		$data = get_option('theme_struc_data');

		if ($data == '') $d = '';
		else { 
			$d = json_encode(json_decode($data), JSON_PRETTY_PRINT);
			$d = str_replace('\\', '', $d);

			if ($d == 'null') $d = 'ERROR IN CODE '.$data; 
		}

		echo '<textarea style="width: 400px; height: 400px;" name="theme_struc_data">'.$d.'</textarea>';
	}
}
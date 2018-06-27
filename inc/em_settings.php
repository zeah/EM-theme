<?php 


final class Emtheme_settings {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->admin_hooks();
	}

	private function admin_hooks() {
		// if (!is_admin()) return;

		add_action('admin_menu', array($this, 'add_menu'));
		add_action('admin_init', array($this, 'register_settings'));

		add_action('wp_footer', array($this, 'echo_scripts'));
	}


	public function add_menu() {
		// add_menu_page('EMSettings', 'Settings', 'manage_options', 'em-settings-page', array($this, 'settings_callback'), 'none', 262);
		add_submenu_page('options-general.php', 'SEO', 'SEO', 'manage_options', 'settings-seo', array($this, 'seo_settings_callback'));
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

		add_settings_section('theme-google-settings', 'Google Scripts', array($this, 'google_settings'), 'settings-seo');
		add_settings_field('theme-google-tagmanager', 'Tagmanager', array($this, 'google_tagmanager'), 'settings-seo', 'theme-google-settings');
		add_settings_field('theme-google-adwords', 'Adwords', array($this, 'google_adwords'), 'settings-seo', 'theme-google-settings');

	}

	public function seo_settings_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('theme-google-options');
		do_settings_sections('settings-seo');
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

		// if (!$tag['tagmanager']) return;

		// $tag = $tag['tagmanager'];

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
	}
}
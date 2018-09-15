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
		add_action('wp_head', array($this, 'add_header'), 2, 1);
	}


	public function add_menu() {
		// add_menu_page('EMSettings', 'Settings', 'manage_options', 'em-settings-page', array($this, 'settings_callback'), 'none', 262);
		add_submenu_page('options-general.php', 'SEO', 'SEO', 'manage_options', 'theme-settings-seo', array($this, 'seo_settings_callback'));
		add_submenu_page('options-general.php', 'Structured Data', 'Structured Data', 'manage_options', 'theme-settings-struc', array($this, 'struc_settings_callback'));
		add_submenu_page('options-general.php', 'Tracking', 'Tracking', 'manage_options', 'theme-settings-tracking', array($this, 'tracking_settings_callback'));
		add_submenu_page('', 'EM', 'EM', 'manage_options', 'theme-settings-em', array($this, 'em_settings_callback'));
	}

	public function sanitize($data) {
		if (!is_array($data)) return sanitize_text_field($data);

		$d = [];
		foreach($data as $key => $value)
			$d[$key] = $this->sanitize($value);

		return $d;
	}

	private function san_js($data) {

		$data = str_replace(['"', '\''], ['', ''], $data);

		return sanitize_text_field($data);
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

	
		register_setting('theme-tracking-options', 'theme_tracking_data', ['sanitize_callback' => array($this, 'sanitize')]);
		add_settings_section('theme-tracking-settings', 'Link Conversions', array($this, 'tracking_settings'), 'theme-settings-tracking');
		add_settings_field('theme-tracking-convert', 'Convert links', array($this, 'tracking_convert'), 'theme-settings-tracking', 'theme-tracking-settings');
		add_settings_field('theme-tracking-gclid', 'gclid', array($this, 'gclid_convert'), 'theme-settings-tracking', 'theme-tracking-settings');
		add_settings_field('theme-tracking-msclkid', 'msclkid', array($this, 'msclkid_convert'), 'theme-settings-tracking', 'theme-tracking-settings');
		add_settings_field('theme-tracking-custom', 'custom', array($this, 'custom_convert'), 'theme-settings-tracking', 'theme-tracking-settings');


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

	public function add_header() {
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
	}

	public function echo_scripts() {

		// $tag = get_option('theme_google_scripts');

		// if (!is_array($tag)) $tag = [];


		// if ($tag['tagmanager']) {
		// 	$script = sprintf("\n<!-- Google Tag Manager -->
		// 				<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		// 				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		// 				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		// 				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		// 				})(window,document,'script','dataLayer','%1\$s');</script>
		// 				<!-- End Google Tag Manager -->

		// 				\n<!-- Google Tag Manager (noscript) -->
		// 				<noscript><iframe src='https://www.googletagmanager.com/ns.html?id=%1\$s'
		// 				height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
		// 				<!-- End Google Tag Manager (noscript) -->

		// 			", esc_js($tag['tagmanager']));

		// 	echo $script;
		// }

		// if ($tag['adwords']) {
		// 	$adwords = sprintf("<script async src='https://www.googletagmanager.com/gtag/js?id=%1\$s'></script>
		//     <script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} 
		//     gtag('js', new Date()); gtag('config', '%1\$s');</script>",
		// 	esc_js($tag['adwords']));

		// 	echo $adwords;
		// }
		// 
		// 

		$tracking = get_option('theme_tracking_data');

		if (!is_array($tracking)) $tracking = [];

		$custom = $tracking['custom'];
		
		if ($tracking['convert']) {
			$script = '<script>(function() { var s = window.location.search.substring(1); if (!s) return; var a = ""; var q = s.split("&"); for (var i in q) { var p = q[i].split("=");var d = decodeURIComponent(p[0]);';

			if ($tracking['gclid']) $script .= 'if (d === "gclid") a += "gclid" + "=" + p[1] + "&";';			

			if ($tracking['msclkid']) $script .= 'if (d === "msclkid") a += "msclkid" + "=" + p[1] + "&";';
		
			if ($custom['one_name'] && $custom['one_value'] && strpos($custom['one_value'], '{') !== false)
					$script .= 'if (d === "'.str_replace(['{', '}'], '', $this->san_js($custom['one_value']))
								.'") a += "'.$this->san_js($custom['one_name']).'" + "=" + p[1] + "&";';
			
			if ($custom['two_name'] && $custom['two_value'] && strpos($custom['two_value'], '{') !== false)
					$script .= 'if (d === "'.str_replace(['{', '}'], '', $this->san_js($custom['two_value']))
								.'") a += "'.$this->san_js($custom['two_name']).'" + "=" + p[1] + "&";';
			
			if ($custom['three_name'] && $custom['three_value'] && strpos($custom['three_value'], '{') !== false)
					$script .= 'if (d === "'.str_replace(['{', '}'], '', $this->san_js($custom['three_value']))
								.'") a += "'.$this->san_js($custom['three_name']).'" + "=" + p[1] + "&";';
			
			if ($custom['four_name'] && $custom['four_value'] && strpos($custom['four_value'], '{') !== false)
					$script .= 'if (d === "'.str_replace(['{', '}'], '', $this->san_js($custom['four_value']))
								.'") a += "'.$this->san_js($custom['four_name']).'" + "=" + p[1] + "&";';
			
			$script .= '}';

			if ($custom['one_name'] && $custom['one_value'] && strpos($custom['one_value'], '{') === false)
				$script .= 'a += "'.$this->san_js($custom['one_name']).'='.$this->san_js($custom['one_value']).'&";';

			if ($custom['two_name'] && $custom['two_value'] && strpos($custom['two_value'], '{') === false)
				$script .= 'a += "'.$this->san_js($custom['two_name']).'='.$this->san_js($custom['two_value']).'&";';

			if ($custom['three_name'] && $custom['three_value'] && strpos($custom['three_value'], '{') === false)
				$script .= 'a += "'.$this->san_js($custom['three_name']).'='.$this->san_js($custom['three_value']).'&";';

			if ($custom['four_name'] && $custom['four_value'] && strpos($custom['four_value'], '{') === false)
				$script .= 'a += "'.$this->san_js($custom['four_name']).'='.$this->san_js($custom['four_value']).'&";';


					// removing last &
			$script .= 'a = a.substring(0, a.length-1); var l = document.getElementsByTagName("a"); for (var i in l) { var u = l[i].href; if (!u) continue; if (u.indexOf("?") != -1) l[i].href += "&" + a; else l[i].href += "?" + a;}})();</script>';


				echo $script;
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

	public function tracking_settings_callback() {
		echo '<form action="options.php" method="POST">';
		settings_fields('theme-tracking-options');
		do_settings_sections('theme-settings-tracking');
		submit_button('save');
		echo '</form>';
	}

	public function tracking_settings() {
		echo 'Converts links to match current url search string or adding to it.
			<p>For exampel:
			<br>The url is: yoururl.com/?gclid=jdfhue98ur
			<br>and Convert links and gclid is checked, 
			<br>then all the links on the page will also have ?gclid=jdfhue98ur tacked on them.</p>';
	}

	public function tracking_convert() {
		$data = get_option('theme_tracking_data');
		if (!is_array($data)) $data = [];

		echo '<input type=checkbox name="theme_tracking_data[convert]"'.($data['convert'] ? ' checked' : '').'>';
	}

	public function gclid_convert() {
		$data = get_option('theme_tracking_data');
		if (!is_array($data)) $data = [];

		echo '<input type=checkbox name="theme_tracking_data[gclid]"'.($data['gclid'] ? ' checked' : '').'>';
	}

	public function msclkid_convert() {
		$data = get_option('theme_tracking_data');
		if (!is_array($data)) $data = [];

		echo '<input type=checkbox name="theme_tracking_data[msclkid]"'.($data['msclkid'] ? ' checked' : '').'>';
	}

	public function custom_convert() {
		$data = get_option('theme_tracking_data');
		if (!is_array($data)) $data = [];

		$custom = $data['custom'];

		echo '<input type="text" name="theme_tracking_data[custom][one_name]" value="'.$custom['one_name'].'" placeholder="name"> = 
		  	  <input type="text" name="theme_tracking_data[custom][one_value]" value="'.$custom['one_value'].'" placeholder="name of value">
		  	  <br>
		  	  <input type="text" name="theme_tracking_data[custom][two_name]" value="'.$custom['two_name'].'" placeholder="name"> = 
		  	  <input type="text" name="theme_tracking_data[custom][two_value]" value="'.$custom['two_value'].'" placeholder="name of value">
		  	  <br>
		  	  <input type="text" name="theme_tracking_data[custom][three_name]" value="'.$custom['three_name'].'" placeholder="name"> = 
		  	  <input type="text" name="theme_tracking_data[custom][three_value]" value="'.$custom['three_value'].'" placeholder="name of value">
		  	  <br>
		  	  <input type="text" name="theme_tracking_data[custom][four_name]" value="'.$custom['four_name'].'" placeholder="name"> = 
		  	  <input type="text" name="theme_tracking_data[custom][four_value]" value="'.$custom['four_value'].'" placeholder="name of value">

		  	  <br>examples: 
		  	  <br>epi = 4i8343
		  	  <br>epi = {gclid}
		  	  ';
		// echo '<input type=checkbox name="theme_tracking_data[custom]"'.($data['custom'] ? ' checked' : '').'>';		
	}
}
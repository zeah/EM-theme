<?php 

final class Emtheme_google_font {
	/* singleton */
	private static $instance = null;

    private $default = 'Open+Sans:400|Roboto:400,700';

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {

		add_filter('google_link', array($this, 'filter'));
		
	}

	public function filter($data) {

		if (!is_array($data)) return $data;

		$fonts = get_theme_mod('emtheme_font');
		// wp_die('<xmp>'.print_r($fonts, true).'</xmp>');
		// 
		// 

		$navbar_family = (isset($fonts['navbar_family'])) ? $fonts['navbar_family'] : 'Roboto';
		$navbar_weight = (isset($fonts['navbar_weight'])) ? $fonts['navbar_weight'] : '400';

		if (isset($data[$navbar_family]) && is_array($data[$navbar_family])) array_push($data[$navbar_family], $navbar_weight);
		else $data[$navbar_family] = [$navbar_weight];

		$title_family = (isset($fonts['title_family'])) ? $fonts['title_family'] : 'Roboto';
		$title_weight = (isset($fonts['title_weight'])) ? $fonts['title_weight'] : '700';

		if (isset($data[$title_family]) && is_array($data[$title_family])) array_push($data[$title_family], $title_weight);
		else $data[$title_family] = [$title_weight];


		$content_family = (isset($fonts['content_family'])) ? $fonts['content_family'] : 'Open Sans';
		$content_weight = (isset($fonts['content_weight'])) ? $fonts['content_weight'] : '400';

		if (isset($data[$content_family]) && is_array($data[$content_family])) array_push($data[$content_family], $content_weight);
		else $data[$content_family] = [$content_weight];

		// wp_die('<xmp>'.print_r($data, true).'</xmp>');

		// if (isset($data['Open Sans']) && is_array($data['Open Sans'])) array_push($data['Open Sans'], '400');
		// else $data['Open Sans'] = ['400'];

		// if (isset($data['Roboto']) && is_array($data['Roboto'])) array_push($data['Roboto'], '400', '700');
		// else $data['Roboto'] = ['400', '700'];

		return $data;
	}

	public function get_link() {

		$data = apply_filters('google_link', []);

		// wp_die('<xmp>'.print_r($data, true).'</xmp>');
		foreach($data as &$d)
			$d = array_unique($d);

		$out = '';
		foreach($data as $k => $v)
			$out .= str_replace(' ', '+', $k).':'.implode($v, ',').'|';

		$out = rtrim($out, '|');
		
		// wp_die('<xmp>'.print_r($out, true).'</xmp>');

 		return '<link href="https://fonts.googleapis.com/css?family='.esc_attr($out).'" rel="stylesheet">';
	}
}
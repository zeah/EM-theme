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

		if (isset($data['Open Sans']) && is_array($data['Open Sans'])) array_push($data['Open Sans'], '400');
		else $data['Open Sans'] = ['400'];

		if (isset($data['Open Sans']) && is_array($data['Open Sans'])) array_push($data['Open Sans'], '400');
		else $data['Open Sans'] = ['400'];

		if (isset($data['Roboto']) && is_array($data['Roboto'])) array_push($data['Roboto'], '400', '700');
		else $data['Roboto'] = ['400', '700'];

		return $data;
	}

	public function get_link() {

		$data = apply_filters('google_link', []);

		foreach($data as &$d)
			$d = array_unique($d);

		$out = '';
		foreach($data as $k => $v)
			$out .= str_replace(' ', '+', $k).':'.implode($v, ',').'|';

		$out = rtrim($out, '|');
		
 		return '<link href="https://fonts.googleapis.com/css?family='.esc_attr($out).'" rel="stylesheet">';
	}
}
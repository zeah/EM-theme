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
		
	}

	public function get_link() {
		$out_link = '<link href="https://fonts.googleapis.com/css?family='.$this->default.'" rel="stylesheet">';
		return $out_link;
	}
}
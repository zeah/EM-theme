<?php 

final class EM_google_font {
	/* singleton */
	private static $instance = null;

    private $default = 'Open+Sans:600|Roboto:100,300,400,700';

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		
	}

}
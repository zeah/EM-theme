<?php 
// not sure if this is needed anymore -- just gonna recreate col shortcode
/**
 * Adds to the filter the_content 
 */
final class Emtheme_content {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}

	private function wp_hooks() {

	}
}
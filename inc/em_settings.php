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
		if (!is_admin()) return;

		add_action('admin_menu', array($this, 'add_menu'));

	}

	public function add_menu() {
		add_menu_page('EMSettings', 'Settings', 'manage_options', 'em-settings-page', array($this, 'settings_callback'), 'none', 262);
	}

	public function settings_callback() {

	}
}
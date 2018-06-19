<?php 

final class Emtheme_search {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
	}


	public function get() {



		// wp_die('<xmp>'.print_r(get_site_url(), true).'</xmp>');

		$html = '<form class="emtheme-search-form" action="'.get_site_url().'" method="get" role="search">';
		$html .= '<input class="emtheme-search-input" type="search" name="s">';
		$html .= '<button class="emtheme-search-button" type="submit"><i class="material-icons">search</i></button>';
		$html .= '</form>';

		return $html;

		// return '<div class="emtheme-header-search"><i class="material-icons">search</i></div>';
	}
}
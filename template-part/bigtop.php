<?php 

$bigtop = Emtheme_bigtop::get_instance();

echo $bigtop->get_html();

final class Emtheme_bigtop {
	/* SINGLETON */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
	}

	public function get_html() {

		$html = '<div class="emtheme-header">';

		if (function_exists('the_custom_logo')) $html .= '<div class="emtheme-identity">'.get_custom_logo().'</div>';
		


		$html .= '</div>';

		return $html;

	}

}
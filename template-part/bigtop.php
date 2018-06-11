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

		$html = '<div class="emtheme-header-container">';

		$html .= '<div class="emtheme-header">';

		if (function_exists('the_custom_logo')) $html .= '<div class="emtheme-identity">'.get_custom_logo().'</div>';

		$html .= '<div class="emtheme-title-tagline">';

		if (get_bloginfo('name')) $html .= '<div class="emtheme-header-title">'.esc_html(get_bloginfo('name')).'</div>';

		if (get_bloginfo('description')) $html .= '<div class="emtheme-header-tagline">'.esc_html(get_bloginfo('description')).'</div>';

		$html .= '</div>';

		$html .= '<div class="emtheme-header-search"><i class="material-icons">search</i></div>';

		$html .= '</div>';


		$html .= '</div>';

		return $html;

	}

}
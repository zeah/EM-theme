<?php 


$show = get_theme_mod('emtheme_layout');

// do nothing if toggled off in customizer
if (isset($show['header_toggle']) && $show['header_toggle'] != '' && !is_customize_preview()) return;

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

		$dim = get_theme_mod('emtheme_layout');

		if (!is_array($dim)) $dim = [];

		$html = '<div class="emtheme-header-container">';

		$html .= '<div class="emtheme-header">';

		if (function_exists('the_custom_logo')) $html .= '<div class="emtheme-identity">'.get_custom_logo().'</div>';

		$html .= '<div class="emtheme-title-tagline">';

		if (get_bloginfo('name')) $html .= '<div class="emtheme-header-title">'.esc_html(get_bloginfo('name')).'</div>';

		if (get_bloginfo('description')) $html .= '<div class="emtheme-header-tagline">'.esc_html(get_bloginfo('description')).'</div>';

		$html .= '</div>';

		if ($dim['search_toggle'] == '' || is_customize_preview()) $html .= get_search_form(false);

		$html .= '</div>';


		$html .= '</div>';

		return $html;

	}

}
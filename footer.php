<?php 


$footer = Emtheme_footer::get_instance();


/* echo privacy user accept window */
echo $footer->privacy();

echo $footer->info();

final class Emtheme_footer {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
	}
	

	/**
	 * @return html element for privacy statement
	 */
	public function privacy() {
		$pri = get_theme_mod('theme_privacy');
		
		// getting values or default values and escaping
		$pri_text = isset($pri['text']) ? wp_kses_post($pri['text']) : 'By continuing to use this site you agree to our terms of privacy.';
		$pri_button = isset($pri['button_text']) ? esc_html($pri['button_text']) : 'OK';

		return '<div class="emtheme-cookie-container">
				<div class="emtheme-cookie">
					<div class="emtheme-cookie-text">'.$pri_text.'</div>
					<button class="emtheme-cookie-button" type="button">'.$pri_button.'</button>
				</div>
			  </div>';
	}


	/**
	 * @return html element at footer for info
	 */
	public function info() {
		$info = get_theme_mod('theme_footer');

		if (isset($info['active']) && $info['active']) return;

		$html = '<div class="emtheme-footer-container"><div class="emtheme-footer">';


		if (isset($info['social']) && $info['social'] != '') $html .= '<div class="emtheme-footer-social">'.wp_kses_post($info['social']).'</div>';
		if (isset($info['contact']) && $info['contact'] != '') $html .= '<div class="emtheme-footer-contact">'.wp_kses_post($info['contact']).'</div>';
		if (isset($info['aboutus']) && $info['aboutus'] != '') $html .= '<div class="emtheme-footer-aboutus">'.wp_kses_post($info['aboutus']).'</div>';	

		$html .= '</div></div>';

		return $html;
	}

}


wp_footer();
echo '</body></html>';
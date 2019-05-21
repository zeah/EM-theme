<?php 


$footer = Emtheme_footer::get_instance();


// echo $footer->privacy();

echo $footer->info();
echo '</div>';


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

		if (isset($_COOKIE['cookieAccept'])) return;

		$pri = get_theme_mod('theme_privacy');
		
		// getting values or default values and escaping
		$pri_text = isset($pri['text']) ? $pri['text'] : 'By continuing to use this site you agree to our terms of privacy.';
		$pri_button = isset($pri['button_text']) ? $pri['button_text'] : 'OK';

		// return '<aside class="emtheme-cookie-container">
		// 		<div class="emtheme-cookie">
		// 			<div class="emtheme-cookie-text">'.wp_kses_post($pri_text).'</div>
		// 			<button class="emtheme-cookie-button" type="button">'.esc_html($pri_button).'</button>
		// 		</div>
		// 	  </aside>';
		// 	  
		return '<aside class="theme-privacy-container">
				<div class="theme-privacy">
					<button class="theme-privacy-button" type="button">'.esc_html($pri_button).'</button>
					<div class="theme-privacy-text">'.apply_filters('the_content', wp_kses_post($pri_text)).'</div>
				</div>
			  </aside>';
	}


	/**
	 * @return html element at footer for info
	 */
	public function info() {
		$info = get_theme_mod('theme_footer');

		if (!is_array($info)) $info = [];

		// if in customizer page, then only "display: none" the container element if footer is disabled. 
		// if (is_customize_preview()) {
		// 	$html .= '<footer class="emtheme-footer-container"'.($info['active'] ? ' style="display: none"' : '').'><div class="emtheme-footer">';

		// 	if ($info['social']) $html .= '<div class="emtheme-footer-social">'.wp_kses_post(preg_replace('/[\r\n]/', '<br>', $info['social'])).'</div>';
		// 	// if (isset($info['social']) && $info['social'] != '') $html .= '<div class="emtheme-footer-social">'.wp_kses_post(preg_replace('/[\r\n]/', '<br>', $info['social'])).'</div>';
			
		// 	if ($info['contact']) $html .= '<div class="emtheme-footer-contact">'.wp_kses_post(preg_replace('/[\r\n]/', '<br>', $info['contact'])).'</div>';
		// 	// if (isset($info['contact']) && $info['contact'] != '') $html .= '<div class="emtheme-footer-contact">'.wp_kses_post(preg_replace('/[\r\n]/', '<br>', $info['contact'])).'</div>';

		// 	if ($info['aboutus']) $html .= '<div class="emtheme-footer-aboutus">'.wp_kses_post(preg_replace('/[\r\n]/', '<br>', $info['aboutus'])).'</div>';	

		// 	$html .= '</div></footer>';
		// 	return $html;
		// }

		// don't output anything in front-end if footer is disabled.
		// if ($info['active']) return;

		$html = '<footer class="emtheme-footer-container">';
		
		// // copyright text
		// $cr = get_option('theme_em_stuff');

		// $cr = $cr ? $cr : 'All rights reserved <a href="https://www.effektivmarkedsforing.no">Effektiv Markedsføring</a> © 2018 -';

		// $cr .= ' '.date('Y');

		// $html .= '<div class="emtheme-cc">'.wp_kses_post($cr).'</div>';

		// footer info
		$html .= '<div class="emtheme-footer">';
		if ((isset($info['active']) && !$info['active']) || is_customize_preview()) {

			if (isset($info['social'])) $html .= '<div class="emtheme-footer-social">'.$this->filter_info($info['social']).'</div>';
			// if ($info['social']) $html .= '<div class="emtheme-footer-social">'.wp_kses_post(preg_replace('/[\r\n]/', '<br>', $info['social'])).'</div>';
			// if (isset($info['social']) && $info['social'] != '') $html .= '<div class="emtheme-footer-social">'.wp_kses_post(preg_replace('/[\r\n]/', '<br>', $info['social'])).'</div>';
			

			if (isset($info['contact'])) $html .= '<div class="emtheme-footer-contact">'.$this->filter_info($info['contact']).'</div>';
			# if ($info['contact']) $html .= '<div class="emtheme-footer-contact">'.wp_kses_post(preg_replace('/[\r\n]/', '<br>', $info['contact'])).'</div>';
			// if (isset($info['contact']) && $info['contact'] != '') $html .= '<div class="emtheme-footer-contact">'.wp_kses_post(preg_replace('/[\r\n]/', '<br>', $info['contact'])).'</div>';
			

			if (isset($info['aboutus'])) $html .= '<div class="emtheme-footer-aboutus">'.$this->filter_info($info['aboutus']).'</div>';	
			// if ($info['aboutus']) $html .= '<div class="emtheme-footer-aboutus">'.wp_kses_post(preg_replace('/[\r\n]/', '<br>', $info['aboutus'])).'</div>';	
			// if (isset($info['aboutus']) && $info['aboutus'] != '') $html .= '<div class="emtheme-footer-aboutus">'.wp_kses_post(preg_replace('/[\r\n]/', '<br>', $info['aboutus'])).'</div>';	



		}


		// copyright text
		$cr = get_option('theme_em_stuff');

		$cr = $cr ? $cr : 'All rights reserved <a href="https://www.effektivmarkedsforing.no">Effektiv Markedsføring</a> © 2018 -';

		$cr .= ' '.date('Y');

		$html .= '<div class="emtheme-cc">'.wp_kses_post($cr).'</div>';

		$html .= '</div>';
		$html .= '</footer>';
		return $html;
	}


	private function filter_info($data) {
		return preg_replace('/[\r\n]/', '<br>', $data);
		return wp_kses_post(preg_replace('/[\r\n]/', '<br>', $data));
	}

}


wp_footer();
echo '</body></html>';

$counter = Emtheme_counter::get_instance();
$counter->do_counter();

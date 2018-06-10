<?php 


$footer = Emtheme_footer::get_instance();

$footer->echo();

final class Emtheme_footer {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {

	}
	
	public function echo() {
		$pri = get_theme_mod('theme_privacy');
		// wp_die('<xmp>'.print_r($pri, true).'</xmp>');
		
		$pri_text = isset($pri['text']) ? wp_kses_post($pri['text']) : 'By continuing to use this site you agree to our terms of privacy.';
		$pri_button = isset($pri['button_text']) ? esc_html($pri['button_text']) : 'OK';

		echo '<div class="emtheme-cookie-container">
				<div class="emtheme-cookie">
					<div class="emtheme-cookie-text">'.$pri_text.'</div>
					<button class="emtheme-cookie-button" type="button">'.$pri_button.'</button>
				</div>
			  </div>';
	}

}


wp_footer();
echo '</body></html>';
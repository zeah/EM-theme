<?php 

require_once 'inc/em_css.php';
// require_once 'footer.php';

// echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
// echo '<html lang="'.get_locale().'"><head>';
echo '<!DOCTYPE html><html lang="'.get_locale().'"><head>';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<link rel="preconnect" href="https://fonts.googleapis.com/">';

// echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';

wp_head();


$color = get_theme_mod('emtheme_color');

if (!is_array($color)) $color = [];

if ($color['nav_bg_top']) echo '<meta name="theme-color" content="'.sanitize_hex_color($color['nav_bg_top']).'">';


echo '</head><body '; body_class(); echo '>';

// $footer = Emtheme_footer::get_instance();


/* echo privacy user accept window */
echo privacy();
echo '<div class="page-container">';


function privacy() {

	if (isset($_COOKIE['cookieAccept'])) return;

	$pri = get_theme_mod('theme_privacy');
	
	$pri_text = isset($pri['text']) ? $pri['text'] : 'By continuing to use this site you agree to our terms of privacy.';
	$pri_button = isset($pri['button_text']) ? $pri['button_text'] : 'OK';

	return '<aside class="theme-privacy-container">
			<div class="theme-privacy">
				<button class="theme-privacy-button" type="button">'.esc_html($pri_button).'</button>
				<div class="theme-privacy-text">'.apply_filters('the_content', wp_kses_post($pri_text)).'</div>
			</div>
		  </aside>';
}
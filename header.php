<?php 

require_once 'inc/em_css.php';
// require_once 'inc/em_google_font.php';

// $css = Emtheme_css::get_instance();

// $google = Emtheme_google_font::get_instance();

echo '<!DOCTYPE html><html lang="'.get_locale().'"><head>';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<link rel="preconnect" href="https://fonts.googleapis.com/">';

// echo $css->get_css();
// echo $google->get_link();
echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';

wp_head();

$cpage = 'frontpage';

if (!is_front_page()) {
	if (is_page()) $cpage = $post->ID;
}

// echo $postjk;
// wp_die('<xmp>'.print_r(basename(get_site_url()), true).'</xmp>');

echo '</head><body '; body_class(); echo '>';
// echo '</head><body class="body-'.preg_replace('/[^.\w]/', '', $cpage).'">';
<?php 

require_once 'inc/em_css.php';
require_once 'inc/em_google_font.php';

// $css = Emtheme_css::get_instance();
// echo $css->get_css();

$google = Emtheme_google_font::get_instance();

echo '<!DOCTYPE html><html lang="'.get_locale().'"><head>';

echo $google->get_link();
echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';

wp_head();
echo '</head><body>';
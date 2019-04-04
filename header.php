<?php 

require_once 'inc/em_css.php';

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
echo '<div class="page-container">';

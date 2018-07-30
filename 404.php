<?php 


$sidebar = ''; 
ob_start();
dynamic_sidebar('404-left-bar');
$sidebar .= ob_get_clean();


$html = '<main class="main page-404">';

$text = get_theme_mod('theme_notfound');
// wp_die('<xmp>'.print_r($text, true).'</xmp>');
if (isset($text['text']) && $text['text'] != '') $text = $text['text'];
else $text = 'This page does not exist.<br><a href="'.esc_url(home_url()).'">Please visit our front page</a>';

// content grid element 
$html .= '<section class="column-404">'.wp_kses_post($text);

$html .= '</section>';

$html .= wp_kses_post($sidebar);

$html .= '</main>';


get_header();


// site header
get_template_part('template-part/bigtop');

// navbar
get_template_part('template-part/nav');

echo $html;

get_footer();
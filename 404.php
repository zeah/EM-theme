<?php 


$sidebar = ''; 
ob_start();

dynamic_sidebar('404-left-bar');

$sidebar .= ob_get_clean();


$html = '<div class="main page-404">';

$text = get_theme_mod('theme_notfound');
if (isset($text['text'])) $text = $text['text'];
else $text = 'This page does not exist.<br><a href="'.esc_url(home_url()).'">Please visit our front page</a>';

// content grid element 
$html .= '<div class="column-404"><span>'.$text.'</span>';
// if (have_posts()) {
// 	while (have_posts()) {
// 		the_post();

// 		// post container
// 		$html .= '<div class="content">hey';

// 		// title container 
// 		// $html .= '<div class="content-title"><h1 class="content-title-text">'.get_the_title().'</h1></div>';

// 		// content container 
// 		// $html .= '<div class="content-post">'.apply_filters('the_content', get_the_content()).'</div>';
		
// 		$html .= '</div>';
// 	}
// }

$html .= '</div>';

$html .= $sidebar;

$html .= '</div>';


get_header();


// site header
get_template_part('template-part/bigtop');

// navbar
get_template_part('template-part/nav');

echo $html;

get_footer();
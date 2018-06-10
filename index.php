<?php 

// make it into a grid !

$sidebar = ''; 
if (is_active_sidebar('emtheme-def-template')) {
	ob_start();
	dynamic_sidebar('emtheme-def-template');
	$sidebar = '<div class="sidebar-def-tem">'.ob_get_clean().'</div>';
	$html = '<div class="main main-sidebar">';
}
else
	$html = '<div class="main">';

// global $post;

$html .= '<div class="content-column">';
if (have_posts()) {
	while (have_posts()) {
		the_post();

		$html .= '<div class="content">';
		$html .= '<div class="content-title"><h1 class="content-title-text">'.get_the_title().'</h1></div>';

		$html .= '<div class="content-post">'.apply_filters('the_content', get_the_content()).'</div>';
		$html .= '</div>';
	}
}
$html .= '</div>';

$html .= $sidebar;

$html .= '</div>';

get_header();

get_template_part('template-part/bigtop');

get_template_part('template-part/nav');

echo $html;

get_footer();
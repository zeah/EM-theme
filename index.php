<?php 

/* adds meta data to head via wp_head action */
$seo = Emtheme_page_seo::get_instance();

$sidebar = ''; 
ob_start();


if (is_active_sidebar('default-template-right')) {
	echo '<aside class="default-template-right-widget default-template-widget">';
	dynamic_sidebar('default-template-right');
	echo '</aside>';
}

if (is_active_sidebar('default-template-left')) {
	echo '<aside class="default-template-left-widget default-template-widget">';
	dynamic_sidebar('default-template-left');
	echo '</aside>';
}

if (is_active_sidebar('default-template-top')) {
	echo '<aside class="default-template-top-widget default-template-widget top-widget">';
	dynamic_sidebar('default-template-top');
	echo '</aside>';
}

if (is_active_sidebar('default-template-bottom')) {
	echo '<aside class="default-template-bottom-widget default-template-widget">';
	dynamic_sidebar('default-template-bottom');
	echo '</aside>';
}

$sidebar .= ob_get_clean();

// grid
$html = '<main class="main main-sidebar">';




// $html .= 

// content grid element 
$html .= '<section class="content-column">';
if (have_posts()) {
	while (have_posts()) {
		the_post();
		// wp_die('<xmp>'.print_r(get_the_title(), true).'</xmp>');
		
		add_action('wp_footer', array($seo, 'add_footer'));
		// wp_die('<xmp>'.print_r(get_the_content(), true).'</xmp>');
		
		// post container
		$html .= '<article class="content">';

		// title container 
		$html .= '<h1 class="content-title content-title-text">'.esc_html(get_the_title()).'</h1>';
		// $html .= '<header class="content-title"><h1 class="content-title-text">'.get_the_title().'</h1></header>';

		// content container (wp_kses is added to the_content filter at priority of 10 (before shortcodes are executed))
		$html .= '<div class="content-post">'.apply_filters('the_content', get_the_content()).'</div>';
		
		$html .= '</article>';
	}
}
$html .= '</section>';

// widget grids
$html .= $sidebar;

// end of grid container
$html .= '</main>';

get_header();

echo '<div class="content-container">';

// site header
get_template_part('template-part/bigtop');

// navbar
get_template_part('template-part/nav');

echo $html;

echo '</div>';

get_footer();

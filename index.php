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
		
		add_action('wp_footer', array($seo, 'add_footer')); // for multi-post pages
		
		// post container
		$html .= '<article class="content">';

		// title container 
		$meta_title = get_post_meta(get_the_ID(), 'theme_showtitle');
		if (isset($meta_title[0])) $meta_title = $meta_title[0];

		if (!$meta_title) $html .= '<h1 class="content-title content-title-text">'.esc_html(get_the_title()).'</h1>';

		// content container (wp_kses is added to the_content filter at priority of 10 (before shortcodes are executed))
		// $html .= '<div class="content-post">'.get_the_content().'</div>';
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

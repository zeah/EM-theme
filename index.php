<?php 


// $args = array(
//      'headers' => array(
//           'Authorization' => 'Basic ' . base64_encode( 'feeduser_effektivmarkedsføring:$1Hestogdill' )
//      )
// );
// $response = wp_remote_request( 'https://www.finansportalen.no/feed/v3/bank/kredittkort.atom', $args ); 

// wp_die('<xmp>'.print_r($response, true).'</xmp>');

// make it into a grid !

$sidebar = ''; 
ob_start();

dynamic_sidebar('default-template-right');
dynamic_sidebar('default-template-left');
dynamic_sidebar('default-template-top');
dynamic_sidebar('default-template-bottom');

$sidebar .= ob_get_clean();

$html = '<div class="main main-sidebar">';

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
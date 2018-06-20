<?php 


// if (!get_transient('xmlfromfor')) {
// 	// wp_die('<xmp>'.print_r('hello', true).'</xmp>');
	
// 	$args = array(
// 	     'headers' => array(
// 	          'Authorization' => 'Basic ' . base64_encode( 'feeduser_effektivmarkedsføring:$1Hestogdill' )
// 	     )
// 	);
// 	$response = wp_remote_request( 'https://www.finansportalen.no/feed/v3/bank/kredittkort.atom', $args ); 
	

// 	$temper = str_replace('f:', '', $response['body']);

// 	$xml = simplexml_load_string($temper);
// 	$json = json_encode($xml);
// 	$array = json_decode($json,TRUE);

// 	set_transient('xmlfromfor', $array, 60*60*24);
// }


// make it into a grid !

$sidebar = ''; 
ob_start();

dynamic_sidebar('default-template-right');
dynamic_sidebar('default-template-left');
dynamic_sidebar('default-template-top');
dynamic_sidebar('default-template-bottom');

$sidebar .= ob_get_clean();

$html = '<div class="main main-sidebar">';

$html .= '<div class="content content-column">';
if (have_posts()) {
	while (have_posts()) {
		the_post();

		// $html .= '<div class="content">';
		$html .= '<div class="content-title"><h1 class="content-title-text">'.get_the_title().'</h1></div>';

		$html .= '<div class="content-post">'.apply_filters('the_content', get_the_content()).'</div>';
		// $html .= '</div>';
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
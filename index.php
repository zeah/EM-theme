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

// $temper = [];


// foreach(get_transient('xmlfromfor')['entry'] as $kort) {
// 	if ($kort['title'] == 'Gold kredittkort' || $kort['title'] == 'Vivo kredittkort' || $kort['title'] == 'Business kredittkort') continue;
// 	array_push($temper, $kort['title'].' # '.$kort['leverandor_tekst']);
// }

// wp_die('<xmp>'.print_r($temper, true).'</xmp>');
// wp_die('<xmp>'.print_r(get_transient('xmlfromfor'), true).'</xmp>');

// make it into a grid !

$sidebar = ''; 
ob_start();

dynamic_sidebar('default-template-right');
dynamic_sidebar('default-template-left');
dynamic_sidebar('default-template-top');
dynamic_sidebar('default-template-bottom');

$sidebar .= ob_get_clean();

// grid
$html = '<div class="main main-sidebar">';

// content grid element 
$html .= '<div class="content-column">';
if (have_posts()) {
	while (have_posts()) {
		the_post();

		// post container
		$html .= '<div class="content">';

		// title container 
		$html .= '<div class="content-title"><h1 class="content-title-text">'.get_the_title().'</h1></div>';

		// content container 
		$html .= '<div class="content-post">'.apply_filters('the_content', get_the_content()).'</div>';
		
		$html .= '</div>';
	}
}
$html .= '</div>';

// widget grids
$html .= $sidebar;

// end of grid container
$html .= '</div>';

get_header();

// echo '<div class="page-container">';

// site header
get_template_part('template-part/bigtop');

// navbar
get_template_part('template-part/nav');

echo $html;

// echo '</div>';

get_footer();

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
// 
// 


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

// content grid element 
$html .= '<section class="content-column">';
if (have_posts()) {
	while (have_posts()) {
		the_post();

		add_action('wp_footer', array($seo, 'add_footer'));

		// post container
		$html .= '<article class="content">';

		// title container 
		$html .= '<h1 class="content-title content-title-text">'.esc_html(get_the_title()).'</h1>';
		// $html .= '<header class="content-title"><h1 class="content-title-text">'.get_the_title().'</h1></header>';

		// content container 
		$html .= '<div class="content-post">'.wp_kses_post(apply_filters('the_content', get_the_content())).'</div>';
		
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

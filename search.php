<?php 
/**
 * SERP template
 * going through the search result twice, with 2 different filters.
 * if post type has first filter (search_first), then they are ranked 
 * above the post types that has second filter (search_second)
 *
 * Filter either returns array with ['title'], ['thumbnail'], ['excerpt'] and ['link'] OR ['html']
 */

// $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
// $actual_link = 'hdfhk';

/* content area html */
$html = '<div class="main"><div class="content"><h2>Søkeresultat:</h2><ul class="emtheme-serp">';

$html_first = '';
$html_second = '';

/* first loop */
if (have_posts())
	while (have_posts()) {
		the_post();
	
		/* converts data from filter to html */
		$html_first .= emtheme_search_serp(apply_filters('search_first', []));
	}


/* second loop */
rewind_posts();
if (have_posts())
	while (have_posts()) {
		the_post();
	
		/* converts data from filter to html */
		$html_second .= emtheme_search_serp(apply_filters('search_second', []));
	}

$html .= $html_first . $html_second;

/**
 * converst escaped data from filters to html
 * one post at a time 
 * @param  Array $data from wp filters
 * @return String       html converted from data
 */
function emtheme_search_serp($data) {
	/* if no filters active, do nothing */
	if  (!isset($data[0])) return '';

	/* getting access to current post */
	// global $post;

	// $title = get_post_meta($post->ID, 'emtheme_seo');

	// if (isset($title[0])) {
	// if (isset($title[0]['custom_title']) && $title[0]['custom_title'] != '') $data[0]['title'] = $title[0]['custom_title'];
	// }

	/* html list item */
	$html = '<li class="emtheme-search-item">';

	/* if filter returned custom html */
	if ($data[0]['html']) $html .= $data[0]['html'];

	/* if filter returned default values */
	else {

		/* html link with post name text*/
		$html .= '<a class="emtheme-search-link" href="'.$data[0]['link'].'">'.$data[0]['title'].'</a>';
		
		/* html text of link */
		$html .= '<div class="emtheme-search-link-text">'.$data[0]['link'].'</div>';
		// $html .= '<div class="emtheme-search-link-text">'.THEME_ACTUAL_URL.preg_replace('/^.*?\/\/.*?\//', '/', $data[0]['link']).'</div>';
		
		/* container for thumbnail and excerpt */
		$html .= '<div class="emtheme-search-box">';

		/* if post has thumbnail */
		if ($data[0]['thumbnail']) $html .= $data[0]['thumbnail'];
		// if ($data[0]['thumbnail']) $html .= '<div class="emtheme-search-thumbnail">'.$data[0]['thumbnail'].'</div>';

		/* user set excerpt or wp generated excerpt */
		$html .= '<span class="emtheme-search-excerpt">'.$data[0]['excerpt'].'</span>';

		/* end of container of thumbnail and excerpt */
		$html .= '</div>';
		
	}

	/* end of html of post */
	$html .= '</li>';


	return $html;
} 

/* end of content area */
$html .= '</ul></div></div>';

/* get the head */
get_header();

/* get header html if set */
get_template_part('template-part/bigtop');

/* get navbar */
get_template_part('template-part/nav');

/* echoes the serp */
echo $html;

/* get the footer */
get_footer();
<?php 
/**
 *  controls the search form and wp filters for internal serp
 */
final class Emtheme_search {
	/* singleton */
	private static $instance = null;


	/**
	 * returns object of class
	 * @return {self} 
	 */
	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();


		return self::$instance;
	}

	/**
	 * activates the wp hooks
	 */
	private function __construct() {

		/* adds the wp hooks */
		$this->hooks();
	}


	/**
	 * adds to wp filter: search_second
	 * @return {void}
	 */
	private function hooks() {
		add_filter('search_second', array($this, 'add_to_serp'), 1);

		add_action('wp_head', array($this, 'add_head'));
	}


	public function add_head() {
		if (is_search()) echo '<meta name="robots" content="noindex,nofollow,noarchive">';
	}

	/**
	 * Adds to wp filter: search_second
	 * @param Array $data adds to array from wp filter
	 */
	function add_to_serp($data) {
		global $post;

		switch (get_post_type()) {
			case 'post':
			case 'page': break;
			default: return;
		}

		$meta = get_post_meta($post->ID, 'emtheme_seo');

		if (!isset($meta[0])) $meta = [];
		else $meta = $meta[0];

		$temp = [];

		/* post url */
		$temp['link'] = get_permalink();
		
		/* post title */
		$temp['title'] = $meta['custom_title'] ? $meta['custom_title'] : $post->post_title;
		// $temp['title'] = get_post_type();

		/* post thumbnail */
		$temp['thumbnail'] = get_the_post_thumbnail($post);
		
		/* post excerpt, either user set or wp generated */
		$temp['excerpt'] = $meta['meta_description'] ? $meta['meta_description'] : get_the_excerpt();

		/* custom html */
		$temp['html'] = false;

		/* add the post to the filter return */
		array_push($data, $temp);


		/* sends data on its filter way */
		return $data;
	}


	/**
	 * the wp search form
	 * @return String string of html of the search form.
	 */
	public function get() {


		/* form container */
		$html = '<nav class="emtheme-search-form"><form action="'.get_site_url().'" method="get" role="search">';
		
		/* input text field */
		$html .= '<input class="emtheme-search-input" type="search" name="s">';

		if (strpos($_SERVER['QUERY_STRING'], 'gclid')) {

			preg_match('/gclid=(.*?)($|&)/', $_SERVER['QUERY_STRING'], $matches);

			if ($matches[1]) $html .= '<input type="hidden" value="'.$matches[1].'" name="gclid">';
		}
		
		/* search button */
		$html .= '<button class="emtheme-search-button" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="search-form-icon" d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg></button>';
		
		$html .= '</form></nav>';


		// $html .= print_r($_SERVER['QUERY_STRING'], true);

		return $html;
	}
}
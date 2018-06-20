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
	}


	/**
	 * Adds to wp filter: search_second
	 * @param Array $data adds to array from wp filter
	 */
	function add_to_serp($data) {
		global $post;

		$temp = [];

		/* post url */
		$temp['link'] = get_permalink();
		
		/* post title */
		$temp['title'] = $post->post_title;
		
		/* post thumbnail */
		$temp['thumbnail'] = get_the_post_thumbnail($post, array(120, 80));
		
		/* post excerpt, either user set or wp generated */
		$temp['excerpt'] = get_the_excerpt();

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
		$html = '<form class="emtheme-search-form" action="'.get_site_url().'" method="get" role="search">';
		
		/* input text field */
		$html .= '<input class="emtheme-search-input" type="search" name="s">';
		
		/* search button */
		$html .= '<button class="emtheme-search-button" type="submit"><i class="material-icons">search</i></button>';
		
		$html .= '</form>';


		return $html;
	}
}
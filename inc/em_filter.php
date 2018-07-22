<?php 


final class Emtheme_filter {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->hooks();
	}

	private function hooks() {

		add_filter('the_content', array($this, 'do_content'), 1, 1);
	}

	/**
	 * Replaces [col ***] and [/col] into a flexbox
	 * @param  WP_Post $data wp using the_content filter on current post.
	 * @return WP_Post       filtered post content.
	 */
	public function do_content($data) {

		// checking whether the post has [col **]
		preg_match_all('/(\[col.*?\])/', $data, $matches);

		// if matches found
		if ($matches[1]) {
			
			// checking syntax:
			// only [col right], [col center], [col left] with or without custom width is allowed.
			foreach($matches[1] as $col)
				if (!preg_match('/\[col (left|center|right)(| width=(|")\d+.(|px)(|"))\]$/', $col, $match))	
					return $data; // does nothing
		

			// adding flexbox
			$data = preg_replace('/\[col/', '<div class="content-flex">[col', $data, 1);

			// closing flexbox
			$data = $this->str_replace_last('[/col]', '[/col]</div>', $data);

			// adding flex items with custom width
			$data = preg_replace('/\[col (right|center|left) width=(|")\d+.(|px)(|")\]/', '<div class="content-flex-$1" style="width: $2px">', $data);

			// adding flex items with default width from external css
			$data = preg_replace('/\[col (center|left|right)\]/', '<div class="content-flex-$1">', $data);

			// closing all flex items
			$data = str_replace('[/col]', '</div>', $data);
		}

		// sending the post content through
		return $data;
	}


	/**
	 * helper function for replacing last occurance in string
	 * @param  String $search  [description]
	 * @param  String $replace [description]
	 * @param  String $str     [description]
	 * @return {String}          [description]
	 */
	private function str_replace_last($search, $replace, $str) {

	    if (($pos = strrpos($str, $search)) !== false) {
	        $search_length = strlen($search);
	        $str = substr_replace($str, $replace, $pos, $search_length);
	    }

	    return $str;
	}

}
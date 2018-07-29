<?php


final class Emtheme_counter {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->admin_hooks();
	}

	/**
	 * add actions and filters for adding column for page hits on page/post list page
	 * 
	 * @return [void]
	 */
	private function admin_hooks() {
		add_action('manage_pages_columns', array($this, 'column_head'));
		add_action('manage_posts_columns', array($this, 'column_head'));

		add_filter('manage_pages_custom_column', array($this, 'custom_column'));
		add_filter('manage_posts_custom_column', array($this, 'custom_column'));
		
		add_filter('manage_edit-page_sortable_columns', array($this, 'sort_column'));
		add_filter('manage_edit-post_sortable_columns', array($this, 'sort_column'));
		
		add_filter('request', array($this, 'column_orderby'));

		// add_action('save_post', array($this, 'add_counter'));
	}


	/**
	 * deprecated
	 *
	 * adds zero or return the meta value and saves it.
	 * to show pages with zero hits if you sort them by hits
	 * 	
	 * @param [int] $post_id [wp post object attribute id]
	 */
	public function add_counter($post_id) {
		$meta = get_post_meta($post_id, 'em_counter');

		if (isset($meta[0])) $meta = $meta[0];
		else $meta = 0;

		update_post_meta($post_id, 'em_counter', intval($meta));
	}


	/**
	 * wp action for adding column to page list page
	 * 
	 * @param  [Array] $defaults [list of columns]
	 * @return [Array]           [added Hit Counter column to columns array]
	 */
	public function column_head($defaults) {
		$defaults['em_counter'] = 'Hit Counter';
		return $defaults;
	}


	/**
	 * adds visual value to column
	 *  
	 * @param  [string] $column_name [the column wp is currently populating]
	 * @return [void]             
	 */
	public function custom_column($column_name) {
	
		// populates "Hit Counter" column 
		if ($column_name == 'em_counter') {
			global $post;
			$meta = get_post_meta($post->ID, 'em_counter');

			// if hits registered, echo them
			if (isset($meta[0])) echo intval($meta[0]);

			// deprecated - show 0 if no hits found
			// else echo 0;
		}
	}


	/**
	 * adds sort feature to "hit counter" column
	 * 
	 * @param  [Array] $columns [wp columns]
	 * @return [Array]          [Added "hit counter" column to columns]
	 */
	public function sort_column($columns) {
		$columns['em_counter'] = 'em_counter';
		return $columns;
	}


	/**
	 * Tells wp to sort by correct meta value _when_ sorting "hit counter" column
	 * 
	 * @param  [Array] $vars [WP Query var]
	 * @return [Array]       [added correct info to WP Query]
	 */
	public function column_orderby($vars) {

		// only fixing query when query is sorting by "hit counter"
	    if (isset($vars['orderby']) && 'em_counter' == $vars['orderby'])
	        $vars = array_merge($vars, array(
	            'meta_key' => 'em_counter',
	            'orderby' => 'meta_value_num'
	        ));
	 
	    return $vars;
	}


	/**
	 * Registering page visits of pages 
	 * that only contains one page
	 * or when there is no user logged in on wp
	 *
	 * TODO: deprecate is_singular and move call to the_loop instead of after html echo?
	 * 
	 * @return [void]
	 */
	public function do_counter() {

		// do nothing if user is logged in, or if page consists of mulitple pages/posts
		if (is_user_logged_in() || !is_singular()) return;

		global $post;

		// getting current hit counter
		$meta = get_post_meta($post->ID, 'em_counter');

		if (isset($meta[0])) $meta = $meta[0];
		else $meta = 0;

		// updating hit counter
		update_post_meta($post->ID, 'em_counter', intval($meta) + 1);
	}
}
<?php 

final class Emtheme_sitemap {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->admin_hooks();
	}

	private function admin_hooks() {

		// add meta box on posts
		add_action( 'add_meta_boxes', array($this, 'add_meta_box'));

		// meta save
		add_action('save_post', array($this, 'save'));

		// add_action('publish_post', array($this, 'add_sitemap'));

		add_filter('sitemap_meta', array($this, 'types_sitemap'));
		// add save sitemap.xml on update on types of posts that has a filter

	}

	public function types_sitemap($data) {

		array_push($data, 'post', 'page');

		return $data;
	}

	public function add_meta_box() {

		$types = apply_filters('sitemap_meta', []);

		add_meta_box(
			'theme_sitemap_meta',
			'Sitemap Options',
			array($this, 'sitemap_callback'),
			$types,
			'side',
			'low'
		);
	}

	public function sitemap_callback($post) {
		wp_nonce_field('sm'.basename(__FILE__), 'sm_nonce');

		$meta = $this->get_meta('emtheme_sitemap');

		$html = '<h3>Include in sitemap</h3>';

		$html .= '<input type="checkbox" name="emtheme_sitemap[ignore]" id="emtheme_sitemap[ignore]"'.($meta['ignore'] ? ' checked' : '').'>
				 <label for="emtheme_sitemap[ignore]">Don\'t add to sitemap.</label>';

		$html .= '<h3>Update frequency</h3>';
		$html .= '<div>
					<input class="emtheme-radio" type="radio" id="emtheme_sitemap[daily]" name="emtheme_sitemap[update]" value="daily"'.($meta['update'] == 'daily' ? ' checked' : '').'>
					<label for="emtheme_sitemap[daily]">Daily</label>
				   </div>';

		$html .= '<div><input class="emtheme-radio" type="radio" id="emtheme_sitemap[weekly]" name="emtheme_sitemap[update]" value="weekly"'.($meta['update'] == 'weekly' ? ' checked' : '').'>
					<label for="emtheme_sitemap[weekly]">Weekly</label>
				  </div>';
		
		$html .= '<div><input class="emtheme-radio" type="radio" id="emtheme_sitemap[monthly]" name="emtheme_sitemap[update]" value="monthly"'.(($meta['update'] == 'monthly' || !$meta['update']) ? ' checked' : '').'>
					<label for="emtheme_sitemap[monthly]">Monthly</label>
				  </div>';

		echo $html;
	}

	public function save($post_id) {

		// is on admin screen
		if (!is_admin()) return;

		// user is logged in and has permission
		if (!current_user_can('edit_posts')) return;

		// nonce is sent
		if (!isset($_POST['sm_nonce'])) return;

		// nonce is checked
		if (!wp_verify_nonce($_POST['sm_nonce'], 'sm'.basename(__FILE__))) return;

		if  (isset($_POST['emtheme_sitemap'])) update_post_meta($post_id, 'emtheme_sitemap', $this->sanitize($_POST['emtheme_sitemap']));

		$this->add_sitemap();
		// wp_publish_post($post_id);
	}

	private function get_meta($key) {
		global $post;

		$meta = get_post_meta($post->ID, $key);

		if (!isset($meta[0])) return [];

		return $meta[0];
	}

	private function sanitize($data) {
		if (!is_array($data)) return sanitize_text_field($data);

		$d = [];
		foreach($data as $key => $value)
			$d[$key] = $this->sanitize($value);

		return $d;
	}


	/**
	 * adds sitemap when specificed post type gets updated.
	 * (post type will have the "sitemap options" meta box)
	 */
	public function add_sitemap() {

      	$type = apply_filters('sitemap_meta', []);

        $postsForSitemap = get_posts(array(
            'numberposts' => -1,
            'orderby' => 'modified',
            'post_type'  => $type,
            'order'    => 'DESC'
        ));

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $site_url = site_url('/');

        foreach($postsForSitemap as $post) {
            setup_postdata($post);

            $meta = get_post_meta($post->ID, 'emtheme_sitemap');

            $meta = (isset($meta[0])) ? $meta[0] : [];

            if ($meta['ignore']) {
            	wp_reset_postdata();
            	continue;
         	}

            $freq = 'monthly';

            if ($meta['update'])
            switch ($meta['update']) {
            	case 'daily': $freq = 'daily'; break;
            	case 'weekly': $freq = 'weekly'; break;
            }

            $postdate = explode( " ", $post->post_modified );

            $sitemap .=  '<url><loc>'.get_permalink($post->ID).'</loc><lastmod>'.$postdate[0].'</lastmod><changefreq>'.$freq.'</changefreq></url>';
            
            wp_reset_postdata();
          }

        $sitemap .= '</urlset>';

        $fp = fopen(ABSPATH.'sitemap.xml', 'w');

        fwrite($fp, $sitemap);
        fclose($fp);


	}

}
<?php 


final class Emtheme_seo {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		
		if (!is_admin()) $this->wp_hooks();

		if (is_admin()) $this->admin_hooks();
	}

	private function admin_hooks() {
		add_action('add_meta_boxes', array($this, 'add_meta_box'));
		add_filter('seo_meta', array($this, 'add_seo_meta'));
		add_action('save_post', array($this, 'save'));
	}

	private function wp_hooks() {
		add_action('wp_head', array($this, 'add_head'));
	}

	public function add_meta_box() {

		$types = apply_filters('seo_meta', []);

		add_meta_box(
			'theme_seo_meta',
			'SEO Options',
			array($this, 'seo_callback'),
			'page'
		);
	}

	public function seo_callback($post) {
		wp_nonce_field('seo'.basename(__FILE__), 'seo_nonce');

		$html .= '<div class="emtheme-seo-container">';

		$html .= '<div>';
		$html .= '<h1>SERP apperance</h1>';
		$html .= '<div>Custom Title <input type="text" name="emtheme_seo[custom_title]" value="'.esc_attr($this->get_meta('custom_title')).'"></div>';
		$html .= '<div>Meta Description<input type="text" name="emtheme_seo[meta_description]" value="'.esc_attr($this->get_meta('meta_description')).'"></div>';
		$html .= '</div>';

		$html .= '<div>';
		
		$html .= '<h1>Web Crawlers:</h1>';
		$html .= '<div>Canonical<input type="text" name="emtheme_seo[canonical]" value="'.esc_attr($this->get_meta('canonical')).'"></div>';

		// $html .= '<div class="emtheme-seo-crawlers">
		// 			<label for="em_seo_canonical">Canonical</label> 
		// 			<input id="em_seo_canonical" type="checkbox" name="emtheme_seo[canonical]"'.($this->get_meta('canonical') ? ' checked' : '').'>
		// 		  </div>';
		
		$html .= '<div class="emtheme-seo-crawlers">
					<label for="em_seo_nofollow">Nofollow</label> 
					<input id="em_seo_nofollow" type="checkbox" name="emtheme_seo[nofollow]"'.($this->get_meta('nofollow') ? ' checked' : '').'>
				  </div>';
		
		$html .= '<div class="emtheme-seo-crawlers">
					<label for="em_seo_noindex">Noindex</label> 
					<input id="em_seo_noindex" type="checkbox" name="emtheme_seo[noindex]"'.($this->get_meta('noindex') ? ' checked' : '').'>
				  </div>';
		
		$html .= '<div class="emtheme-seo-crawlers">
					<label for="em_seo_noimageindex">Noimageindex</label> 
					<input id="em_seo_noimageindex" type="checkbox" name="emtheme_seo[noimageindex]"'.($this->get_meta('noimageindex') ? ' checked' : '').'>
				  </div>';
		
		$html .= '<div class="emtheme-seo-crawlers">
					<label for="em_seo_noarchive">Noarchive</label> 
					<input id="em_seo_noarchive" type="checkbox" name="emtheme_seo[noarchive]"'.($this->get_meta('noarchive') ? ' checked' : '').'>
				  </div>';
		
		$html .= '</div>';
		

		$html .= '<div>';
		$html .= '<h1>Social media link apperance</h1>';
		$html .= '<div>Title <input type="text" name="emtheme_seo[soc_title]" value="'.esc_attr($this->get_meta('soc_title')).'"></div>';
		// $html .= '<div>Image</div><input type="text" name="emtheme_seo[soc_image]" value="'.esc_attr($this->get_meta('soc_image')).'">';
		// $html .= '<div>Site name <input type="text" name="emtheme_seo[soc_sitename]" value="'.esc_attr($this->get_meta('soc_sitename')).'"></div>';
		// $html .= '<div>Description <input type="text" name="emtheme_seo[soc_description]" value="'.esc_attr($this->get_meta('soc_description')).'"></div>';
		$html .= '</div>';
		
		$html .= '</div>';


		echo $html;
	}

	public function add_seo_meta($data) {

		array_push($data, 'page', 'post');

		return $data;
	}

	private function get_meta($data) {
		global $post;

		$meta = get_post_meta($post->ID, 'emtheme_seo');
		if (!isset($meta[0])) return false;
		else return $meta[0][$data];

	}

	public function save($post_id) {

		// is on admin screen
		if (!is_admin()) return;

		// user is logged in and has permission
		if (!current_user_can('edit_posts')) return;

		// nonce is sent
		if (!isset($_POST['seo_nonce'])) return;

		// nonce is checked
		if (!wp_verify_nonce($_POST['seo_nonce'], 'seo'.basename(__FILE__))) return;

		if  (isset($_POST['emtheme_seo'])) update_post_meta($post_id, 'emtheme_seo', $this->sanitize($_POST['emtheme_seo']));

	}


	private function sanitize($data) {
		if (!is_array($data)) return sanitize_text_field($data);

		$d = [];
		foreach($data as $key => $value)
			$d[$key] = $this->sanitize($value);

		return $d;
	}


	/**
	 * Echoing into head element
	 */
	public function add_head() {
		global $post;

		// only set meta if the page is showing only one post/page
		if (!is_singular()) return;
		
		$meta = get_post_meta($post->ID, 'emtheme_seo');

		if (!isset($meta[0])) return;

		$meta = $meta[0];

		// title and meta description
		if (isset($meta['custom_title'])) echo '<title>'.esc_html($meta['custom_title']).'</title>';
	
		if (isset($meta['meta_description'])) echo '<meta name="description" content="'.esc_html($meta['meta_description']).'">';
	

		// OG meta
		$thumbnail = get_the_post_thumbnail_url($post);

		if ($thumbnail) echo '<meta name="og:image" content="'.esc_attr($thumbnail).'">';

		if (isset($meta['soc_title'])) echo '<meta name="og:title" content="'.esc_attr($meta['soc_title']).'">';

		echo '<meta property="og:type" content="website">';

		echo '<meta property="og:url" content="'.esc_attr(get_permalink($post)).'">';


		// robots
		if (isset($meta['canonical']) && $meta['canonical'] != '') echo '<meta name="canonical" content="'.esc_attr($meta['canonical']).'">';
		
		$content = [];

		if (isset($meta['nofollow'])) array_push($content, 'nofollow');
		if (isset($meta['noindex'])) array_push($content, 'noindex');
		if (isset($meta['noimageindex'])) array_push($content, 'noimageindex');
		if (isset($meta['noarchive'])) array_push($content, 'nofollow');

		if (sizeof($content) > 0 ) echo '<meta name="robots" content="'.implode(' ', $content).'">';

	}

}
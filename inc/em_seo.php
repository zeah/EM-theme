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
		$html .= '<div>Description <input type="text" name="emtheme_seo[soc_description]" value="'.esc_attr($this->get_meta('soc_description')).'"></div>';
		// $html .= '<div>Description <textarea name="emtheme_seo[soc_description]" value="'.esc_attr($this->get_meta('soc_description')).'"></textarea></div>';
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
		if (!is_singular())  {
			if (get_bloginfo('name')) echo '<title>'.esc_html(get_bloginfo('name')).'</title>';
			return;
		}
		
		// getting meta
		$meta = get_post_meta($post->ID, 'emtheme_seo');

		if (!isset($meta[0])) $meta = [];
		else $meta = $meta[0];


		// to be echoed
		$html = '';

		// title and meta description
		if (isset($meta['custom_title']) && $meta['custom_title'] != '') $html .= '<title>'.esc_html($meta['custom_title']).'</title>';
		else $html .= '<title>'.esc_html(get_bloginfo('name')).'</title>';

		if (isset($meta['meta_description']) && $meta['meta_description'] != '') $html .= '<meta name="description" content="'.esc_html($meta['meta_description']).'">';
	

		// OG meta

		// image
		$thumbnail = get_the_post_thumbnail_url($post);
		if ($thumbnail) $html .= '<meta name="og:image" content="'.esc_attr($thumbnail).'">';

		// if no thumbnail set, then use site logo
		elseif (function_exists('the_custom_logo')) {
			$logo = get_theme_mod('custom_logo');
			$logo = wp_get_attachment_image_src($logo, 'full');

			if (isset($logo[0])) $html .= '<meta name="og:image" content="'.esc_attr($logo[0]).'">';
		}

		// title
		if (isset($meta['soc_title']) && $meta['soc_title'] != '') $html .= '<meta name="og:title" content="'.esc_attr($meta['soc_title']).'">';
		
		// if social title not set, use custom title or page title
		elseif (isset($meta['custom_title']) && $meta['custom_title'] != '') $html .= '<meta name="og:title" content="'.esc_attr($meta['custom_title']).'">';
		
		// if custom or page title not set, then use site name
		else $html .= '<title>'.esc_html(get_bloginfo('name')).'</title>';

		// type
		$html .= '<meta property="og:type" content="website">';

		// url
		$html .= '<meta property="og:url" content="'.esc_attr(get_permalink($post)).'">';

		// site name
		$html .= '<meta property="og:site_name" content="'.esc_attr(get_bloginfo('name')).'">';

		// description
		if (isset($meta['soc_description']) && $meta['soc_description'] != '') $html .= '<meta property="og:description" content="'.esc_attr($meta['soc_description']).'">';

		// robots
		
		// web crawlers
		if (isset($meta['canonical']) && $meta['canonical'] != '') $html .= '<link rel="canonical" href="'.esc_attr($meta['canonical']).'">';
		else $html .= '<link rel="canonical" href="'.esc_attr(get_permalink($post)).'">';

		$content = [];

		if (isset($meta['nofollow'])) array_push($content, 'nofollow');
		if (isset($meta['noindex'])) array_push($content, 'noindex');
		if (isset($meta['noimageindex'])) array_push($content, 'noimageindex');
		if (isset($meta['noarchive'])) array_push($content, 'nofollow');

		if (sizeof($content) > 0 ) $html .= '<meta name="robots" content="'.implode(' ', $content).'">';

		echo $html;
	}

}
<?php 

/**
 * 
 */
final class Emtheme_page_seo {
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
		add_action('admin_footer-post.php', array($this, 'admin_scripts'));
	}

	private function wp_hooks() {
		remove_action( 'wp_head', 'rel_canonical' );
		add_action('wp_head', array($this, 'add_head'));
	}

	public function add_meta_box() {

		$types = get_post_types(['public' => true]);
      	unset($types[array_search('attachment', $types)]);

		add_meta_box(
			'theme_seo_meta',
			'SEO Options',
			array($this, 'seo_callback'),
			$types,
			'advanced',
			'low'
		);

		add_meta_box(
			'theme_titlepage',
			'Hide page name as on-page title',
			array($this, 'titlepage_callback'),
			$types,
			'side'

		);
	}

	public function seo_callback($post) {
		wp_nonce_field('seo'.basename(__FILE__), 'seo_nonce');

		$html .= '<div class="emtheme-seo-container">';

		$html .= '<div style="width: 550px">';
		$html .= '<h1>SERP apperance</h1>';
		$html .= '<div style="display: flex; flex-wrap: wrap;"><div style="width: 100%">Custom Title</div><input class="theme-seo-title" style="flex: 1; margin: 0" type="text" name="emtheme_seo[custom_title]" value="'.esc_attr($this->get_meta('custom_title')).'"><input style="width: 40px; color: black; border: none;" class="theme-seo-title-input" disabled></div>';

		$html .= '<div>Meta Description<br><textarea class="theme-seo-metadesc" name="emtheme_seo[meta_description]" style="width: 100%; height: 10rem;">'.esc_html($this->get_meta('meta_description')).'</textarea><input style="width: 40px; color: black; border: none;" class="theme-seo-metadesc-input"></div>';

		// $html .= '<div>Meta Description<input type="text" name="emtheme_seo[meta_description]" value="'.esc_attr($this->get_meta('meta_description')).'"></div>';
		$html .= '</div>';

		$html .= '<div>';
		
		$html .= '<h1>Web Crawlers:</h1>';
		$html .= '<div class="emtheme-seo-canonical">Canonical<input type="text" name="emtheme_seo[canonical]" value="'.esc_attr($this->get_meta('canonical')).'"></div>';

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
		$html .= '<div class="emtheme-seo-soc">Title <input type="text" name="emtheme_seo[soc_title]" value="'.esc_attr($this->get_meta('soc_title')).'"></div>';
		// $html .= '<div>Image</div><input type="text" name="emtheme_seo[soc_image]" value="'.esc_attr($this->get_meta('soc_image')).'">';
		// $html .= '<div>Site name <input type="text" name="emtheme_seo[soc_sitename]" value="'.esc_attr($this->get_meta('soc_sitename')).'"></div>';
		$html .= '<div class="emtheme-seo-soc">Description <input type="text" name="emtheme_seo[soc_description]" value="'.esc_attr($this->get_meta('soc_description')).'"></div>';
		// $html .= '<div>Description <textarea name="emtheme_seo[soc_description]" value="'.esc_attr($this->get_meta('soc_description')).'"></textarea></div>';
		$html .= '</div>';
		
		$html .= '</div>';

		$html .= '<div><h1>Structured Data</h1>';

		global $post;
		$struc_data = get_post_meta($post->ID, 'struc_data');
		if (!$struc_data[0]) $struc_data = ''; 
		else $struc_data = $struc_data[0];

		if ($struc_data == '') $d = '';
		else { 
			$d = json_encode(json_decode($struc_data), JSON_PRETTY_PRINT);
			$d = str_replace('\\', '', $d);

			if ($d == 'null') $d = 'ERROR IN CODE '.$struc_data; 
		}

		$html .= '<textarea style="width: 400px; height: 400px;" name="struc_data">'.$d.'</textarea>';

		$html .= '</div>';

		echo $html;
	}


	public function titlepage_callback($post) {
		$meta = get_post_meta($post->ID, 'theme_showtitle');

		if (isset($meta[0])) $meta = $meta[0];
		else $meta = false;
		// echo print_r($_POST['theme_showtitle'], true);

		echo '<input type=checkbox name="theme_showtitle" id="theme-showtitle"'.($meta ? ' checked' : '').'><label for="theme-showtitle">Hide Title</label>';
	}

	/**
	 * Filter for which type of posts to add seo meta box for.
	 * 
	 * @param [type] $data [description]
	 */
	public function add_seo_meta($data) {

		array_push($data, 'page', 'post');

		return $data;
	}




	/**
	 * getting emtheme_seo meta data
	 * 
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function get_meta($data) {
		global $post;

		$meta = get_post_meta($post->ID, 'emtheme_seo');
		if (!isset($meta[0])) return false;
		else return $meta[0][$data];

	}




	/**
	 * saving seo meta data
	 * 
	 * @param  [type] $post_id [description]
	 * @return [type]          [description]
	 */
	public function save($post_id) {

		// is on admin screen
		if (!is_admin()) return;

		// user is logged in and has permission
		if (!current_user_can('edit_posts')) return;

		// nonce is sent
		if (!isset($_POST['seo_nonce'])) return;

		// nonce is checked
		if (!wp_verify_nonce($_POST['seo_nonce'], 'seo'.basename(__FILE__))) return;

		if (isset($_POST['emtheme_seo'])) update_post_meta($post_id, 'emtheme_seo', $this->sanitize($_POST['emtheme_seo']));
		if (isset($_POST['struc_data'])) update_post_meta($post_id, 'struc_data', $this->sanitize($_POST['struc_data']));

		if (isset($_POST['theme_showtitle'])) update_post_meta($post_id, 'theme_showtitle', $this->sanitize($_POST['theme_showtitle']));
		else update_post_meta($post_id, 'theme_showtitle', '');
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
		else $html .= '<title>'.esc_html($post->post_title).'</title>';

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
		if (isset($meta['noarchive'])) array_push($content, 'noarchive');

		if (sizeof($content) > 0 ) $html .= '<meta name="robots" content="'.implode(' ', $content).'">';

		echo $html;
	}


	/**
	 * gets called in index.php inside The Loop
	 *
	 * echoes json-ld structured data retrieved from a post's meta data
	 */
	public function add_footer() {
		// if (!is_singular()) return;

		global $post;

		$meta = get_post_meta($post->ID, 'struc_data');
		// wp_die('<xmp>'.print_r($meta, true).'</xmp>');
		if (!$meta[0]) return;

		$meta = $meta[0];

		$meta = json_encode(json_decode($meta), JSON_PRETTY_PRINT);
		$script = '<script type="application/ld+json">'.str_replace('\\', '', $meta).'</script>';

		// only print if it is json
		if ($meta && $meta != 'null') echo $script;

	}

	public function admin_scripts() {
		echo '<script>

		(() => { 
			let title = document.querySelector(".theme-seo-title");
			let titleCounter = document.querySelector(".theme-seo-title-input");

			let meta = document.querySelector(".theme-seo-metadesc");
			let metaCounter = document.querySelector(".theme-seo-metadesc-input");

			// if (!title || !titleCounter || !meta || !metaCounter) return;

			titleCounter.value = title.value.length;
			metaCounter.value = meta.value.length;
			
			title.addEventListener("input", () => titleCounter.value = title.value.length);
			meta.addEventListener("input", () => metaCounter.value = meta.value.length);
		})();

		</script>';
	}

}
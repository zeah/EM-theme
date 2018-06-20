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
		add_action('save_post', array($this, 'save'));

		// add save function

		// add save sitemap.xml on update on types of posts that has a filter

	}

	public function add_meta_box() {
		add_meta_box(
			'theme_sitemap_meta',
			'Sitemap Options',
			array($this, 'sitemap_callback'),
			'post',
			'side',
			'low'
		);
	}

	public function sitemap_callback($post) {
		wp_nonce_field('sm'.basename(__FILE__), 'sm_nonce');

		$meta = $this->get_meta('emtheme_sitemap');

		$html = '<input type="checkbox" name="emtheme_sitemap[ignore]" id="emtheme_sitemap[ignore]"'.($meta['ignore'] ? ' checked' : '').'>
				 <label for="emtheme_sitemap[ignore]">Don\'t add to sitemap.</label>';

		$html .= '<div>
					<input class="emtheme-radio" type="radio" id="emtheme_sitemap[daily]" name="emtheme_sitemap[update]" value="daily"'.($meta['update'] == 'daily' ? ' checked' : '').'>
					<label for="emtheme_sitemap[daily]">Daily</label>
				   </div>';

		$html .= '<div><input class="emtheme-radio" type="radio" id="emtheme_sitemap[weekly]" name="emtheme_sitemap[update]" value="weekly"'.($meta['update'] == 'weekly' ? ' checked' : '').'>
					<label for="emtheme_sitemap[weekly]">Weekly</label>
				  </div>';
		
		$html .= '<div><input class="emtheme-radio" type="radio" id="emtheme_sitemap[monthly]" name="emtheme_sitemap[update]" value="monthly"'.($meta['update'] == 'monthly' ? ' checked' : '').'>
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

	}

	private function get_meta($key) {
		global $post;

		$meta = get_post_meta($post->ID, $key);

		if (!isset($meta[0])) return '';

		return $meta[0];
	}

	private function sanitize($data) {
		if (!is_array($data)) return sanitize_text_field($data);

		$d = [];
		foreach($data as $key => $value)
			$d[$key] = $this->sanitize($value);

		return $d;
	}

}
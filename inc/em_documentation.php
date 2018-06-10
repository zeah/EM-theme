<?php 

final class Emtheme_documentation {
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
		if (!is_admin()) return;

		add_action('admin_menu', array($this, 'add_menu'));
		add_filter('emtheme_doc', array($this, 'add_doc'));

	}

	public function add_menu() {
		add_menu_page('Documentations', 'Documentation', 'manage_options', 'em-documentation-page', array($this, 'doc_callback'), 'none', 261);
	}

	public function doc_callback() {

		$data = apply_filters('emtheme_doc', []);

		$html = '<div class="emtheme-documentation">';

		foreach ($data as $key) {
			if (isset($key['index'])) $html .= '<div><ul>'._x(sprintf('%s', $key['index']), 'An index for the documentation.').'</ul></div>';

			if (isset($key['title'])) $html .= '<div>'._x(sprintf('%s', $key['title']), 'Title for the documentation.').'</div>';

			if (isset($key['info'])) $html .= '<div class="emtheme-documentation-info"><ul>'._x(sprintf('%s', $key['info']), 'documentation text').'</ul></div>';
		}


		$html .= '</div>';

		echo wp_kses_post($html);

		// echo '<script>window.addEventListener("hashchange", function () { console.log("hiya"); window.scrollTo(window.scrollX, window.scrollY - 50); });</script>';
	}

	public function add_doc($data) {

		$data['theme']['title'] = '<h1>EM Theme</h1>';

		$data['theme']['index'] = '<li>
									  <h2><a href="#theme-templates">Theme Templates</a></h2>
									  <ul>
										 <li><a href="#theme-template-default">Default</a></li>
										 <li><a href="#theme-template-redirect">Redirect</a></li>
									  </ul>
								   </li>
								   <li>
									  <h2><a href="#theme-templates">Theme Customizer</a></h2>
									  <ul>
										 <li><a href="#theme-customizer-color">Color</a></li>
										 <li><a href="#theme-customizer-font">Font</a></li>
									  </ul>
								   </li>
								   ';

		$data['theme']['info'] = '<li>
								 	<h2 id="theme-templates">Theme Templates</h2>
								 	<ul>
								 		<li id="theme-template-default">Default
								 		<ul>
								 			<li>Widget locations</li>
								 		</ul></li>
								 	</ul>
								  </li>';



		// $dat = new stdClass;
		// $dat->theme->title = 'Emtheme';

		return $data;
	}
}
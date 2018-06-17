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
									  <h2><a href="#theme-customizer">Theme Customizer</a></h2>
									  <ul>
										 <li><a href="#theme-customizer-siteidentity">Site Identity</a></li>
										 <li><a href="#theme-customizer-color">Color</a></li>
										 <li><a href="#theme-customizer-font">Font</a></li>
										 <li><a href="#theme-customizer-privacy">Privacy</a></li>
									  </ul>
								   </li>
								   <li>
									  <h2><a href="#theme-menu">Menu</a></h2>
									  <ul>
										 <li><a href="#theme-menu-locations">Locations</a></li>
										 <li><a href="#theme-menu-attributes">Attributes</a></li>
									  </ul>
								   </li>
								   <li>
									  <h2><a href="#theme-features">Features</a></h2>
									  <ul>
										 <li><a href="#theme-features-privacy">Privacy Window</a></li>
										 <li><a href="#theme-features-goup">Go Up Button</a></li>
									  </ul>
								   </li>
								   ';

		$data['theme']['info'] = _x('<li>
								 	<h2 id="theme-templates">Theme Templates</h2>
								 	<ul>
								 		<li id="theme-template-default">Default
								 		<ul>
								 			<li>Widget locations</li>
								 		</ul></li>
								 	</ul>
								  </li>
								  <li>
									  <h2 id="theme-customizer">Theme Customizer</h2>
									  <ul>
										 <li id="theme-customizer-siteidentity"><b>Site Identity</b>
										 	<ul>
										 		<li><b>Header</b>
										 			<p><b>Logo:</b> Add image to header.<br>Shown in navbar on small device.</p>
										 			
										 			<p><b>Site title:</b> Default values: 40px font-size and Roboto font-family.
										 			<br>Shown in navbar on small device.</p>
										 			
										 			<p><b>Tagline:</b> Is shown indented below site title.
										 			<br>Not shown on small device.</p>
										 			
										 			<p><b>Search:</b> Is shown as icon when inactive and shares color with title text.</p>

										 			<p><b>Show/Hide:</b> Toggle header (logo, title, tagline, search box) on or off.</p>
										 		</li>
										 		<li><b>Footer Info</b>
										 		<p>3 boxes of content added on a line in footer element at bottom of every page.
										 		<br>Boxes are not labeled on front-end, and can contain any html code that a normal post would accept.
										 		<br>A line break (hitting enter) in the footer editor will return a br element on front-end.</p>
										 		</li>
										 	</ul>
										 </li>

										 <li id="theme-customizer-color"><b>Color</b>
										 	<ul>
										 		<li><b>Header</b>
										 		<p>Font family, font size and color is customizable.
										 		<br>Search and tagline shares color with title and family with content.</p>
										 		</li>
										 		<li><b>Nav</b>
										 		<p>If Navbar -> background/hover middle/bottom is set then the background/hover will be a linear-gradient.</p>
										 		</li>
										 		<li><b>Content</b>
										 			<p>Content is post, pages and widgets.</p>
										 		</li>
										 		<li><b>Footer</b>
										 			<p>Links in footer share color with text in footer.</p>
										 		</li>
										 	</ul>
										 </li>
										 <li id="theme-customizer-font"><b>Font</b>
										 	<p>Theme Customizer is using fonts retrieved from google. <a href="https://fonts.google.com">Google Fonts</a>
										 	<br>The font family selection is sorted by popular use.
										 	<br>The font weight selection is matching the available weights for chosen font.</p>
										 	<ul>
										 		<li><b>Header</b>
										 		<p>Sets site title font only.</p>
										 		</li>
										 		<li><b>Nav</b>
										 		<p>Sets all nav items.</p>
										 		</li>
										 		<li><b>Content</b>
										 		<p>Sets content (including page/post titles), tagline, search, widgets and footer.
										 		<br>Line-height setting is only for content.</p>
										 		</li>
										 	</ul>
										 </li>
										 <li id="theme-customizer-privacy"><b>Privacy</b>
										 <p>Shows a fixed element at the bottom of screen.
										 <br>The editor accepts the same html as a post do. But &lt;br&gt; or &lt;p&gt; tag is needed for new lines.
										 <br>When button is clicked, it will not show up again for the user on their following vists for the next 120 days.
										 <br>The cookie named "cookieAccept" is used to track the button click.</p></li>
									  </ul>
								   </li>
								   <li>
									  <h2 id="theme-menu">Menu</h2>
									  <ul>
										 <li><h3 id="theme-menu-locations">Locations</h3>
										 <ul>
										 	<li><b>Head</b>
										 	<p>If no menu assigned, then theme will create a menu from pages. If "Don\'t add to menu" is selected, 
										 	then theme will not add it to the generated menu.<br> 
										 	The menu, both custom and theme generated is sorted by menu order, with lowest value put on the left.</p></li>
										 </ul>
										 </li>
										 <li><h3 id="theme-menu-attributes">Attributes</h3><br>Option to show these attributes for editing is "Screen Options" in 
										 Apperance -> Menu page or cog icon in customize page. 
										 	<ul>
										 		<li><b>Link Target</b><p>If set then link will open in a new tab.<br> All menu links will regardless 
										 		have rel=noopener for security reason.</p></li>
										 		<li><b>Title Attribute</b><p>Value set will show as link tooltip.</p></li>
										 		<li><b>CSS classes</b><p>Adds css classes to the a-tag in menu. Which can be styled from customize -> 
										 		additional css.</p></li>
										 		<li><b>Link Relationship (XFN)</b><p>XFN stands for xhtml friends network and it is meant to represent 
										 		human relationship using links.<br>
										 		<a href="https://codex.wordpress.org/Defining_Relationships_with_XFN">Read More</a></p></li>
										 		<li><b>Description</b><p>Is shown as extra text in the same menu item, but in the upper right corner 
										 		on hover. (best fit in sub-menus)</p></li>
										 	</ul>
										 </li>
									  </ul>
								   </li>
								   <li>
									  <h2 id="theme-features">Features</h2>
									  <ul>
										 <li id="theme-features-privacy"><b>Privacy Window</b><p>Visitors will get an element centered at bottom of 
										 screen with customizable text and colors. When visitor clicks the button (default text: "OK") they will not 
										 see the element again.</p></li>
										 
										 <li id="theme-features-goup"><b>Go Up Button</b><p>Is shown in bottom right corner if user has scrolled more 
										 than 100px from the top. Colors can be set from customizer.</p></li>
									  </ul>
								   </li>	

								   ', 'documentation body' ,'emtheme');


		return $data;
	}
}
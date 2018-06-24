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
										 <li><a href="#theme-customizer-layout">Layout</a></li>
										 <li><a href="#theme-customizer-privacy">Privacy</a></li>
									  </ul>
								   </li>
								   <li>
								   		<h2><a href="#theme-editor">Post Editor</a></h2>
								   		<ul>
								   			<li><a href="#theme-editor-style">Editor apperance</a></li>
								   			<li><a href="#theme-editor-seo">SEO meta</a></li>
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
									  <h2><a href="#theme-features">Misc. Features</a></h2>
									  <ul>
										 <li><a href="#theme-features-privacy">Privacy Window</a></li>
										 <li><a href="#theme-features-goup">Go Up Button</a></li>
										 <li><a href="#theme-features-sitemap">Sitemap.xml</a></li>
										 <li><a href="#theme-features-searchpage">Internal SERP</a></li>
										 <li><a href="#theme-features-notfound">Page not found</a></li>
									  </ul>
								   </li>
								   <li>
								   	  <h2><a href="">Cache</a></h2>
								   	  <ul>
								   	  	<li></li>
								   	  </ul>
								   </li>
								   ';

		$data['theme']['info'] = _x('<li id="theme-templates">
								 	<h2>Theme Templates</h2>
								 	<ul>
								 		<li id="theme-template-default">Default
								 		<ul>
								 			<li>Widget locations</li>
								 		</ul></li>
								 	</ul>
								  </li>
								  <li id="theme-customizer">
									  <h2>Theme Customizer</h2>
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
										 		<li><b>Search box (header)</b>
										 		<p>Uses title font family and navbar font size.</p>
										 		</li>
										 		<li><b>Search box (navbar)</b>
										 		<p>Uses navbar font family and navbar font size.</p>
										 		</li>
										 	</ul>
										 </li>

										 <li id="theme-customizer-layout"><b>Layout</b>
										 	<p>Show or hide elements and sets dimensions.</p>
										 	<ul>
										 	<li><b>Search</b><p>Search feature can be toggled on or off in header and navbar.
										 	<br>It is also a widget who has the same design as the toggleable search</p></li>
										 	<li><b>Navbar Height</b><p>Actually sets the top/bottom padding of the navbar.</p></li>
										 	<li><b>Navbar Logo</b><p>Shows Logo (from Site Identity) on the left most side of the navbar.
										 	<br>The logo is shown as a background image and will show it\'s actual width, 
										 	<br>but will have a cropped height to fit on the navbar (it will crop top and bottom.)
										 	<br>So the image chosen should fit the navbar as it will not be re-sized.</p></li>
										 	</ul>
										 </li>

										 <li id="theme-customizer-privacy"><b>Privacy</b>
										 <p>Shows a fixed element at the bottom of screen.
										 <br>The editor accepts the same html as a post do. But &lt;br&gt; or &lt;p&gt; tag is needed for new lines.
										 <br>When button is clicked, it will not show up again for the user on their following vists for the next 120 days.
										 <br>The cookie named "cookieAccept" is used to track the button click.</p></li>
									  </ul>
								   </li>

								   <li id="theme-editor">
								   		<h2>Post editor</h2>
								   		<ul>
								   			<li id="theme-editor-style"><b>Editor apperance</b>
								   			<p>The editor uses font-family and font-size set by content font and content size in customizer.</p>
								   			</li>
								   		
								   			<li id="theme-editor-seo"><b>SEO meta</b>
								   			<p>All but title meta tag is only for singular pages.
								   			<br>Pages with more than one post/page will only have title tag set to site name.</p> 
								   			<ul>
								   			<li><b>SERP apperance</b>
								   			<p><b>Custom title</b>
								   			<br>Sets title tag in header, which is shown on the search results pages of search engines and internal search results.
								   			<br>If no custom title is set, then post title will be used. If page is not a singular post, then site name will be shown.
								   			</p>
								   			<p><b>Meta description</b>
								   			<br>Sets page description, which is shown on search engine SERP and internal SERP. 
								   			<br>If not set, then the search engine will generate their own description and on internal SERP the post excerpt will be shown.</p>
								   			</li>
								   			
								   			<li><b>Web crawlers</b>
								   			<p>These settings works as a guide for web crawlers.</p>
								   			<p><b>Canonical</b>
								   			<br>If the page is of duplicate content, then add the link to the original page.
								   			<br>If the page is of original content, then do nothing.
								   			<br>If no value given, then canonical tag will link to the page\'s permalink url.</p>
								   			<p><b>Nofollow</b>
								   			<br>If checked, then web crawlers will not access the links.</p>
								   			<p><b>Noindex</b>
								   			<br>If checked, then search engines will not list the page on its SERP.</p>
								   			<p><b>Noimageindex</b>
								   			<br>If checked, then search engines will not list the images found on page on its image SERP.</p>
								   			<p><b>Noarchive</b>
								   			<br>If checked, then search engines will not an archive of the page.</p>

								   			</li>
								   			
								   			<li><b>Social media link apperance</b>
								   			<p>Meta tags added to pages that are singular. (pages that do no contain multiple posts.)
								   			<br>Meta types listed in ( ) only have default values.</p>
								   			<p><b>Title</b>
								   			<br>Will use first available of: Social title -> custom title -> page title -> site name.</p>
								   			<p><b>Description</b>
								   			<br>Will only use data from social media description field.</p>
								   			<p><b>(Site name)</b>
								   			<br>Will be auto-generated with site name from customizer.</p>
								   			<p><b>(Image)</b>
								   			<br>Will use thumbnail from post or if not availble then site logo from customizer.</p>
								   			<p><b>(Type)</b>
								   			<br>Is set to "website"</p>
								   			<p><b>(Url)</b>
								   			<br>Is set to the permalink of post.</p>

								   			</li>
								   			
								   			<li id="theme-editor-struc"><b>Structured Data</b>

								   			</li>
								   			</ul>
								   			</li>
								   		</ul>
								   	</li>


								   <li id="theme-menu">
									  <h2>Menu</h2>
									  <ul>
										 <li id="theme-menu-locations"><h3>Locations</h3>
										 	<ul>
										 		<li><b>Head</b>
										 		<p>If no menu assigned, then theme will create a menu from pages. If "Don\'t add to menu" is selected, 
										 		then theme will not add it to the generated menu.<br> 
										 		The menu, both custom and theme generated is sorted by menu order, with lowest value put on the left.</p></li>
										 	</ul>
										 </li>
										 
										 <li id="theme-menu-attributes"><h3>Attributes</h3><br>Option to show these attributes for editing is "Screen Options" in 
										 Apperance -> Menu page or cog icon in customize page. 
										 	<ul>
										 		<li><b>Link Target</b>
										 		<p>If set then link will open in a new tab.
										 		<br> All menu links will regardless 
										 		have rel=noopener for security reason.</p>
										 		</li>
										 		
										 		<li><b>Title Attribute</b>
										 		<p>Value set will show as link tooltip.</p>
										 		</li>
										 		
										 		<li><b>CSS classes</b>
										 		<p>Adds css classes to the a-tag in menu. Which can be styled from customize -> 
										 		additional css.</p>
										 		</li>
										 		
										 		<li><b>Link Relationship (XFN)</b><p>XFN stands for xhtml friends network and it is meant to represent 
										 		human relationship using links.
										 		<br><a href="https://codex.wordpress.org/Defining_Relationships_with_XFN">Read More</a></p>
										 		</li>
										 		
										 		<li><b>Description</b>
										 		<p>Is shown as extra text in the same menu item, but in the upper right corner 
										 		on hover. (best fit in sub-menus)</p>
										 		</li>
										 	</ul>
										 </li>

									  </ul>
								   </li>
								   <li id="theme-features">
									  <h2>Features</h2>
									  <ul>
										 <li id="theme-features-privacy"><b>Privacy Window</b><p>Visitors will get an element centered at bottom of 
										 screen with customizable text and colors. When visitor clicks the button (default text: "OK") they will not 
										 see the element again.</p></li>
										 
										 <li id="theme-features-goup"><b>Go Up Button</b><p>Is shown in bottom right corner if user has scrolled more 
										 than 100px from the top. Colors can be set from customizer.</p></li>
									  

										 <li id="theme-features-sitemap"><b>Sitemap.xml</b>
										 <p>When posts and pages <b>with</b> the "Sitemap Options" meta box are updated, then the theme will generate/update a sitemap for web crawlers.
										 <br>Which you can see at your-url/sitemap.xml </p>
										 </li>

										 <li id="theme-features-searchpage"><b>Internal</b>
										 <p>Searches titles and content and returns a SERP page that looks like google\'s.</p>
										 </li>

									  </ul>
								   </li>	

								   ', 'documentation body' ,'emtheme');


		return $data;
	}
}
<?php 


/**
 * Getting css settings from customizer and adding css to style tag in wp head
 */
final class Emtheme_css {
	/* SINGLETON */
	private static $instance = null;

	/* css for colors (background, font, hover) */
	private $colors;

	/* css for fonts (family, size, etc) */
	private $fonts;

	/* padding, toggle element */
	private $layout;

	/* css and text for privacy info window */
	private $privacy;

	/* singleton init function */
	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {

		add_action('wp_enqueue_scripts', array($this, 'get_css'));

	}


	private function css_values() {
		/* getting color css and fixing return */
		$col = get_theme_mod('emtheme_color');
		if (!is_array($col)) $col = [];

		/* getting font css and fixing return */
		$fon = get_theme_mod('emtheme_font');
		if (!is_array($fon)) $fon = [];
		
		/* getting layout settings and fixing return */
		$lay = get_theme_mod('emtheme_layout');
		if (!is_array($lay)) $lay = [];
		
		// COLOR SECTION
		$colors = [];

		// page background color (body tag)
		$colors['background'] = sanitize_hex_color('#'.get_background_color());
		if ($colors['background'] == '') $colors['background'] = '#eee';

		// main background color 
		$colors['main_background'] = $col['main_background'] ? sanitize_hex_color($col['main_background']) : '#fff';
		
		if ($col['main_shadow']) {

			if ($col['main_shadow'] == '') $css = 'none';
			else $css = '0 0 2px '.sanitize_hex_color($col['main_shadow']);

			$colors['main_shadow'] = $css;  
		}
		else $colors['main_shadow'] = 'none';

		// font color in main area
		$colors['main_font'] = $col['main_font'] ? sanitize_hex_color($col['main_font']) : '#000';


		// header font
		$colors['header_font'] = $col['emtop_font'] ? sanitize_hex_color($col['emtop_font']) : '#000';

		// header background
		$colors['header_background'] = $col['emtop_bg'] ? sanitize_hex_color($col['emtop_bg']) : '#fff';

		// search
		$colors['search'] = $col['search'] ? sanitize_hex_color($col['search']) : '#000';
		
		// bg image
		$colors['header_background_image'] = $col['emtop_bg_image'] ? esc_url($col['emtop_bg_image']) : '';
		
		// bg image opacity
		$colors['header_background_image_opacity'] = $col['emtop_bg_image_opacity'] ? esc_html($col['emtop_bg_image_opacity']) : '0.5';

		// navbar font
		$colors['navbar_font'] = $col['navbar_font'] ? sanitize_hex_color($col['navbar_font']) : '#ffffff';
		
		// navbar bg
		// creating linear-gradient if set
		if ($col['nav_bg_top']) {
			$col['nav_bg_top'] = sanitize_hex_color($col['nav_bg_top']);
			$col['nav_bg_middle'] = sanitize_hex_color($col['nav_bg_middle']);
			$col['nav_bg_bottom'] = sanitize_hex_color($col['nav_bg_bottom']);

			$colors['navbar_background'] = 'background-color: '.$col['nav_bg_top'];
			$colors['navbar_background_mobile'] = $col['nav_bg_top'];


			if ($col['nav_bg_middle'] && $col['nav_bg_middle'] != '' && $col['nav_bg_bottom'] && $col['nav_bg_bottom'] != '') 
				$colors['navbar_background'] = "background: linear-gradient(to top, $col[nav_bg_bottom] 0%, $col[nav_bg_middle] 50%, $col[nav_bg_top] 100%)";

			elseif ($col['nav_bg_middle'] && $col['nav_bg_middle'] != '')
				$colors['navbar_background'] = "background: linear-gradient(to top, $col[nav_bg_middle] 50%, $col[nav_bg_top] 100%)";
			
			elseif ($col['nav_bg_bottom'] && $col['nav_bg_bottom'] != '')
				$colors['navbar_background'] = "background: linear-gradient(to top, $col[nav_bg_bottom] 0%, $col[nav_bg_top] 100%)";

		}
		else {  
			$colors['navbar_background'] = 'background-color: #000';
			$colors['navbar_background_mobile'] = '#000';
		}

		// navbar hover
		// creating linear-gradient if set
		if ($col['nav_bg_hover_top']) {

			$col['nav_bg_hover_top'] = sanitize_hex_color($col['nav_bg_hover_top']);
			$col['nav_bg_hover_middle'] = sanitize_hex_color($col['nav_bg_hover_middle']);
			$col['nav_bg_hover_bottom'] = sanitize_hex_color($col['nav_bg_hover_bottom']);

			$colors['navbar_hover'] = 'background-color: '.$col['nav_bg_hover_top'];
		
			if ($col['nav_bg_hover_middle'] && $col['nav_bg_hover_middle'] != '' && $col['nav_bg_hover_bottom'] && $col['nav_bg_hover_bottom'] != '') 
				$colors['navbar_hover'] = "background: linear-gradient(to top, $col[nav_bg_hover_bottom] 0%, $col[nav_bg_hover_middle] 50%, $col[nav_bg_hover_top] 100%)";

			elseif ($col['nav_bg_hover_middle'] && $col['nav_bg_hover_middle'] != '')
				$colors['navbar_hover'] = "background: linear-gradient(to top, $col[nav_bg_hover_middle] 50%, $col[nav_bg_hover_top] 100%)";
			
			elseif ($col['nav_bg_hover_bottom'] && $col['nav_bg_hover_bottom'] != '')
				$colors['navbar_hover'] = "background: linear-gradient(to top, $col[nav_bg_hover_bottom] 0%, $col[nav_bg_hover_top] 100%)";

		}
		else $colors['navbar_hover'] = 'background-color: #353';

		// navbar borders
		$colors['navbar_border'] = $col['navbar_border'] ? 'solid 1px '.sanitize_hex_color($col['navbar_border']) : 'none';


		// submenu font
		$colors['submenu_font'] = $col['submenu_font'] ? sanitize_hex_color($col['submenu_font']) : '#000';
		
		// submenu bg
		$colors['submenu_background'] = $col['submenu_bg'] ? sanitize_hex_color($col['submenu_bg']) : '#eee';
						
		// submenu hover
		$colors['submenu_hover'] = $col['submenu_hover'] ? sanitize_hex_color($col['submenu_hover']) : '#ddd';

		// submenu border
		$colors['submenu_border'] = $col['submenu_border'] ? sanitize_hex_color($col['submenu_border']) : '#bbb';


		// active page marker
		$colors['active'] = $col['navbar_active'] ? sanitize_hex_color($col['navbar_active']).'90' : '#33663390';


		/* FOOTER */
		
		// footer background
		$colors['footer_bg'] = $col['footer_bg'] ? sanitize_hex_color($col['footer_bg']) : '#000';

		// footer font color
		$colors['footer_font'] = $col['footer_font'] ? sanitize_hex_color($col['footer_font']) : '#fff';


		/* GO UP BUTTON */

		// go up background 
		$colors['goup_bg'] = $col['goup_bg'] ? sanitize_hex_color($col['goup_bg']) : '#ccc';

		// go up font color
		$colors['goup_font'] = $col['goup_font'] ? sanitize_hex_color($col['goup_font']) : '#000';

		// privacy css
		$pri = get_theme_mod('theme_privacy');
		
		// privacy window background
		$colors['privacy_bg'] = $pri['bg'] ? sanitize_hex_color($pri['bg']) : '#eee';
		$colors['privacy_font'] = $pri['font'] ? sanitize_hex_color($pri['font']) : '#000';

		$colors['privacy_button_bg'] = $pri['button_bg'] ? sanitize_hex_color($pri['button_bg']) : '#aaa';
		$colors['privacy_button_font'] = $pri['button_font'] ? sanitize_hex_color($pri['button_font']) : '#000';

		

		$this->colors = $colors;




		// FONTS
		$fonts = [];

		// content font family
		$fonts['content_family'] = ($fon['content_family'] && $fon['content_family'] != '') ? esc_html($fon['content_family']) : 'Open Sans';
		
		// content weight
		$fonts = array_merge($fonts, $this->check_weight(($fon['content_weight'] ? $fon['content_weight'] : false), 'content'));

		// content font size
		$fonts['content_size'] = $fon['content_size'] ? floatval($fon['content_size']) / 10 : '1.6';

		// content lineheight
		$fonts['content_lineheight'] = $fon['content_lineheight'] ? esc_html($fon['content_lineheight']) : 1.3;

		// title font family
		$fonts['title_family'] = ($fon['title_family'] && $fon['title_family'] != '') ? esc_html($fon['title_family']) : 'Roboto';

		// title weight
		$fonts = array_merge($fonts, $this->check_weight(($fon['title_weight'] ? $fon['title_weight'] : false), 'title'));

		// title font size
		$fonts['title_size'] = $fon['title_size'] ? floatval($fon['title_size']) / 10 : '4';

		// navbar font family
		$fonts['navbar_family'] = ($fon['navbar_family'] && $fon['navbar_family'] != '') ? esc_html($fon['navbar_family']) : 'Roboto';

		// navbar weight / style
		$fonts = array_merge($fonts, $this->check_weight(($fon['navbar_weight'] ? $fon['navbar_weight'] : false), 'navbar'));

		// navbar font-size
		$fonts['navbar_size'] = $fon['navbar_size'] ? floatval($fon['navbar_size']) / 10 : '2';

		$this->fonts = $fonts;

		// layout
		$layout['navbar_padding'] = $lay['navbar_padding'] ? floatval($lay['navbar_padding']) / 10 : '0.6';
		$layout['navbar_search'] = ($lay['search_navbar_toggle'] && $lay['search_navbar_toggle'] != '') ? $lay['search_navbar_toggle'] : false;
		$layout['goup_toggle'] = $lay['goup_toggle'] ? true : false;

		$this->layout = $layout;

	}

	private function check_weight($weight, $prefix) {
		$data = [];

		if ($weight) {

			if (strpos($weight, 'italic')) 	$data[$prefix.'_style'] = 'italic';
			else 							$data[$prefix.'_style'] = 'normal';
			
			$weight = str_replace('regular', 'normal', $weight);
			$data[$prefix.'_weight'] = str_replace('italic', '', esc_html($weight));
		}
		else {
			$data[$prefix.'_weight'] = 'normal';
			$data[$prefix.'_style'] = 'normal';
		}

		return $data;
	}

	public function get_css() {

		if (get_transient('theme_css')) {
			wp_add_inline_style('style', sanitize_text_field(get_transient('theme_css')));
			return;
		}

		$this->css_values();

		$lay = get_theme_mod('emtheme_layout');
		if (!is_array($lay)) $lay = [];

		$html = '';

		
		if (!$lay['header_toggle'] || $lay['header_toggle'] == '' || is_customize_preview()) $html .= $this->top();

		$html .= $this->navbar();

		$html .= $this->page();

		set_transient('theme_css', $html);

		wp_add_inline_style('style', $html);
	}

	private function top() {
		global $content_width;
		$col = $this->colors;
		$fon = $this->fonts;
		
		$bg = get_theme_mod('theme_background');

		if (!is_array($bg)) $bg = [];
		
		$width = $content_width / 10;

		$css = ".emtheme-header-container { background-color: $col[header_background]; color: $col[header_font]; z-index: 2;}";

		if ($bg['header']) {
		
			$url = $bg['header'];
			$size = $bg['header_size'];
			$pos = $bg['header_position'];
			$repeat = $bg['header_repeat'];

			

			$css .= "\n.emtheme-header-container::after { 
				content: ''; 
				position: absolute; 
				top: 0; left: 0; right: 0; bottom: 0; 
				z-index: -1; 
				background-image: url('".esc_attr($url)."');
				background-size: $bg[header_size];
				background-position: $bg[header_position];
				background-repeat: $bg[header_repeat];
				opacity: ".($bg['header_opacity'] ? floatval($bg['header_opacity']) : '0.5')."
			}";
		}

		$css .= "\n.title-link { color: $col[header_font]; }";
		
		$css .= "\n.emtheme-header-title { font-family: $fon[title_family]; font-size: {$fon[title_size]}rem; }";
		$css .= "\n.emtheme-header-tagline { font-family: $fon[content_family]; font-size: {$fon[content_size]}rem; }";

		$css .= "\n.emtheme-header { width: {$width}rem; }";
		
		$css .= "\n.emtheme-header .emtheme-search-input { color: $col[header_font]; border-bottom: solid 1px $col[header_font]; font-size: {$fon[navbar_size]}rem; }"; 

		

		return $css;
	}

	/**
	 * inline css for navbar
	 * @return {void} echoes to webpage
	 */
	private function navbar() {

		$col = $this->colors;
		$fon = $this->fonts;
		$lay = $this->layout;
		global $content_width;
		$width = $content_width / 10;
		
		$css = '';


		// DESKTOP 
		$css .= "\n@media only screen and (min-width: 1280px) {";

			// content width (1260px)
			$css .= "\n.navbar-container { width: {$width}rem; }";
			
			// hover color on links in submenu 
			$css .= "\n.menu-level-second:hover { background-color: $col[submenu_hover]; }";
			
			// top level hover color
			$css .= "\n.menu-has-child:hover { $col[navbar_hover]; }";
			
			// hover color on links in navbar (hover shouldn't be used on mobile)
			$css .= "\n.menu-link:hover { $col[navbar_hover]; }";
		
		$css .= "\n}";
		
		// MOBILE
		$css .= "\n@media only screen and (max-width: 1279px) {";

		// solid color background for top level menu on mobile
		$css .= "\n.navbar-menu { background-color: $col[navbar_background_mobile]; }";
		
		$css .= "\n}";

		// DESKTOP AND MOBILE 
		
		// full width (or .page-content width) with background color
		$css .= "\n.navbar-background { $col[navbar_background]; }";
		
		// if toggled: show site title on navbar with navbar styling
		$css .= "\n.navbar-title { color: $col[navbar_font]; font-size: {$fon[title_size]}rem; font-family: $fon[title_family]; font-weight: $fon[title_weight]; }";

		// if toggled or on mobile: show search form on navbar or above top level menu (mobile) with navbar styling
		$css .= "\n.navbar-search .emtheme-search-input { background-color: inherit; color: $col[navbar_font]; font-size: {$fon[navbar_size]}rem; border: none; border-bottom: 1px solid {$col[navbar_font]}50; }";

		// visual hightlight when search form has focus
		$css .= "\n.navbar-search .emtheme-search-input:focus { border-bottom: 2px solid $col[navbar_font]; }"; 
		  
		// search icon color on navbar 
		$css .= "\n.navbar-search .emtheme-search-button > .material-icons { color: $col[navbar_font]; }";

		// navbar font color
		$css .= "\n.menu-container { color: $col[navbar_font]; user-select: none;}";

		// border for top level menu
		$css .= "\n.menu-level-top { border-right: $col[navbar_border]; }";
		
		// the UL container for menu
		$css .= "\n.menu-list { display: flex; padding: 0; margin: 0; margin: auto; }";

		// submenu
		$css .= "\n.sub-menu { display: none; padding: 0; margin: 0; background-color: $col[submenu_background]; z-index: 99; color: $col[submenu_font]; border: solid 1px $col[submenu_border]; }";
						
		// LI navbar element containing the link		
		$css .= "\n.menu-item { position: relative; list-style: none; }";

		// navbar link - contains the dimensions 
		$css .= "\n.menu-link { display: flex; align-items: center; height: 100%; box-sizing: border-box; padding: {$lay[navbar_padding]}rem 1.5rem; font-family: \"$fon[navbar_family]\"; font-size: {$fon[navbar_size]}rem; font-weight: $fon[navbar_weight]; text-decoration: none; color: $col[navbar_font]; white-space: nowrap;}";

		// removes right padding for menu items with submenu (down-arrow is added in its place)
		$css .= "\n.menu-has-child > .menu-link { padding-right: 0 }";

		// submenu link font color and border
		$css .= "\n.menu-level-second { color: $col[submenu_font]; border-bottom: solid 1px $col[submenu_border]; }";
		
		// border fix for last submenu item
		$css .= "\n.menu-item:last-child > .menu-level-second { border-bottom: none; }";
		
		// custom set element within LI navbar element (from menu customizer)
		$css .= "\n.sub-menu .emtheme-navbar-description { color: $col[submenu_font]; }";

		// active page marker
		$css .= "\n.menu-current { background-color: $col[active]; } ";
		
		// color for JS added arrows for submenu and hamburger icon
		$css .= "\n.nav-arrow, .mobile-icon { fill: $col[navbar_font]; }";
		
		// hides the go up button if toggled in customizer
		if ($lay['goup_toggle']) $css .= "\n.emtheme-goup { display: none !important; }";

			
		return $css;
	}


	/**
	 * returns css for page (not header/navbar)
	 * 
	 * @return text [css]
	 */
	private function page() {
		global $content_width;
		$fon = $this->fonts;
		$col = $this->colors;

		$width = $content_width / 10;

		$css = "\n@media only screen and (min-width: 1280px) {";
		$css .= "\n.main, .emtheme-footer { width: {$width}rem; }";
		$css .= "\n}";

		$css .= "\nbody { background-color: $col[background]; }";
		
		$css .= "\n.main { font-family: $fon[content_family]; font-size: {$fon[content_size]}rem; color: $col[main_font]; line-height: $fon[content_lineheight]; }";
		$css .= "\n.content, .default-template-widget, .column-404 { background-color: $col[main_background]; box-shadow: $col[main_shadow]; }";

		$css .= "\n.emtheme-footer { background-color: $col[footer_bg]; font-size: {$fon[content_size]}rem; color: $col[footer_font]; font-family: $fon[content_family]; }";
		$css .= "\n.emtheme-footer a { color: $col[footer_font]; }";

		$css .= "\n.emtheme-cookie-container { font-family: $fon[content_family]; }";
		$css .= "\n.emtheme-cookie { background-color: $col[privacy_bg]; color: $col[privacy_font]; border: solid 1px $col[privacy_button_bg]; }";
		$css .= "\n.emtheme-cookie-button { background-color: $col[privacy_button_bg]; color: $col[privacy_button_font]; }";
		$css .= "\n.emtheme-goup { background-color: $col[goup_bg]; border: solid 2px $col[goup_font]; }";
		$css .= "\n.emtheme-goup-arrow { fill: $col[goup_font]; }";
		
		return $css;
	}	
}
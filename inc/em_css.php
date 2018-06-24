<?php 

final class Emtheme_css {
	/* SINGLETON */
	private static $instance = null;
	private $colors;
	private $fonts;
	private $layout;
	private $privacy;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$col = get_theme_mod('emtheme_color');
		// $col = get_option('emtheme_color');
		$fon = get_theme_mod('emtheme_font');
		// $fon = get_option('emtheme_font');
		
		$lay = get_theme_mod('emtheme_layout');
		// wp_die('<xmp>'.print_r($fon, true).'</xmp>');
		// checking custom colors and setting defaults
		
		// COLORS
		$colors = [];

		// page background color (body tag)
		$colors['background'] = sanitize_hex_color('#'.get_background_color());
		if ($colors['background'] == '') $colors['background'] = '#eee';

		// main background color 
		$colors['main_background'] = isset($col['main_background']) ? sanitize_hex_color($col['main_background']) : '#fff';

		if (isset($col['main_shadow'])) {

			if ($col['main_shadow'] == '') $css = 'none';
			else $css = '0 0 2px '.sanitize_hex_color($col['main_shadow']);

			$colors['main_shadow'] = $css;  
		}
		else $colors['main_shadow'] = 'none';
		// else $colors['main_shadow'] = '0 0 2px #888';

		// font color in main area
		$colors['main_font'] = isset($col['main_font']) ? sanitize_hex_color($col['main_font']) : '#000';


		// header font
		$colors['header_font'] = isset($col['emtop_font']) ? sanitize_hex_color($col['emtop_font']) : '#000';

		// header background
		$colors['header_background'] = isset($col['emtop_bg']) ? sanitize_hex_color($col['emtop_bg']) : '#fff';

		// search
		$colors['search'] = isset($col['search']) ? sanitize_hex_color($col['search']) : '#000';
		
		// bg image
		$colors['header_background_image'] = isset($col['emtop_bg_image']) ? esc_url($col['emtop_bg_image']) : '';
		
		// bg image opacity
		$colors['header_background_image_opacity'] = isset($col['emtop_bg_image_opacity']) ? esc_html($col['emtop_bg_image_opacity']) : '0.5';

		// navbar font
		$colors['navbar_font'] = isset($col['nav_font']) ? sanitize_hex_color($col['nav_font']) : '#ffffff';
		
		// navbar bg
		if (isset($col['nav_bg_top'])) {
			$colors['navbar_background'] = 'background-color: '.sanitize_hex_color($col['nav_bg_top']);
		
			if (isset($col['nav_bg_middle']) && $col['nav_bg_middle'] != '' && isset($col['nav_bg_bottom']) && $col['nav_bg_bottom'] != '') 
				$colors['navbar_background'] = "background: linear-gradient(to top, $col[nav_bg_bottom] 0%, $col[nav_bg_middle] 50%, $col[nav_bg_top] 100%)";

			elseif (isset($col['nav_bg_middle']) && $col['nav_bg_middle'] != '')
				$colors['navbar_background'] = "background: linear-gradient(to top, $col[nav_bg_middle] 50%, $col[nav_bg_top] 100%)";
			
			elseif (isset($col['nav_bg_bottom']) && $col['nav_bg_bottom'] != '')
				$colors['navbar_background'] = "background: linear-gradient(to top, $col[nav_bg_bottom] 0%, $col[nav_bg_top] 100%)";

		}
		else $colors['navbar_background'] = 'background-color: #000';
		
		// navbar hover
		if (isset($col['nav_bg_hover_top'])) {
			$colors['navbar_hover'] = 'background-color: '.sanitize_hex_color($col['nav_bg_hover_top']);
		
			if (isset($col['nav_bg_hover_middle']) && $col['nav_bg_hover_middle'] != '' && isset($col['nav_bg_hover_bottom']) && $col['nav_bg_hover_bottom'] != '') 
				$colors['navbar_hover'] = "background: linear-gradient(to top, $col[nav_bg_hover_bottom] 0%, $col[nav_bg_hover_middle] 50%, $col[nav_bg_hover_top] 100%)";

			elseif (isset($col['nav_bg_hover_middle']) && $col['nav_bg_hover_middle'] != '')
				$colors['navbar_hover'] = "background: linear-gradient(to top, $col[nav_bg_hover_middle] 50%, $col[nav_bg_hover_top] 100%)";
			
			elseif (isset($col['nav_bg_hover_bottom']) && $col['nav_bg_hover_bottom'] != '')
				$colors['navbar_hover'] = "background: linear-gradient(to top, $col[nav_bg_hover_bottom] 0%, $col[nav_bg_hover_top] 100%)";

		}
		else $colors['navbar_hover'] = 'background-color: #353';

		// navbar borders
		$colors['navbar_border'] = isset($col['navbar_border']) ? 'solid 1px '.sanitize_hex_color($col['navbar_border']) : 'none';

		// submenu font
		$colors['submenu_font'] = isset($col['submenu_font']) ? sanitize_hex_color($col['submenu_font']) : '#000';
		
		// submenu bg
		$colors['submenu_background'] = isset($col['submenu_bg']) ? sanitize_hex_color($col['submenu_bg']) : '#eee';
						
		// submenu hover
		$colors['submenu_hover'] = isset($col['submenu_hover']) ? sanitize_hex_color($col['submenu_hover']) : '#ddd';

		$colors['submenu_border'] = isset($col['submenu_border']) ? sanitize_hex_color($col['submenu_border']) : '#bbb';
		
		// footer background
		$colors['footer_bg'] = isset($col['footer_bg']) ? sanitize_hex_color($col['footer_bg']) : '#000';

		// footer font color
		$colors['footer_font'] = isset($col['footer_font']) ? sanitize_hex_color($col['footer_font']) : '#fff';

		// go up button 
		$colors['goup_bg'] = isset($col['goup_bg']) ? sanitize_hex_color($col['goup_bg']) : '#ccc';

		// go up font color
		$colors['goup_font'] = isset($col['goup_font']) ? sanitize_hex_color($col['goup_font']) : '#000';

		// privacy css
		$pri = get_theme_mod('theme_privacy');
		
		// privacy window background
		$colors['privacy_bg'] = isset($pri['bg']) ? sanitize_hex_color($pri['bg']) : '#eee';
		$colors['privacy_font'] = isset($pri['font']) ? sanitize_hex_color($pri['font']) : '#000';

		$colors['privacy_button_bg'] = isset($pri['button_bg']) ? sanitize_hex_color($pri['button_bg']) : '#aaa';
		$colors['privacy_button_font'] = isset($pri['button_font']) ? sanitize_hex_color($pri['button_font']) : '#000';

		

		$this->colors = $colors;




		// FONTS
		$fonts = [];

		// content font family
		$fonts['content_family'] = (isset($fon['content_family']) && $fon['content_family'] != '') ? esc_html($fon['content_family']) : 'Open Sans';
		
		// content weight
		$fonts = array_merge($fonts, $this->check_weight((isset($fon['content_weight']) ? $fon['content_weight'] : false), 'content'));

		// content font size
		$fonts['content_size'] = isset($fon['content_size']) ? floatval($fon['content_size']) / 10 : '1.6';

		// content lineheight
		$fonts['content_lineheight'] = isset($fon['content_lineheight']) ? esc_html($fon['content_lineheight']) : 1.3;

		// title font family
		$fonts['title_family'] = (isset($fon['title_family']) && $fon['title_family'] != '') ? esc_html($fon['title_family']) : 'Roboto';

		// title weight
		$fonts = array_merge($fonts, $this->check_weight((isset($fon['title_weight']) ? $fon['title_weight'] : false), 'title'));

		// title font size
		$fonts['title_size'] = isset($fon['title_size']) ? floatval($fon['title_size']) / 10 : '4';

		// navbar font family
		$fonts['navbar_family'] = (isset($fon['navbar_family']) && $fon['navbar_family'] != '') ? esc_html($fon['navbar_family']) : 'Roboto';

		// navbar weight / style
		$fonts = array_merge($fonts, $this->check_weight((isset($fon['navbar_weight']) ? $fon['navbar_weight'] : false), 'navbar'));

		// navbar font-size
		$fonts['navbar_size'] = isset($fon['navbar_size']) ? floatval($fon['navbar_size']) / 10 : '2';

		// wp_die('<xmp>'.print_r($fonts, true).'</xmp>');
		$this->fonts = $fonts;



		// layout
		$layout['navbar_padding'] = isset($lay['navbar_padding']) ? floatval($lay['navbar_padding']) / 10 : '0.6';
		$layout['navbar_search'] = (isset($lay['search_navbar_toggle']) && $lay['search_navbar_toggle'] != '') ? $lay['search_navbar_toggle'] : false;

		$this->layout = $layout;


		/* adding css to front-end*/
		add_action( 'wp_enqueue_scripts', array($this, 'get_css') );
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
		$lay = get_theme_mod('emtheme_layout');


		$html = '';

		
		if (!isset($lay['header_toggle']) || $lay['header_toggle'] == '' || is_customize_preview()) $html .= $this->top();

		$html .= $this->navbar();

		$html .= $this->page();

		wp_add_inline_style('style', $html);
	}

	private function top() {
		global $content_width;
		$col = $this->colors;
		$fon = $this->fonts;
		
		$width = $content_width / 10;

		$css = ".emtheme-header-container { display: flex; align-items: center; min-height: 10rem; background-color: $col[header_background]; color: $col[header_font];}";
		$css .= "\n.emtheme-header-title { font-family: $fon[title_family]; font-size: {$fon[title_size]}rem; }";
		$css .= "\n.emtheme-header-tagline { font-family: $fon[content_family]; font-size: {$fon[content_size]}rem; }";
		// $css .= "\n.emtheme-header-search { align-self: flex-start; }";

		$css .= "\n.emtheme-header { width: {$width}rem; }";
		
		$css .= "\n.emtheme-header .emtheme-search-input { color: $col[header_font]; border-bottom: solid 1px $col[header_font]; font-size: {$fon[navbar_size]}rem; }"; 

		

		return $css;
	}

	private function navbar() {

		$col = $this->colors;
		$fon = $this->fonts;
		$lay = $this->layout;
		global $content_width;
		$width = $content_width / 10;
		
		$css = '';
		// wp_die('<xmp>'.print_r($lay, true).'</xmp>');

		// if ($lay['navbar_search'] || is_customize_preview()) {

		// $css .= "\n.emtheme-sea { color: $col[navbar_font]; }";
		$css .= "\n.navbar-background { $col[navbar_background]; }";
		
		$css .= "\n.navbar-title { color: $col[navbar_font]; font-size: {$fon[navbar_size]}rem; font-family: $fon[navbar_family]; margin-right: auto; }";
		$css .= "\n.navbar-menu { $col[navbar_background]; }";
		$css .= "\n.navbar-search .emtheme-search-input { background-color: inherit; color: $col[navbar_font]; font-size: {$fon[navbar_size]}rem; border: none; border-bottom: 1px solid {$col[navbar_font]}50; }";
		$css .= "\n.navbar-search .emtheme-search-input:focus { border-bottom: 2px solid $col[navbar_font]; }"; 
		  
		// $css .= "\n.emtheme-search-input::-webkit-search-cancel-button { -webkit-appearance: none; }"; 
		$css .= "\n.navbar-search .emtheme-search-button { background-color: inherit; border: none; position: relative; top: 5px; }";
		$css .= "\n.navbar-search .emtheme-search-button > .material-icons { color: $col[navbar_font]; }";
		// }

		// DESKTOP 
		$css .= "\n@media only screen and (min-width: 1280px) {";
			$css .= "\n.navbar-container { width: {$width}rem; }";
			// $css .= "\n.menu-list { width: {$width}rem; }";
			$css .= "\n.menu-level-second:hover { background-color: $col[submenu_hover]; }";
			$css .= "\n.sub-menu { position: absolute; }";
			$css .= "\n.menu-has-child:hover > .sub-menu { display: block; }";
			$css .= "\n.menu-has-child:hover { $col[navbar_hover]; }";
			$css .= "\n.menu-link:hover { $col[navbar_hover]; }";
		$css .= "\n}";
		
		// $css .= "\n.navbar-background, .navbar-container { $col[navbar_background]; }";
		// 
		$css .= "\n.menu-container { color: $col[navbar_font]; user-select: none;}";
		$css .= "\n.menu-list { display: flex; padding: 0; margin: 0; margin: auto; }";
		// $css .= "\n.menu-list { display: flex; position: relative; right: 1.5rem; padding: 0; margin: 0; width: {$width}rem; margin: auto; }";

		$css .= "\n.sub-menu { display: none; padding: 0; margin: 0; background-color: $col[submenu_background]; z-index: 99; color: $col[submenu_font]; border: solid 1px $col[submenu_border]; }";
						
		
		
		$css .= "\n.menu-item { position: relative; list-style: none; }";
		$css .= "\n.menu-link { display: flex; align-items: center; height: 100%; box-sizing: border-box; padding: {$lay[navbar_padding]}rem 1.5rem; font-family: \"$fon[navbar_family]\"; font-size: {$fon[navbar_size]}rem; text-decoration: none; color: $col[navbar_font]; white-space: nowrap;}";

		$css .= "\n.menu-has-child > .menu-link { padding-right: 0 }";

		$css .= "\n.menu-level-second { color: $col[submenu_font]; border-bottom: solid 1px $col[submenu_border]; }";
		$css .= "\n.menu-item:last-child > .menu-level-second { border-bottom: none; }";
		$css .= "\n.menu-item > .menu-level-second { margin-bottom: 0; }";
		
		$css .= "\n.sub-menu .emtheme-navbar-description { color: $col[submenu_font]; }";

		$css .= "\n.menu-level-top { border-right: $col[navbar_border]; }";

		// active page marker
		// $css .= "\n.menu-list > li > .menu-current::before { display: block; position: absolute; bottom:0; top: 0; right: 0; left: 0; content: ''; border-bottom: solid 4px #2a2; border-top: solid 4px #2a2; }";
		
		$css .= "\n.theme-nav-arrow { fill: $col[navbar_font]; }";
		

		// if (!$lay['logo-navbar-toggle']) $css .= "\n.menu-container { position: relative; right: 1.5rem}";
			
		

		return $css;
	}


	private function page() {
		global $content_width;
		$fon = $this->fonts;
		$col = $this->colors;
		// wp_die('<xmp>'.print_r($fon, true).'</xmp>');
		

		$width = $content_width / 10;

		$css = "\n@media only screen and (min-width: 1280px) {";
		$css .= "\n.main, .emtheme-footer { width: {$width}rem; }";
		$css .= "\n}";

		$css .= "\nbody { background-color: $col[background]; }";
		
		$css .= "\n.main { font-family: $fon[content_family]; font-size: {$fon[content_size]}rem; color: $col[main_font]; line-height: $fon[content_lineheight]; }";
		$css .= "\n.content, .default-template-widget { background-color: $col[main_background]; box-shadow: $col[main_shadow]; }";

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
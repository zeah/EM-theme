<?php 

final class Emtheme_css {
	/* SINGLETON */
	private static $instance = null;
	private $colors;
	private $fonts;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$col = get_option('emtheme_color');
		$fon = get_option('emtheme_font');
		
		// wp_die('<xmp>'.print_r($col, true).'</xmp>');
		
		// COLORS
		$colors = [];

		// page background color (body tag)
		$colors['background'] = isset($col['background']) ? sanitize_hex_color($col['background']) : '#fff'; 

		// main background color 
		$colors['main_background'] = isset($col['main_background']) ? sanitize_hex_color($col['main_background']) : '#fff';

		if (isset($col['main_shadow'])) {
			// $css = '0 0 5px';

			if ($col['main_shadow'] == '') $css = 'none';
			else $css = '0 0 5px '.sanitize_hex_color($col['main_shadow']);

			$colors['main_shadow'] = $css;  
		}
		else $colors['main_shadow'] = 'none';

		// header font
		$colors['header_font'] = isset($col['emtop_font']) ? sanitize_hex_color($col['emtop_font']) : '#000';

		// header background
		$colors['header_background'] = isset($col['emtop_bg']) ? sanitize_hex_color($col['emtop_bg']) : '#fff';

		// $colors['header_image'] = isset($col['emtop_bg_image']) ? esc_url($col['emtop_bg_image']) : false;

		// $colors['header_image_opacity'] = isset($col['emtop_bg_image_opacity']) ? esc_html($col['emtop_bg_image_opacity']) : '0.5';

		// search
		$colors['search'] = isset($col['search']) ? sanitize_hex_color($col['search']) : '#000';
		
		// bg image
		$colors['header_background_image'] = isset($col['emtop_bg_image']) ? esc_url($col['emtop_bg_image']) : '';
		
		// bg image opacity
		$colors['header_background_image_opacity'] = isset($col['emtop_bg_image_opacity']) ? esc_html($col['emtop_bg_image_opacity']) : '0.5';

		// navbar font
		$colors['navbar_font'] = isset($col['nav_font']) ? sanitize_hex_color($col['nav_font']) : '#fff';
		
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

		// submenu font
		$colors['submenu_font'] = isset($col['navsub_font']) ? sanitize_hex_color($col['navsub_font']) : '#000';
		
		// submenu bg
		$colors['submenu_background'] = isset($col['navsub_bg']) ? sanitize_hex_color($col['navsub_bg']) : '#eee';
						
		// submenu hover
		$colors['submenu_hover'] = isset($col['navsub_bg_hover']) ? sanitize_hex_color($col['navsub_bg_hover']) : '#ddd';
		
		$this->colors = $colors;

		// FONTS
		$fonts = [];

		// content font family
		$fonts['content_family'] = (isset($fon['standard']) && $fon['standard'] != '') ? esc_html($fon['standard']) : 'arial';
		// wp_die('<xmp>'.print_r($fonts['content_family'].'..'.$fon['standard'], true).'</xmp>');
		
		// content weight
		$fonts = array_merge($fonts, $this->check_weight((isset($fon['standard_weight']) ? $fon['standard_weight'] : false), 'content'));

		// content font size
		$fonts['content_size'] = isset($fon['standard_size']) ? esc_html($fon['standard_size']) : '1.6';

		// content lineheight
		$fonts['content_lineheight'] = isset($fon['standard_lineheight']) ? esc_html($fon['standard_lineheight']) : 1;

		// title font family
		$fonts['title_family'] = (isset($fon['title']) && $fon['title'] != '') ? esc_html($fon['title']) : 'verdana';

		// title weight
		$fonts = array_merge($fonts, $this->check_weight((isset($fon['title_weight']) ? $fon['title_weight'] : false), 'title'));

		// title font size
		$fonts['title_size'] = isset($fon['title_size']) ? esc_html($fon['title_size']) : '4.6';

		// navbar font family
		$fonts['navbar_family'] = (isset($fon['nav']) && $fon['nav'] != '') ? esc_html($fon['nav']) : 'arial';

		// navbar weight
		$fonts = array_merge($fonts, $this->check_weight((isset($fon['nav_weight']) ? $fon['nav_weight'] : false), 'navbar'));

		// navbar font-size
		$fonts['navbar_size'] = isset($fon['nav_size']) ? esc_html($fon['nav_size']) : '2';

		$this->fonts = $fonts;
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

		$html = $this->top();

		$html .= $this->navbar();

		$html .= $this->page();

		wp_add_inline_style('style', $html);
	}

	private function top() {
		global $content_width;

		$width = $content_width / 10;

		$css = "\n.emtheme-identity { display: flex; width: {$width}rem; margin: auto; }";
		

		return $css;
	}

	private function navbar() {

		$col = $this->colors;
		$fon = $this->fonts;
		global $content_width;

		// wp_die('<xmp>'.print_r($col, true).'</xmp>');
		
		
		$css .= "\n@media only screen and (min-width: 1280px) {";
		
		$css .= "\n.menu-container { $col[navbar_background]; color: $col[navbar_font]; user-select: none;}";
		$css .= "\n.menu-list { position: relative; right: 1rem; display: flex; padding: 0; margin: 0; width: {$content_width}px; margin: auto; }";
		
		$css .= "\n.sub-menu { display: none; position: absolute; padding: 0; margin: 0; background-color: $col[submenu_background]; z-index: 99; color: $col[submenu_font]; }";
						
		
		$css .= "\n.menu-has-child:hover > .sub-menu { display: block; }";
		
		$css .= "\n.menu-item { position: relative; list-style: none; }";
		$css .= "\n.menu-link { display: flex; align-items: center; height: 100%; box-sizing: border-box; padding: 0.3rem 1rem; font-family: \"$fon[navbar_family]\"; font-size: {$fon[navbar_size]}rem; text-decoration: none; color: $col[navbar_font]; white-space: nowrap;}";
		$css .= "\n.menu-has-child:hover { $col[navbar_hover]; }";
		$css .= "\n.menu-link:hover { $col[navbar_hover]; }";
		

		$css .= "\n.menu-has-child > .menu-link { padding-right: 0 }";

		$css .= "\n.menu-level-second { color: $col[submenu_font]; margin-bottom: 1rem; }";
		$css .= "\n.menu-item > .menu-level-second { margin-bottom: 0; }";
		$css .= "\n.menu-level-second:hover { background-color: $col[submenu_hover] !important; }";
		
		$css .= "\n.menu-current::before { display: block; position: absolute; bottom:0; right: 0; left: 0; content: ''; border-bottom: solid 3px red; }";
		
		// $css .= "\n.menu-current:hover { border-top: solid 2px red; }";
		

		$css .= "\n}";
		

		return $css;
	}

	private function page() {
		global $content_width;

		$width = $content_width / 10;

		$css .= "\n.main { width: {$width}rem; margin: auto; }";
		return $css;
	}	
}
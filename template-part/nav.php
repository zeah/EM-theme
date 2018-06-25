<?php 

$nav = Emtheme_nav::get_instance();

echo $nav->get_html();

// echo '<div class="navbar-background">
// 			<div class="navbar-container">
// 				<div class="navbar-logo">H</div>
// 				'.$nav->get_nav().'
// 				<div class="navbar-search">S</div>
// 			</div>
// 	  </div>';


/*
	Makes Nav Bar
*/
final class Emtheme_nav {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
	}

	public function get_html() {

		// settings from customizer
		$show = get_theme_mod('emtheme_layout');

		// to avoid array errors later
		if (!is_array($show)) $show = [];

		$search = $show['search_navbar_toogle'] || wp_is_mobile();

		$logo = $show['logo_navbar_toogle'] || wp_is_mobile();


		// container with background
		$html = '<div class="navbar-background">';

		// container with $content_width
		$html .= '<div class="navbar-container">';

		// container for logo and title 
		// $html .= '<div class="navbar-identity'.(!$logo ? ' desktop-hidden' : '').'">';
		$html .= '<div class="navbar-identity'.(!$show['logo_navbar_toggle'] ? ' desktop-hidden' : '').'"'.(!$logo ? ' style="display: none;"' : '').'>';

		// logo from customizer
		if (function_exists('get_custom_logo'))
			$html .= '<div class="navbar-logo">'.get_custom_logo().'</div>';

		// site title
		$html .= '<div class="navbar-title">'.esc_html(get_bloginfo('name')).'</div>';

		$html .= '</div>';

		// container for menu and search
		$html .= '<div class="navbar-menu">';

		// $html .= '<div class=""></div>';

		// the menu
		$html .= $this->get_nav();

		// search template
		$html .= '<div class="navbar-search'.(!$show['search_navbar_toogle'] ? ' desktop-hidden' : '').'"'.(!$search ? ' style="display: none;"' : '').'>'.get_search_form(false).'</div>';

		// end of container for menu and search
		$html .= '</div>';


		// end of navbar-background and navbar-container
		$html .= '</div></div>'; 

		// if (wp_is_mobile()) {

		// 	if (function_exists('the_custom_logo')) {
		// 		$logo = get_theme_mod('custom_logo');
		// 		$logo = wp_get_attachment_image_src($logo, 'full');


		// 		$html .= '<img src="'.esc_attr($logo[0]).'" width="auto" height="46px">';

		// 		$html .= '<span class="emtheme-title-text">'.esc_html(get_bloginfo('name')).'</span>';
		// 		// $html .= get_custom_logo();
		// 	}

		// }

		// $html .= '<div class="navbar-container">';

		// if (function_exists('the_custom_logo') && !wp_is_mobile())
		// 	if ((isset($show['logo_navbar_toggle']) && $show['logo_navbar_toggle']) || is_customize_preview()) {

		// 		$custom_logo_id = get_theme_mod('custom_logo');
		// 		$logo = wp_get_attachment_image_src($custom_logo_id , 'full');
				
		// 		if (has_custom_logo() && isset($logo[0]) && isset($logo[1])) 
		// 			$html .= '<a class="navbar-logo" href="'.esc_html(get_site_url()).'" style="flex-basis: '.(intval($logo[1])/10).'rem; background-image: url(\''.esc_url($logo[0]).'\');"></a>';
		// 	}
		
		// $html .= $this->get_nav();

		// if ((isset($show['search_navbar_toggle']) && $show['search_navbar_toggle']) || is_customize_preview() || wp_is_mobile()) $html .= get_search_form(false);

		// $html .= '</div></div>';

		return $html;
	}

	/**
	*	Currently only header-menu exists
	*	@return html element container nav menu
	*/
	private function get_nav() {

		/* getting nav menu */
		$menu = wp_nav_menu(array(
			'theme_location' 	=> 'header-menu',
			'fallback_cb' 		=> array($this, 'default_menu'), 
			'depth' 			=> 2,
			'container'			=> 'nav',
			'container_class'	=> 'menu-container',
			'menu_class'		=> 'menu-list',
			'echo' 				=> false,
			'walker'			=> new Emtheme_nav_walker
		));

		return $menu;

	}


	/**
	*	@return default nav menu if no menu for location is set 
	*/
	public function default_menu() {
		
		/* getting list of pages to ignore */
		$list = get_pages(['meta_key' => 'navignore', 'meta_value' => 'on']);

		/* getting the IDs for wp_page_menu() */
		$ignore = [];
		foreach($list as $li)
			array_push($ignore, $li->ID);	

		// returning pages as menu
		$menu = wp_page_menu(array(
			'exclude' 		=> implode(',', $ignore),
			'echo'			=> false,
			'depth'			=> 2,
			'container'		=> 'nav',
			'menu_class' 	=> 'menu-container',
			'before' 		=> '<ul class="menu-list">',
			'after' 		=> '</ul>',
			'walker'		=> new Emtheme_page_walker
		)); 

		return $menu;
	}

}


/*
	Extended Wordpress Walker for wp_nav_menu 
*/
class Emtheme_nav_walker extends Walker_Nav_menu {

	function start_lvl(&$output, $depth = 0, $args = array()) {
		$output .= '<ul class="sub-menu">';
	}

	function end_lvl(&$output, $depth = 0, $args = array()) {
		$output .= '</ul>';
	}
	
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {

		// translates depth to class
		// $depth_class = ($depth == 0) ? ' menu-level-top' : ' menu-level-second';
		$has_child = ($args->walker->has_children > 0) ? ' menu-has-child' : ''; 

		
		// finding custom classes
		$classes = '';
		foreach($item->classes as $name)
			// all custom classes comes before first default class
			if ($name == 'menu-item') break; 

			// adding custom classes
			else $classes .= ' '.$name;

		// $output .= print_r($item, true);
		   // $output .= sprintf('<li class="menu-item%s"><a class="menu-link" href="">%s</a>', 
					// $has_child, 
		   		
		   // 			$item->title

		   // 		);
			$output .= sprintf('<li class="menu-item%s"><a class="menu-link%s%s%s" href="%s" rel="noopener%s"%s%s>%s%s</a>%s',
					
					// adds "menu-has-child" class to parent items
					$has_child, 
					
					// adds "menu-current" to nav item which matches current page
					(($item->object_id == get_the_ID()) ? ' menu-current' : ''), 

					// translates nav depth to css class 
					($depth == 0) ? ' menu-level-top' : ' menu-level-second',

					// adds user made classes (from menu customizer)
					esc_html($classes), 

					// the url to which the nav item points to
					esc_url($item->url),

					// user created rel - meant to signal relationship between sites
					($item->xfn) ? ' '.esc_html($item->xfn) : '',

					// user created title attribute (from menu customizer) (shows as tooltip)
					($item->attr_title) ? ' title="'.esc_html($item->attr_title).'"' : '',
					
					// (user set) whether to open page in new tab or same window
					($item->target) ? ' target="_blank"' : '',

					// text of nav item (the html anchor)
					esc_html($item->title),

					// adds visual to parent nav items
					// ($has_child) ? '<i class="material-icons nav-arrow">arrow_drop_down</i>' : '', // add icon if true
					($has_child) ? '<svg class="theme-nav-arrow-container" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
										<path class="theme-nav-arrow" d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/>
									</svg>' : '', // add icon if true

					// user created descriptions. shows up when hover on same nav item.
					($item->description) ? '<span class="emtheme-navbar-description">'.esc_html($item->description).'</span>' : ''
				);
    }

    function end_el(&$output, $object, $depth = 0, $args = array()) {
    	$outout .= '</li>';
    }

}

/*
	Extended Wordpress Walker for wp_page_menu
*/
class Emtheme_page_walker extends Walker_Page {


	function start_lvl(&$output, $depth = 0, $args = array()) {
		$output .= '<ul class="sub-menu">';
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
		$output .= '</ul>';
	}
	

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		
		// translates depth to class
		$depth_class = ($depth == 0) ? ' menu-level-top' : ' menu-level-second';
	
		// whether item has child or not		
		$has_child = ($args['walker']->has_children > 0) ? ' menu-has-child' : ''; 

		// creates the nav item with nav link
		$output .= sprintf('<li class="menu-item%s"><a class="menu-link%s%s" href="%s">%s%s</a>',
					$has_child,
					(($item->ID == get_the_ID()) ? ' menu-current' : ''), // curent page
					$depth_class, // nav depth
					esc_url(get_permalink($item->ID)), // url
					esc_html($item->post_title), // page title
					($has_child) ? '<i class="material-icons nav-arrow">arrow_drop_down</i>' : ''
				);

    }

    function end_el(&$output, $object, $depth = 0, $args = array()) {
    	$outout .= '</li>';
    }

}
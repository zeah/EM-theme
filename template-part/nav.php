<?php 

// if (get_transient('theme_nav') && !is_customize_preview()) echo get_transient('theme_nav');
// else {
$nav = Emtheme_nav::get_instance();
echo $nav->get_html();
// }


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
		if (!is_array($show)) $show = [];


		// site identity 	navbar-menu
		// logo title 		nav 	search

		$h = '<div class="navbar-background">';
		$h .= '<div class="navbar-container">';



		
		if (wp_is_mobile()) {
			$h .= '<div class="navbar-identity">';

			// $h .= '<a href="'.get_site_url().'">';

			if (function_exists('get_custom_logo') && get_custom_logo())
				$h .= '<div class="navbar-logo">'.get_custom_logo().'</div>';

			$h .= '<a href="'.get_site_url().'" class="navbar-title">'.esc_html(get_bloginfo('name')).'</a>';

			// $h .= '</a>';

			$h .= '</div>';

			$h .= '<div class="navbar-menu">';

			$h .= $this->get_nav();
			
			$h .= '<div class="navbar-search-mobile">'.get_search_form(false).'</div>';

			$h .= '</div>';

			$h .= '<button aria-label="menu" type="button" onclick="document.querySelector(\'.navbar-menu\').classList.toggle(\'navbar-menu-show\')" class="mobile-icon-container"><svg class="mobile-menu-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"></path><path class="mobile-icon" d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path></svg></button>';

		}
		else if (is_customize_preview()) {
			
			$h .= '<div class="navbar-identity">';

			if (function_exists('get_custom_logo') && get_custom_logo())
				$h .= '<div class="navbar-logo">'.get_custom_logo().'</div>';

			$h .= '<a href="'.get_site_url().'" class="navbar-title">'.esc_html(get_bloginfo('name')).'</a>';


			$h .= '</div>';

			$h .= '<div class="navbar-menu">';

			$h .= $this->get_nav();
			
			$h .= '<div class="navbar-search"><button type=button class="navbar-search-button"><svg class="navbar-search-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="theme-search-svg" d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg><svg class="navbar-search-cancel-svg navbar-hide" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="navbar-search-cancel" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/><path d="M0 0h24v24H0z" fill="none"/></svg></button>';
			$h .= '<div class="navbar-search-popup navbar-hide">'.get_search_form(false).'</div></div>';


			$h .= '</div>';
		}
		else {

			if ($show['logo_navbar_toggle']) {

				$h .= '<div class="navbar-identity">';

				if (function_exists('get_custom_logo') && get_custom_logo())
					$h .= '<div class="navbar-logo">'.get_custom_logo().'</div>';

				$h .= '<a href="'.get_site_url().'" class="navbar-title">'.esc_html(get_bloginfo('name')).'</a>';

				$h .= '</div>';
			}

			$h .= '<div class="navbar-menu"'.($show['logo_navbar_toggle'] ? '' : ' style="flex: 1;"').'>';

			$h .= $this->get_nav();
			
			if ($show['search_navbar_toggle']) {
				$h .= '<div class="navbar-search"><button type=button class="navbar-search-button"><svg class="navbar-search-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="theme-search-svg" d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg><svg class="navbar-search-cancel-svg navbar-hide" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="navbar-search-cancel" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/><path d="M0 0h24v24H0z" fill="none"/></svg></button>';
				$h .= '<div class="navbar-search-popup navbar-hide">'.get_search_form(false).'</div></div>';
			}

			$h .= '</div>';

		}


		$h .= '</div></div>';

		// to avoid array errors later
		// if (!is_array($show)) $show = [];

		// show if toggled or is mobile
		// $search = $show['search_navbar_toggle'] || wp_is_mobile();
		// $logo = $show['logo_navbar_toggle'] || wp_is_mobile() || is_customize_preview();
		// // wp_die('<xmp>'.print_r($show, true).'</xmp>');

		// // container with background
		// $html = '<div class="navbar-background">';

		// // container with $content_width
		// $html .= '<div class="navbar-container">';

		// // container for logo and title 
		// $html .= '<div class="navbar-identity'.(!$show['logo_navbar_toggle'] ? ' desktop-hidden' : '').'"'.($logo ? '' : ' style="display: none;"').'>';

		// // logo from customizer
		// if (function_exists('get_custom_logo') && get_custom_logo())
		// 	$html .= '<div class="navbar-logo">'.get_custom_logo().'</div>';

		// // site title
		// $html .= '<a href="'.get_site_url().'" class="navbar-title">'.esc_html(get_bloginfo('name')).'</a>';

		// $html .= '</div>';

		// // container for menu and search
		// $html .= '<div class="navbar-menu"'.(!$logo ? ' style="flex: 1;"' : '').'>';

		// // the menu
		// $html .= $this->get_nav();
		
		// // search template
		
		// if (!wp_is_mobile()) {
		// 	if ($show['search_navbar_toggle']) {
		// 		$html .= '<div class="navbar-search"><button type=button class="navbar-search-button"><svg class="navbar-search-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="theme-search-svg" d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg><svg class="navbar-search-cancel-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="navbar-search-cancel" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/><path d="M0 0h24v24H0z" fill="none"/></svg></button>';
		// 		$html .= '<div class="navbar-search-popup">'.get_search_form(false).'</div></div>';
		// 	}
		// 	elseif (is_customize_preview()) {
		// 		$html .= '<div class="navbar-search" style="display: none;"><button type=button class="navbar-search-button"><svg class="navbar-search-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="theme-search-svg" d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg><svg class="navbar-search-cancel-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="navbar-search-cancel" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/><path d="M0 0h24v24H0z" fill="none"/></svg></button>';
		// 		// $html .= '<div class="navbar-search" style="display: none;"><svg class="navbar-search-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="theme-search-svg" d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg><svg class="navbar-search-cancel-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="navbar-search-cancel" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';
		// 		$html .= '<div class="navbar-search-popup">'.get_search_form(false).'</div></div>';				
		// 	}
		// }
		// else $html .= '<div class="navbar-search-mobile">'.get_search_form(false).'</div>';
		// // $html .= '<div class="navbar-search'.(!$show['search_navbar_toggle'] ? ' desktop-hidden' : '').'"'.(!$search ? ' style="display: none;"' : '').'>'.get_search_form(false).'</div>';
		
		// // end of container for menu and search
		// $html .= '</div>';

		// // end of navbar-background and navbar-container
		// $html .= '</div></div>'; 

		// set_transient('theme_nav', $html);
		return $h;
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
			'container'			=> '',
			// 'container'			=> 'nav',
			// 'container_class'	=> 'menu-container',
			'menu_class'		=> 'menu-list',
			'echo' 				=> false,
			'walker'			=> new Emtheme_nav_walker
		));

		return '<nav class="menu-container" itemscope itemtype="http://schema.org/SiteNavigationElement" role="navigation">'.$menu.'</nav>';
		// return $menu;
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
			// 'container'		=> false,
			// 'container'		=> 'nav',
			// 'menu_class' 	=> 'menu-container',
			'before' 		=> '<ul class="menu-list">',
			'after' 		=> '</ul>',
			'walker'		=> new Emtheme_page_walker
		)); 

		// return '<nav class="menu-container" itemscope itemtype="http://schema.org/SiteNavigationElement" role="navigation">'.$menu.'</nav>';
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
		$has_child = ($args->walker->has_children > 0) ? ' menu-has-child' : ''; 

		
		// finding custom classes
		$classes = '';
		foreach($item->classes as $name)
			// all custom classes comes before first default class
			if ($name == 'menu-item') break; 

			// adding custom classes
			else $classes .= ' '.$name;

			if (wp_is_mobile()) 
				$output .= sprintf('<li class="menu-item%s"><a itemprop="url" class="menu-link%s%s%s" href="%s" rel="noopener%s"%s%s>%s</a>%s%s',
					
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
					($has_child) ? '<div class="mobile-arrow-container"><svg onclick="var sm = this.parentNode.nextSibling; if (sm) sm.classList.toggle(\'submenu-show\')" class="nav-arrow-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
										<path class="nav-arrow" d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/>
									</svg></div>' : '', // add icon if true

					// user created descriptions. shows up when hover on same nav item.
					($item->description) ? '<span class="emtheme-navbar-description">'.esc_html($item->description).'</span>' : ''
				);
					
			else
				$output .= sprintf('<li class="menu-item%s"><a itemprop="url" class="menu-link%s%s%s" href="%s" rel="noopener%s"%s%s>%s%s</a>%s',
					
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
					($has_child) ? '<svg class="nav-arrow-container" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
										<path class="nav-arrow" d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/>
									</svg>' : '', // add icon if true

					// user created descriptions. shows up when hover on same nav item.
					($item->description) ? '<span class="emtheme-navbar-description">'.esc_html($item->description).'</span>' : ''
				);
    }

    function end_el(&$output, $object, $depth = 0, $args = array()) {
    	$output .= '</li>';
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
		$output .= sprintf('<li class="menu-item%s"><a itemprop="url" class="menu-link%s%s" href="%s">%s%s</a>',
					$has_child,
					(($item->ID == get_the_ID()) ? ' menu-current' : ''), // curent page
					$depth_class, // nav depth
					esc_url(get_permalink($item->ID)), // url
					esc_html($item->post_title), // page title
					// ($has_child) ? '<i class="material-icons nav-arrow">arrow_drop_down</i>' : ''
					// adds visual to parent nav items
					($has_child) ? '<svg class="nav-arrow-container" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
										<path class="nav-arrow" d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/>
									</svg>' : '' // add icon if true
				);

    }

    function end_el(&$output, $object, $depth = 0, $args = array()) {
    	$outout .= '</li>';
    }

}
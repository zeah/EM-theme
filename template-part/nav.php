<?php 

$nav = Emtheme_nav::get_instance();

echo $nav->get_nav();


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



	/**
	*	Currently only header-menu exists
	*	@return html element container nav menu
	*/
	public function get_nav() {

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
		// return '<nav>'.$menu.'</nav>';
		// return wp_page_menu(['exclude' => implode(',', $ignore), 'echo' => false, 'depth' => 2]);

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
		$depth_class = ($depth == 0) ? ' menu-level-top' : ' menu-level-second';
		$has_child = ($args->walker->has_children > 0) ? ' menu-has-child' : ''; 

		// wp_die('<xmp style="font-size: 1.6rem; line-height: 1.5;">'.print_r($args->walker->has_children, true).'</xmp>');
		
		// finding custom classes
		$classes = '';
		foreach($item->classes as $name)
			if ($name == 'menu-item') break;
			else $classes .= ' '.$name;

		// $output .= print_r($item, true);
		$output .= sprintf('<li class="menu-item%s"><a class="menu-link%s%s%s" href="%s" rel="noopener%s"%s%s>%s%s</a>%s',
					$has_child, // add class (is empty if no child)
					(($item->object_id == get_the_ID()) ? ' menu-current' : ''), // current page 
					$depth_class, // nav depth 
					esc_html($classes), // custom classes from menu customizer
					esc_url($item->url), // url
					($item->xfn) ? ' '.esc_html($item->xfn) : '', // rel relationship e.g. friend
					($item->attr_title) ? ' title="'.esc_html($item->attr_title).'"' : '', // title attribute on tag
					($item->target) ? ' target="_blank"' : '', // open link in new window
					esc_html($item->title), // page title or 'navigation label' 
					($has_child) ? '<i class="material-icons nav-arrow">arrow_drop_down</i>' : '', // add icon if true
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
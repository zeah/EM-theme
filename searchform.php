<?php 

require_once 'inc/em_search.php';

$search = Emtheme_search::get_instance();

echo $search->get();

// // if (!class_exists('Emtheme_searchform')) {
// final class Emtheme_searchform {
// 	/* singleton */
// 	private static $instance = null;

// 	public static function get_instance() {
// 		if (self::$instance === null) self::$instance = new self();

// 		return self::$instance;
// 	}

// 	private function __construct() {
// 	}


// 	public function get() {
// 		return '<div class="emtheme-header-search"><i class="material-icons">search</i></div>';
// 	}
// }
// }
<?php 

require_once 'inc/em_search.php';

$search = Emtheme_search::get_instance();

echo $search->get();


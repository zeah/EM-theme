<?php 

require_once 'inc/em_css.php';

echo '<!DOCTYPE html><html lang="'.get_locale().'"><head>';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<link rel="preconnect" href="https://fonts.googleapis.com/">';

// echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';

wp_head();


echo '</head><body '; body_class(); echo '>';
echo '<div class="page-container">';

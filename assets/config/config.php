<?php
if($_SERVER['SERVER_NAME'] == 'localhost')
    define('PUN_ROOT', 'C:/xampp/htdocs/movie/forum/');
else
    define('PUN_ROOT', '/var/www/movie/forum/');

require PUN_ROOT.'include/common.php';
require PUN_ROOT.'include/parser.php';
global $pun_user;

/*
function __autoload($class_name) {
    include '../site_auteurs_v3/include/class/'.$class_name.'.class.php';
}
*/

// Inclusions
require_once('library/autoload.php');

// Constantes
define('FOLDER_IMAGES', 'img');
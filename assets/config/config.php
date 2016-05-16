<?php
if($_SERVER['SERVER_NAME'] == 'localhost') {
	define('ROOT', 'C:/xampp/htdocs/movie/');
    define('PUN_ROOT', ROOT.'forum/');
    define('WWW_ROOT', 'http://localhost/movie/');
	}
else if($_SERVER['SERVER_NAME'] == 'mymoviewall.com') {
	define('ROOT', '/home/mymoviewall/www');
    define('PUN_ROOT', ROOT.'forum/');
    define('WWW_ROOT', 'http://www.mymoviewall.com/');
}
else {
	define('ROOT', '/home/mymoviewall/www');
    define('PUN_ROOT', ROOT.'forum/');
    define('WWW_ROOT', 'http://www.mymoviewall.com/');
}

require PUN_ROOT.'include/common.php';
require PUN_ROOT.'include/parser.php';

global $pun_user;

// Inclusions
require_once('library/autoload.php');

// Constantes
define('FOLDER_IMAGES', 'img');

// Timezone to date
date_default_timezone_set('Europe/Paris');

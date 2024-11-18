<?php
if($_SERVER['SERVER_NAME'] == 'localhost') {
	define('ROOT', 'C:/xampp/htdocs/MyMovieWall/');
    define('PUN_ROOT', ROOT.'forum/');
    define('WWW_ROOT', 'http://localhost/MyMovieWall/');
    define('MAINTENANCE_MOD', false);
	}
else if($_SERVER['SERVER_NAME'] == 'www.mymoviewall.com') {
    define('ROOT', '/srv/data/web/vhosts/www.mymoviewall.com/htdocs/');
    define('PUN_ROOT', ROOT.'forum/');
    define('WWW_ROOT', 'https://www.mymoviewall.com/');
    define('MAINTENANCE_MOD', false);
}
else {
    define('ROOT', '/srv/data/web/vhosts/www.mymoviewall.com/htdocs/');
    define('PUN_ROOT', ROOT.'forum/');
    define('WWW_ROOT', 'https://www.mymoviewall.com/');
    define('MAINTENANCE_MOD', false);
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

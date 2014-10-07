<?php
function autoload($class) {
	if(file_exists('C:/xampp/htdocs/movie/'.str_replace('\\', '/', $class).'.class.php'))
		require_once 'C:/xampp/htdocs/movie/'.str_replace('\\', '/', $class).'.class.php';
}
 
spl_autoload_register('autoload');
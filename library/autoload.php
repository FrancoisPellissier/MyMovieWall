<?php
function autoload($class) {
    if(file_exists(ROOT.str_replace('\\', '/', $class).'.class.php'))
        require_once ROOT.str_replace('\\', '/', $class).'.class.php';
}
 
spl_autoload_register('autoload');
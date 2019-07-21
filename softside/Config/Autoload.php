<?php namespace Config;
    
class Autoload
{
    
    // Static method that requires global.php and the needed classes
    // Using a class is enough for it to be included
    public static function run()
    {
        require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'global.php');
        spl_autoload_register(function($class) {
            $route = str_replace('\\', '/', $class) . '.php';
            if(is_readable($route)) {
                require_once $route;
            } else {
                echo 'Failed to read file in ' . $route;
            }
        });
    }
}

?>
<?php namespace Config;

class Router
{
    
    // This calls a specific method with (or without) specified arguments in a controller, according to the URL
    // URL must be in this format: $_SERVER['DOCUMENT_ROOT']/controller/method/argument
    // $_SERVER['DOCUMENT_ROOT']/users/index/ will call the index method in usersController with no argument
    // The template and view are loaded according to the controller and method 
    public static function run(Request $request)
    {
        $controller = $request->getController() . 'Controller';
        $route = ROOT . 'Controllers' . DS . $controller . '.php';
        $method = $request->getMethod();
        if($method == 'index.php') {
            $method = 'index';
        }
        $argument = $request->getArgument();
        $restricted = array();
        if (is_readable($route) && !in_array($route, $restricted)) {
            require_once $route;
            $show = 'Controllers\\' . $controller;
            $controller = new $show;
            if(!isset($argument)) {
                $data = call_user_func(array($controller, $method));
            } else {
                $data = call_user_func_array(array($controller, $method), $argument);
            }
        }
        
        // Load template
        if ($request->getController() == 'users' && $request->getMethod() != 'sync') {
            if ($request->getMethod() == 'index') {
                require_once('Views/template/loginTemplate.php');
            } else {
                require_once('Views/template/controlTemplate.php');
            }
        }
        
        // Load view
        $route = ROOT . 'Views' . DS . $request->getController() . DS . $request->getMethod() . '.php';
        if (is_readable($route)) {
            require_once $route;
        } else {
            print 'View not found'; 
        }
    }
}

?>
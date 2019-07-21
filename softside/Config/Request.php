<?php namespace Config;

class Request
{
    
    private $controller;
    private $method;
    private $argument;
    
    // According to the actual URL, this will decide which is the controller, method and arguments
    // See router for more
    // Default controllers are also set here
    public function __construct()
    {
        if(isset($_GET['url'])) {
            $route = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
            $route = explode('/', $route);
            $route = array_filter($route);
            
            if ($route[0] == 'index.php') {
                $this->controller = 'users';
            } else {
                $this->controller = strtolower(array_shift($route));
            }
            
            $this->method = strtolower(array_shift($route));
            
            if (!$this->method) {
                $this->method = 'index';
            }
            $this->argument = $route;
        } else {
            $this->controller = 'users';
            $this->method = 'index';
        }
    }
    
    public function getController()
    {
        return $this->controller;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getArgument()
    {
        return $this->argument;
    }
}

?>
<?php
namespace Base\Core;

use Base\Exceptions\Exception;
use Base\Tools\HttpRequest;

// TODO: Write unit tests.
class Router
{
    protected $routes = [];

    public function delete(string $route, string $callback)
    {
        $this->add(HttpRequest::DELETE, $route, $callback);
    }

    public function get(string $route, string $callback)
    {
        $this->add(HttpRequest::GET, $route, $callback);
    }

    public function post(string $route, string $callback)
    {
        $this->add(HttpRequest::POST, $route, $callback);
    }

    public function put(string $route, string $callback)
    {
        $this->add(HttpRequest::PUT, $route, $callback);
    }
    
    public function add(string $method, string $route, string $callback)
    {
        $routeId = $method . ":" . $route;
        
        if (isset($this->routes[$routeId]))
        {
            // TODO: Put here specific type of exception.
            throw new Exception("Route '{$routeId}' is already added.");
        }
        
        $this->routes[$routeId] = [
            "method" => $method,
            "route" => $route,
            "callback" => $callback
        ];
    }
    
    public function execute(string $method, string $path)
    {
        $executeRouteId = false;
        $params = false;
        foreach ($this->routes as $routeId => $routeInfo)
        {
            if ($routeInfo["method"] !== $method)
            {
                continue;
            }
            
            $routeObject = new Route($routeInfo["route"]);
            $params = $routeObject->match($path);
            if (is_array($params))
            {
                $executeRouteId = $routeId;
                break;
            }
        }
        
        if (!$executeRouteId)
        {
            // TODO: Returns response 404.
            return false;
        }
        
        $callbackString = $this->routes[$routeId]["callback"];
        $callbackArray = explode("::", $callbackString);
        if (count($callbackArray) != 2 || !$callbackArray[0] || !$callbackArray[1])
        {
            // TODO: Put here specific type of exception.
            throw new Exception("Syntax error of callback '{$callbackString}'.");
        }
        
        $className = $callbackArray[0];
        $methodName = $callbackArray[1];
        
        // TODO: Check all possible errors and throw Base's exceptions.
        return call_user_func_array([new $className, $methodName], $params);
    }
}

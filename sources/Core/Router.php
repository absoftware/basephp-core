<?php
/**
 * @project BasePHP Core
 * @file Router.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Exceptions\InternalError;
use Base\Exceptions\NotFound;
use Base\Tools\HttpRequest;

/**
 * Class Router.
 * @package Base\Core
 */
class Router
{
    /**
     * Collection of registered routes. Routes are kept here in string format.
     * @var array
     */
    protected $routes = [];

    /**
     * Adds route for DELETE requests.
     * @param string $route
     * @param string $callback
     */
    public function delete(string $route, string $callback)
    {
        $this->add(HttpRequest::DELETE, $route, $callback);
    }

    /**
     * Adds route for GET requests.
     * @param string $route
     * @param string $callback
     */
    public function get(string $route, string $callback)
    {
        $this->add(HttpRequest::GET, $route, $callback);
    }

    /**
     * Adds route for POST requests.
     * @param string $route
     * @param string $callback
     */
    public function post(string $route, string $callback)
    {
        $this->add(HttpRequest::POST, $route, $callback);
    }

    /**
     * Adds route for PUT requests.
     * @param string $route
     * @param string $callback
     */
    public function put(string $route, string $callback)
    {
        $this->add(HttpRequest::PUT, $route, $callback);
    }

    /**
     * Adds route for any requests.
     * @param string $method
     * @param string $route
     * @param string $callback
     * @throws InternalError
     */
    public function add(string $method, string $route, string $callback)
    {
        $routeId = $method . ":" . $route;
        
        if (isset($this->routes[$routeId]))
        {
            throw new InternalError("Route '{$routeId}' is already added.");
        }
        
        $this->routes[$routeId] = [
            "method" => $method,
            "route" => $route,
            "callback" => $callback
        ];
    }

    /**
     * Searches callback for given method and path.
     * @param string $method
     * @param string $path
     * @return array First element is name of controller, second is name of method, third is an array of parameters.
     * @throws InternalError
     * @throws NotFound
     */
    public function callback(string $method, string $path)
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
            throw new NotFound("Path '{$path}' not found.");
        }
        
        $callbackString = $this->routes[$executeRouteId]["callback"];
        $callbackArray = explode("::", $callbackString);
        if (count($callbackArray) != 2 || !$callbackArray[0] || !$callbackArray[1])
        {
            throw new InternalError("Syntax error of callback '{$callbackString}'.");
        }
        
        $className = $callbackArray[0];
        $methodName = $callbackArray[1];
        return [$className, $methodName, $params];
    }
}

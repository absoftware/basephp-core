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
 *
 * TODO: Implement middleware to rate limit access to routes within application.
 * TODO: Implement path-prefix patterns for example for language in path like http://domain.com/en/my/path.
 * TODO: Implement groups of routes that path prefixes or middleware limits could be assigned to each group.
 * TODO: Implement authorization methods on Router level.
 * TODO: Implement fallbacks (?) instead of immediately 404.
 *
 * @package Base\Core
 */
class Router
{
    /**
     * Collection of registered routes.
     * @var array
     */
    protected $routes = [];

    /**
     * Adds route for DELETE requests.
     * @param string $pattern Path pattern.
     * @param string $callback Name of controller and its methods.
     */
    public function delete(string $pattern, string $callback)
    {
        $this->add(HttpRequest::DELETE, $pattern, $callback);
    }

    /**
     * Adds route for GET requests.
     * @param string $pattern Path pattern.
     * @param string $callback Name of controller and its methods.
     */
    public function get(string $pattern, string $callback)
    {
        $this->add(HttpRequest::GET, $pattern, $callback);
    }

    /**
     * Adds route for POST requests.
     * @param string $pattern Path pattern.
     * @param string $callback Name of controller and its methods.
     */
    public function post(string $pattern, string $callback)
    {
        $this->add(HttpRequest::POST, $pattern, $callback);
    }

    /**
     * Adds route for PUT requests.
     * @param string $pattern Path pattern.
     * @param string $callback Name of controller and its methods.
     */
    public function put(string $pattern, string $callback)
    {
        $this->add(HttpRequest::PUT, $pattern, $callback);
    }

    /**
     * Adds route for any requests.
     * @param string $method HTTP request method.
     * @param string $pattern Path pattern.
     * @param string $callback Name of controller and its methods.
     * @throws InternalError
     */
    public function add(string $method, string $pattern, string $callback)
    {
        $route = new Route($method, $pattern, $callback);
        $routeId = $route->routeId();
        
        if (isset($this->routes[$routeId]))
        {
            throw new InternalError("Route '{$routeId}' is already added.");
        }
        
        $this->routes[$routeId] = $route;
    }

    /**
     * Searches route for given method and real path.
     * @param string $method HTTP request method.
     * @param string $path Real request path.
     * @return CallbackInfo Information about callback.
     * @throws InternalError
     * @throws NotFound
     */
    public function callbackInfo(string $method, string $path): CallbackInfo
    {
        $executeRouteId = false;
        $params = false;

        // Find matching route.
        foreach ($this->routes as $routeId => $route)
        {
            $params = $route->match($method, $path);
            if (is_array($params))
            {
                $executeRouteId = $routeId;
                break;
            }
        }

        // Return 404 if not found.
        if (!$executeRouteId)
        {
            throw new NotFound("Path '{$path}' not found.");
        }

        // Create callback info.
        $route = $this->routes[$executeRouteId];
        $callbackInfo = new CallbackInfo($route->callback(), $params);

        // Validate callback of found route.
        if (!$callbackInfo->isValid())
        {
            throw new InternalError("Syntax error of callback '{$callbackInfo->callback()}'.");
        }

        return $callbackInfo;
    }
}

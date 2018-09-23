<?php
/**
 * @project BasePHP Core
 * @file RouteGroup.php created by Ariel Bogdziewicz on 23/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Exceptions\InternalError;
use Base\Exceptions\NotFound;
use Base\Http\HttpRequest;

/**
 * Class RouteGroup represents route group with specified prefix, locales or authorization rules.
 * @package Base\Core
 */
class RouteGroup
{
    /**
     * Array of routes or route groups.
     * @var array
     */
    protected $routes = [];

    /**
     * Pattern prefix.
     * @var string
     */
    protected $patternPrefix = "";

    /**
     * Authorization ID.
     * @var string
     */
    protected $authorizationId = "";

    /**
     * RouteGroup constructor.
     * @param string $patternPrefix
     * @param string $authorizationId
     */
    public function __construct(string $patternPrefix = "", string $authorizationId = "")
    {
        $this->patternPrefix = $patternPrefix;
        $this->authorizationId = $authorizationId;
    }

    /**
     * Returns pattern prefix.
     * @return string
     */
    public function patternPrefix(): string
    {
        return $this->patternPrefix;
    }

    /**
     * Returns authorization ID.
     * @return string
     */
    public function authorizationId(): string
    {
        return $this->authorizationId;
    }

    /**
     * Adds route for DELETE requests.
     * @param string $pattern Path pattern.
     * @param string $callback Name of controller and its methods.
     * @throws \Base\Exceptions\ArgumentException
     */
    public function delete(string $pattern, string $callback): void
    {
        $this->add(HttpRequest::DELETE, $pattern, $callback);
    }

    /**
     * Adds route for GET requests.
     * @param string $pattern Path pattern.
     * @param string $callback Name of controller and its methods.
     * @throws \Base\Exceptions\ArgumentException
     */
    public function get(string $pattern, string $callback): void
    {
        $this->add(HttpRequest::GET, $pattern, $callback);
    }

    /**
     * Adds route for POST requests.
     * @param string $pattern Path pattern.
     * @param string $callback Name of controller and its methods.
     * @throws \Base\Exceptions\ArgumentException
     */
    public function post(string $pattern, string $callback): void
    {
        $this->add(HttpRequest::POST, $pattern, $callback);
    }

    /**
     * Adds route for PUT requests.
     * @param string $pattern Path pattern.
     * @param string $callback Name of controller and its methods.
     * @throws \Base\Exceptions\ArgumentException
     */
    public function put(string $pattern, string $callback): void
    {
        $this->add(HttpRequest::PUT, $pattern, $callback);
    }

    /**
     * Adds route for any requests.
     * @param string $method HTTP request method.
     * @param string $pattern Path pattern.
     * @param string $callback Name of controller and its methods.
     * @throws \Base\Exceptions\ArgumentException
     */
    public function add(string $method, string $pattern, string $callback): void
    {
        $this->routes[] = new Route($method, $pattern, $callback);
    }

    /**
     * Adds route group.
     * @param RouteGroup $routeGroup
     */
    public function addRouteGroup(RouteGroup $routeGroup): void
    {
        $this->routes[] = $routeGroup;
    }

    /**
     * Searches route for given method and real path.
     * @param string $method HTTP request method.
     * @param string $path Real request path.
     * @param string $patternPrefix
     * @param array $authorizationIds
     * @return CallbackInfo Information about callback.
     * @throws InternalError
     * @throws NotFound
     */
    public function callbackInfo(string $method, string $path, string $patternPrefix = "", array $authorizationIds = []): CallbackInfo
    {
        $route = null;
        $params = false;

        // Append authorization ID for this level.
        if ($this->authorizationId)
        {
            $authorizationIds[] = $this->authorizationId;
        }

        // Find matching route.
        foreach ($this->routes as $routeItem)
        {
            if ($routeItem instanceof Route)
            {
                $params = $routeItem->match($method, $path, $patternPrefix);
                if (is_array($params))
                {
                    $route = $routeItem;
                    break;
                }
            }
            else if ($routeItem instanceof RouteGroup)
            {
                try
                {
                    return $routeItem->callbackInfo($method, $path, $patternPrefix, $authorizationIds);
                }
                catch (NotFound $exception)
                {
                    continue;
                }
            }
        }

        // Return 404 if not found.
        if (!$route)
        {
            throw new NotFound("Path '{$path}' not found.");
        }

        // Create callback info.
        $callbackInfo = new CallbackInfo($route->callback(), $params, $authorizationIds);

        // Validate callback of found route.
        if (!$callbackInfo->isValid())
        {
            throw new InternalError("Syntax error of callback '{$callbackInfo->callback()}'.");
        }

        return $callbackInfo;
    }
}

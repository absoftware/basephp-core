<?php
/**
 * @project BasePHP Core
 * @file Route.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Exceptions\ArgumentException;

/**
 * Class Route represents relation between path pattern and target controller and its methods.
 * @package Base\Core
 */
class Route
{
    /**
     * Allowed characters in parameter of path.
     * @var string
     */
    protected $allowedParamCharacters = '[a-zA-Z0-9\_\-]+';

    /**
     * Route identifier unique across all routes.
     * @var string
     */
    protected $routeId;

    /**
     * HTTP request method.
     * @var string
     */
    protected $method;

    /**
     * Path pattern.
     * @var string
     */
    protected $pattern;

    /**
     * Class name of controller and its methods in one string.
     * @var string
     */
    protected $callback;

    /**
     * Route constructor.
     * @param string $method HTTP request method.
     * @param string $pattern Path pattern.
     * @param string $callback Class name of controller and its methods in one string.
     * @throws ArgumentException
     */
    public function __construct(string $method, string $pattern, string $callback)
    {
        if (preg_match('/[^-:\/_{}()a-zA-Z\d]/', $pattern))
        {
            throw new ArgumentException("pattern", "Path pattern '{$pattern}' is invalid because contains not allowed characters.");
        }

        $this->routeId = "{$method}:{$pattern}";
        $this->method = $method;
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    /**
     * Returns route identifier unique across all routes.
     * @return string Route identifier.
     */
    public function routeId()
    {
        return $this->routeId;
    }

    /**
     * Returns path pattern.
     * @return string Path pattern.
     */
    public function pattern()
    {
        return $this->pattern;
    }

    /**
     * Returns HTTP request method of route.
     * @return string HTTP request method.
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * Returns callback of route.
     * @return string Callback which contains name of class and its method or methods.
     */
    public function callback()
    {
        return $this->callback;
    }

    /**
     * Returns PHP regex created from path pattern.
     * @param bool $caseSensitive
     * @return string
     */
    public function regex(bool $caseSensitive = false)
    {
        // Replace "{parameter}" with "(?<parameter>[a-zA-Z0-9\_\-]+)".
        $regex = preg_replace(
            '/{('. $this->allowedParamCharacters .')}/',
            '(?<$1>' . $this->allowedParamCharacters . ')',
            $this->pattern
        );

        // Return route as regex.
        $caseSensitiveFlag = $caseSensitive ? "" : "i";
        return "@^" . $regex . "$@D" . $caseSensitiveFlag;
    }

    /**
     * Matches path pattern to given path.
     * @param string $method HTTP request method.
     * @param string $path Real path from request.
     * @param bool $caseSensitive
     * @return array|bool
     *      Returns matched parameters as array or false if path doesn't match to path pattern.
     *      Empty array means empty list of parameters.
     */
    public function match(string $method, string $path, bool $caseSensitive = false)
    {
        if (!$method || $method != $this->method())
        {
            return false;
        }

        $result = preg_match($this->regex($caseSensitive), $path, $matches);
        if ($result !== 1)
        {
            return false;
        }
        
        // Get values of parameters from matches where string is key.
        $params = [];
        foreach ($matches as $paramName => $paramValue)
        {
            if (!is_string($paramName))
            {
                continue;
            }
            
            $params[$paramName] = $paramValue;
        }
        
        return $params;
    }
}

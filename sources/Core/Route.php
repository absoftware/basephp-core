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
 */
class Route
{
    /**
     * Allowed characters in parameter of path.
     * @var string
     */
    protected string $allowedParamCharacters = '[a-zA-Z0-9\_\-]+';

    /**
     * HTTP request method.
     * @var string
     */
    protected string $method;

    /**
     * Path pattern.
     * @var string
     */
    protected string $pattern;

    /**
     * Class name of controller and its methods in one string.
     * @var string
     */
    protected string $callback;

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

        $this->method = $method;
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    /**
     * Returns path pattern.
     * @return string Path pattern.
     */
    public function pattern(): string
    {
        return $this->pattern;
    }

    /**
     * Returns HTTP request method of route.
     * @return string HTTP request method.
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Returns callback of route.
     * @return string Callback which contains name of class and its method or methods.
     */
    public function callback(): string
    {
        return $this->callback;
    }

    /**
     * Returns PHP regex created from path pattern.
     * @param string $patternPrefix
     * @param bool $caseSensitive
     * @return string
     */
    public function regex(string $patternPrefix = "", bool $caseSensitive = false): string
    {
        // Replace "{parameter}" with "(?<parameter>[a-zA-Z0-9\_\-]+)".
        $regex = preg_replace(
            '/{('. $this->allowedParamCharacters .')}/',
            '(?<$1>' . $this->allowedParamCharacters . ')',
            $patternPrefix . $this->pattern
        );

        // Return route as regex.
        $caseSensitiveFlag = $caseSensitive ? "" : "i";
        return "@^" . $regex . "$@D" . $caseSensitiveFlag;
    }

    /**
     * Matches path pattern to given path.
     * @param string $method HTTP request method.
     * @param string $path Real path from request.
     * @param string $patternPrefix
     * @param bool $caseSensitive
     * @return array|bool
     *      Returns matched parameters as array or false if path doesn't match to path pattern.
     *      Empty array means empty list of parameters.
     */
    public function match(string $method, string $path, string $patternPrefix = "", bool $caseSensitive = false): bool|array
    {
        if (!$method || $method !== $this->method())
        {
            return false;
        }

        $result = preg_match($this->regex($patternPrefix, $caseSensitive), $path, $matches);
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

<?php
/**
 * @project BasePHP Core
 * @file Route.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

class Route
{
    protected $allowedParamCharacters = '[a-zA-Z0-9\_\-]+';
    
    protected $route;
    
    public function __construct(string $route)
    {
        if (preg_match('/[^-:\/_{}()a-zA-Z\d]/', $route))
        {
            // TODO: Use desired type of exception.
            throw new Exception("Route '{$route}' is invalid.");
        }
        
        $this->route = $route;
    }
    
    public function regex(bool $caseSensitive = false)
    {
        // Replace "{parameter}" with "(?<parameter>[a-zA-Z0-9\_\-]+)".
        $regex = preg_replace(
            '/{('. $this->allowedParamCharacters .')}/',
            '(?<$1>' . $this->allowedParamCharacters . ')',
            $this->route
        );

        // Return route as regex.
        $caseSensitiveFlag = $caseSensitive ? "" : "i";
        return "@^" . $regex . "$@D" . $caseSensitiveFlag;
    }
    
    public function match(string $path, bool $caseSensitive = false)
    {
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

<?php
/**
 * @project BasePHP Core
 * @file Request.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

/**
 * Class Request delivers all information about request.
 * @package Base\Core
 */
class Request
{
    /**
     * Configuration of ports.
     * @var Ports|null
     */
    protected $ports = null;

    /**
     * Request constructor.
     * @param Ports|null $ports
     */
    public function __construct(Ports $ports = null)
    {
        $this->ports = $ports ?? new Ports();
    }

    /**
     * Request method.
     * @return string
     */
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Returns true if connection is performed through HTTPS protocol.
     * @return bool
     */
    public function isHttps(): bool
    {
        if (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https')
        {
            return true;
        }
        
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
        {
            return true;
        }
        
        if (!empty($_SERVER['SERVER_PORT']) && in_array((int)$_SERVER['SERVER_PORT'], $this->ports->httpsPorts()))
        {
            return true;
        }
        
        return false;
    }

    /**
     * Returns true if request is an AJAX request.
     * @return bool
     */
    function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * Protocol.
     * @return string
     */
    public function protocol(): string
    {
        return $this->isHttps() ? "https://" : "http://";
    }

    /**
     * Full host name.
     * @return string
     */
    public function host(): string
    {
        return strtolower($_SERVER['SERVER_NAME']);
    }

    /**
     * Current port.
     * @return int
     */
    public function port(): int
    {
        if (isset($_SERVER['SERVER_PORT']))
        {
            $port = (int)$_SERVER['SERVER_PORT'];
        }
        else
        {
            $port = $this->isHttps() ? $this->ports->defaultHttpsPort() : $this->ports->defaultHttpPort();
        }
        return $port;
    }

    /**
     * Request URI which means path and arguments starting from slash character.
     * @return mixed
     */
    public function uri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Path of request without arguments starting from slash character.
     * @return string
     */
    public function path(): string
    {
        $requestUri = explode("?", $this->uri());
        return is_array($requestUri) && count($requestUri) > 0 ? $requestUri[0] : "/";
    }

    /**
     * Gets GET param.
     * @param $name
     * @return array|float|null|string
     */
    public function get($name)
    {
        return $this->getVariable($_GET, $name);
    }

    /**
     * Gets POST param.
     * @param $name
     * @return array|float|null|string
     */
    public function post($name)
    {
        return $this->getVariable($_POST, $name);
    }

    /**
     * Private method used to process GET and POST variables.
     * @param $array
     * @param $name
     * @return array|float|null|string
     */
    private function getVariable($array, $name)
    {
        if (isset($array[$name]))
        {
            if (is_array($array[$name]))
            {
                $tmp = array();
                foreach($array[$name] as $key => $val)
                {
                    if (((double)$val) === $val)
                    {
                        $tmp[$key] = (double)$val;
                    }
                    else
                    {
                        $tmp[$key] = $this->escape(trim($val));
                    }
                }
                return $tmp;
            }
            if (((double)$array[$name]) === $array[$name])
            {
                return (double)$array[$name];
            }
            return $this->escape(trim($array[$name]));
        }
        return null;
    }

    /**
     * Private method used to escape GET and POST variables.
     * @param $string
     * @return string
     */
    private function escape($string)
    {
        return get_magic_quotes_gpc() ? stripslashes($string) : $string;
    }

    /**
     * Gets cookie from request.
     * @param $name
     * @return string
     */
    public function cookie($name): ?string
    {
        if (isset($_COOKIE[$name]))
        {
            return $_COOKIE[$name];
        }
        return null;
    }
}

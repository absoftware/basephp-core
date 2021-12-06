<?php
/**
 * @project BasePHP Core
 * @file Request.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Http\HttpRequest;
use Base\Exceptions\BadRequest;

/**
 * Class Request delivers all information about request.
 */
class Request extends HttpRequest
{
    /**
     * Configuration of ports.
     * @var Ports
     */
    protected Ports $ports;

    /**
     * Request constructor.
     * @param Ports|null $ports
     */
    public function __construct(?Ports $ports = null)
    {
        $this->ports = $ports ?? new Ports();
        parent::__construct($this->url(), $this->method(), new Header());
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
        if (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https')
        {
            return true;
        }
        
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
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
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * @return string
     */
    public function index(): string
    {
        return $this->protocol() . $this->host() . '/';
    }

    /**
     * Full URL of request.
     * @return string
     */
    public function url(): string
    {
        return $this->protocol() . $this->host() . $this->uri();
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
     * @param string $name
     * @return array|float|null|string
     */
    public function get(string $name)
    {
        return $this->getVariable($_GET, $name);
    }

    /**
     * Gets GET param otherwise throws bad request exception.
     * @param string $name
     * @return array|float|null|string
     * @throws BadRequest
     */
    public function requiredGet(string $name)
    {
        $param = $this->get($name);
        if (!$param)
        {
            throw new BadRequest("Missing GET param '$name'.");
        }
        return $param;
    }

    /**
     * Gets POST param.
     * @param string $name
     * @return array|float|null|string
     */
    public function post(string $name)
    {
        return $this->getVariable($_POST, $name);
    }

    /**
     * Gets POST param otherwise throws bad request exception.
     * @param string $name
     * @return array|float|null|string
     * @throws BadRequest
     */
    public function requiredPost(string $name)
    {
        $param = $this->post($name);
        if (!$param)
        {
            throw new BadRequest("Missing POST param '$name'.");
        }
        return $param;
    }

    /**
     * Gets POST array transformed into form array.
     * @return array Transformed POST array.
     */
    public function formData(): array
    {
        if (!is_array($_POST))
        {
            return [];
        }

        $form = [];
        foreach ($_POST as $key => $value)
        {
            $form[$key] = $this->getVariable($_POST, $key);
        }

        return $form;
    }

    /**
     * Private method used to process GET and POST variables.
     * @param $array
     * @param $name
     * @return float|int|array|string|null
     */
    private function getVariable($array, $name): float|int|array|string|null
    {
        if (isset($array[$name]))
        {
            if (is_array($array[$name]))
            {
                $tmp = array();
                foreach($array[$name] as $key => $val)
                {
                    if (((float)$val) === $val)
                    {
                        $tmp[$key] = (double)$val;
                    }
                    else
                    {
                        $tmp[$key] = trim($val);
                    }
                }
                return $tmp;
            }
            if (((float)$array[$name]) === $array[$name])
            {
                return (double)$array[$name];
            }
            return trim($array[$name]);
        }
        return null;
    }

    /**
     * Gets cookie from request.
     * @param $name
     * @return string|null
     */
    public function cookie($name): ?string
    {
        return $_COOKIE[$name] ?? null;
    }
}

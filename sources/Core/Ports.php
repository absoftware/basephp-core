<?php
/**
 * @project BasePHP Core
 * @file Ports.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Exceptions\ArgumentException;

class Ports
{
    protected $httpPorts = [80];
    protected $defaultHttpPort = 80;
    protected $httpsPorts = [443];
    protected $defaultHttpsPort = 443;
    
    public function __construct(int $defaultHttpPort = 80, int $defaultHttpsPort = 443)
    {
        if ($defaultHttpPort == $defaultHttpsPort)
        {
            throw new ArgumentException("defaultHttpsPort", "Port cannot be defined as HTTP and HTTPS port for both at once.");
        }
        $this->validatePort("defaultHttpPort", $defaultHttpPort, $this->httpPorts, $this->httpsPorts);
        $this->validatePort("defaultHttpsPort", $defaultHttpsPort, $this->httpsPorts, $this->httpPorts);
        $this->setDefaultHttpPort($defaultHttpPort);
        $this->setDefaultHttpsPort($defaultHttpsPort);
    }
    
    public function defaultHttpPort()
    {
        return $this->defaultHttpPort;
    }
    
    public function defaultHttpsPort()
    {
        return $this->defaultHttpsPort;
    }
    
    public function httpPorts()
    {
        return $this->httpPorts;
    }
    
    public function httpsPorts()
    {
        return $this->httpsPorts;
    }
    
    public function registerHttpPort(int $httpPort)
    {
        $this->validatePort("httpPort", $httpPort, $this->httpPorts, $this->httpsPorts);
        $this->registerPort($httpPort, $this->httpPorts);
    }
    
    public function registerHttpsPort(int $httpsPort)
    {
        $this->validatePort("httpsPort", $httpsPort, $this->httpsPorts, $this->httpPorts);
        $this->registerPort($httpsPort, $this->httpsPorts);
    }
    
    private function registerPort(int $port, array &$ports)
    {
        if (!in_array($port, $ports))
        {
            $ports[] = $port;
        }
    }
    
    public function setDefaultHttpPort(int $httpPort)
    {
        $this->validatePort("httpPort", $httpPort, $this->httpPorts, $this->httpsPorts);
        $this->setDefaultPort($httpPort, $this->httpPorts, $this->defaultHttpPort);
    }
    
    public function setDefaultHttpsPort(int $httpsPort)
    {
        $this->validatePort("httpsPort", $httpsPort, $this->httpsPorts, $this->httpPorts);
        $this->setDefaultPort($httpsPort, $this->httpsPorts, $this->defaultHttpsPort);
    }
    
    private function setDefaultPort(int $port, array &$ports, int &$curentDefaultPort)
    {        
        for ($i = 0; $i < count($ports); ++$i)
        {
            if ($ports[$i] === $curentDefaultPort)
            {
                $curentDefaultPort = $port;
                $ports[$i] = $port;
                return;
            }
        }
        
        $curentDefaultPort = $port;
        $ports[] = $port;
    }
    
    private function validatePort(string $argumentName, int $port, array $hereWillBeAdded, array $secondPortArray)
    {
        if ($port < 0)
        {
            throw new ArgumentException($argumentName, "Port cannot be negative.");
        }
        if (in_array($port, $secondPortArray))
        {
            throw new ArgumentException($argumentName, "Port cannot be defined as HTTP and HTTPS port for both at once.");
        }
    }
}

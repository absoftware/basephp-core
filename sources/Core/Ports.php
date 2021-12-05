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
    protected array $httpPorts = [80];
    protected int $defaultHttpPort = 80;
    protected array $httpsPorts = [443];
    protected int $defaultHttpsPort = 443;

    /**
     * @throws ArgumentException
     */
    public function __construct(int $defaultHttpPort = 80, int $defaultHttpsPort = 443)
    {
        if ($defaultHttpPort === $defaultHttpsPort)
        {
            throw new ArgumentException("defaultHttpsPort", "Port cannot be defined as HTTP and HTTPS port for both at once.");
        }
        $this->validatePort("defaultHttpPort", $defaultHttpPort, $this->httpPorts, $this->httpsPorts);
        $this->validatePort("defaultHttpsPort", $defaultHttpsPort, $this->httpsPorts, $this->httpPorts);
        $this->setDefaultHttpPort($defaultHttpPort);
        $this->setDefaultHttpsPort($defaultHttpsPort);
    }

    /**
     * @return int
     */
    public function defaultHttpPort(): int
    {
        return $this->defaultHttpPort;
    }

    /**
     * @return int
     */
    public function defaultHttpsPort(): int
    {
        return $this->defaultHttpsPort;
    }

    /**
     * @return int[]
     */
    public function httpPorts(): array
    {
        return $this->httpPorts;
    }

    /**
     * @return int[]
     */
    public function httpsPorts(): array
    {
        return $this->httpsPorts;
    }

    /**
     * @throws ArgumentException
     */
    public function registerHttpPort(int $httpPort): void
    {
        $this->validatePort("httpPort", $httpPort, $this->httpPorts, $this->httpsPorts);
        $this->registerPort($httpPort, $this->httpPorts);
    }

    /**
     * @throws ArgumentException
     */
    public function registerHttpsPort(int $httpsPort): void
    {
        $this->validatePort("httpsPort", $httpsPort, $this->httpsPorts, $this->httpPorts);
        $this->registerPort($httpsPort, $this->httpsPorts);
    }

    /**
     * @param int $port
     * @param array $ports
     */
    private function registerPort(int $port, array &$ports): void
    {
        if (!in_array($port, $ports, true))
        {
            $ports[] = $port;
        }
    }

    /**
     * @param int $httpPort
     * @throws ArgumentException
     */
    public function setDefaultHttpPort(int $httpPort): void
    {
        $this->validatePort("httpPort", $httpPort, $this->httpPorts, $this->httpsPorts);
        $this->setDefaultPort($httpPort, $this->httpPorts, $this->defaultHttpPort);
    }

    /**
     * @throws ArgumentException
     */
    public function setDefaultHttpsPort(int $httpsPort): void
    {
        $this->validatePort("httpsPort", $httpsPort, $this->httpsPorts, $this->httpPorts);
        $this->setDefaultPort($httpsPort, $this->httpsPorts, $this->defaultHttpsPort);
    }

    /**
     * @param int $port
     * @param array $ports
     * @param int $currentDefaultPort
     */
    private function setDefaultPort(int $port, array &$ports, int &$currentDefaultPort): void
    {
        for ($i = 0, $count = count($ports); $i < $count; ++$i)
        {
            if ($ports[$i] === $currentDefaultPort)
            {
                $currentDefaultPort = $port;
                $ports[$i] = $port;
                return;
            }
        }

        $currentDefaultPort = $port;
        $ports[] = $port;
    }

    /**
     * @throws ArgumentException
     */
    private function validatePort(string $argumentName, int $port, array $hereWillBeAdded, array $secondPortArray): void
    {
        if ($port < 0)
        {
            throw new ArgumentException($argumentName, "Port cannot be negative.");
        }
        if (in_array($port, $secondPortArray, true))
        {
            throw new ArgumentException($argumentName, "Port cannot be defined as HTTP and HTTPS port for both at once.");
        }
    }
}

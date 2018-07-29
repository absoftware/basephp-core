<?php
/**
 * @project BasePHP Core
 * @file PortsTest.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Tests\Core;

use Base\Core\Ports;
use Base\Exceptions\ArgumentException;
use PHPUnit\Framework\TestCase;

final class PortsTest extends TestCase
{
    public function testDefaultConstructor()
    {
        $ports = new Ports();
        $this->assertTrue($ports->defaultHttpPort() == 80, "Default HTTP port is not 80.");
        $this->assertTrue($ports->defaultHttpsPort() == 443, "Default HTTPS port is not 443.");
    }
    
    public function testConstructorArguments()
    {
        $ports = new Ports(8080, 8443);
        $this->assertTrue($ports->defaultHttpPort() == 8080, "Default HTTP port is not 8080.");
        $this->assertTrue($ports->defaultHttpsPort() == 8443, "Default HTTPS port is not 8443.");
    }
    
    public function testRegisteredPorts()
    {
        $ports = new Ports(8080, 8443);
        $ports->registerHttpPort(9080);
        $ports->registerHttpsPort(9443);
        $this->assertTrue($ports->defaultHttpPort() == 8080, "Default HTTP port is not 8080.");
        $this->assertTrue($ports->defaultHttpsPort() == 8443, "Default HTTPS port is not 8443.");
        $this->assertTrue(in_array(8080, $ports->httpPorts()), "Port 8080 is not in HTTP ports.");
        $this->assertTrue(in_array(9080, $ports->httpPorts()), "Port 9080 is not in HTTP ports.");
        $this->assertTrue(in_array(8443, $ports->httpsPorts()), "Port 8443 is not in HTTPS ports.");
        $this->assertTrue(in_array(9443, $ports->httpsPorts()), "Port 9443 is not in HTTPS ports.");
    }
    
    public function testTheSamePortsInConstructor()
    {
        try
        {
            $ports = new Ports(8080, 8080);
            $this->assertTrue(false, "ArgumentException should be thrown.");
        }
        catch (ArgumentException $e)
        {
            $this->assertTrue($e->argumentName() == "defaultHttpsPort");
        }
    }
    
    public function testTheSamePortsForRegisterHttp()
    {
        try
        {
            $ports = new Ports();
            $ports->registerHttpsPort(9080);
            $ports->registerHttpPort(9080);
            $this->assertTrue(false, "ArgumentException should be thrown.");
        }
        catch (ArgumentException $e)
        {
            $this->assertTrue($e->argumentName() == "httpPort");
        }
        catch (Throwable $t)
        {
            $this->assertTrue(false, "Unexpected Throwable is thrown.");
        }
    }
    
    public function testTheSamePortsForRegisterHttps()
    {
        try
        {
            $ports = new Ports();
            $ports->registerHttpPort(9080);
            $ports->registerHttpsPort(9080);
            $this->assertTrue(false, "ArgumentException should be thrown.");
        }
        catch (ArgumentException $e)
        {
            $this->assertTrue($e->argumentName() == "httpsPort");
        }
        catch (Throwable $t)
        {
            $this->assertTrue(false, "Unexpected Throwable is thrown.");
        }
    }
    
    public function testTheSamePortsForSetDefaultHttp()
    {
        try
        {
            $ports = new Ports();
            $ports->setDefaultHttpsPort(9080);
            $ports->setDefaultHttpPort(9080);
            $this->assertTrue(false, "ArgumentException should be thrown.");
        }
        catch (ArgumentException $e)
        {
            $this->assertTrue($e->argumentName() == "httpPort");
        }
        catch (Throwable $t)
        {
            $this->assertTrue(false, "Unexpected Throwable is thrown.");
        }
    }
    
    public function testTheSamePortsForSetDefaultHttps()
    {
        try
        {
            $ports = new Ports();
            $ports->setDefaultHttpPort(9080);
            $ports->setDefaultHttpsPort(9080);
            $this->assertTrue(false, "ArgumentException should be thrown.");
        }
        catch (ArgumentException $e)
        {
            $this->assertTrue($e->argumentName() == "httpsPort");
        }
        catch (Throwable $t)
        {
            $this->assertTrue(false, "Unexpected Throwable is thrown.");
        }
    }
    
    public function testNegativePortInConstructorHttp()
    {
        try
        {
            $ports = new Ports(-8080, 9080);
            $this->assertTrue(false, "ArgumentException should be thrown.");
        }
        catch (ArgumentException $e)
        {
            $this->assertTrue($e->argumentName() == "defaultHttpPort");
        }
    }
    
    public function testNegativePortInConstructorHttps()
    {
        try
        {
            $ports = new Ports(8080, -9080);
            $this->assertTrue(false, "ArgumentException should be thrown.");
        }
        catch (ArgumentException $e)
        {
            $this->assertTrue($e->argumentName() == "defaultHttpsPort");
        }
    }
    
    public function testNegativePortForRegisterHttp()
    {
        try
        {
            $ports = new Ports();
            $ports->registerHttpPort(-8080);
            $this->assertTrue(false, "ArgumentException should be thrown.");
        }
        catch (ArgumentException $e)
        {
            $this->assertTrue($e->argumentName() == "httpPort");
        }
        catch (Throwable $t)
        {
            $this->assertTrue(false, "Unexpected Throwable is thrown.");
        }
    }
    
    public function testNegativePortForRegisterHttps()
    {
        try
        {
            $ports = new Ports();
            $ports->registerHttpsPort(-9080);
            $this->assertTrue(false, "ArgumentException should be thrown.");
        }
        catch (ArgumentException $e)
        {
            $this->assertTrue($e->argumentName() == "httpsPort");
        }
        catch (Throwable $t)
        {
            $this->assertTrue(false, "Unexpected Throwable is thrown.");
        }
    }
    
    public function testNegativePortForSetDefaultHttp()
    {
        try
        {
            $ports = new Ports();
            $ports->setDefaultHttpPort(-8080);
            $this->assertTrue(false, "ArgumentException should be thrown.");
        }
        catch (ArgumentException $e)
        {
            $this->assertTrue($e->argumentName() == "httpPort");
        }
        catch (Throwable $t)
        {
            $this->assertTrue(false, "Unexpected Throwable is thrown.");
        }
    }
    
    public function testNegativePortForSetDefaultHttps()
    {
        try
        {
            $ports = new Ports();
            $ports->setDefaultHttpsPort(-9080);
            $this->assertTrue(false, "ArgumentException should be thrown.");
        }
        catch (ArgumentException $e)
        {
            $this->assertTrue($e->argumentName() == "httpsPort");
        }
        catch (Throwable $t)
        {
            $this->assertTrue(false, "Unexpected Throwable is thrown.");
        }
    }
}

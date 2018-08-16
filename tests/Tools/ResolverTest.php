<?php
/**
 * @project BasePHP Core
 * @file ResolverTest.php created by Ariel Bogdziewicz on 16/08/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Tests\Core;

use Base\Exceptions\InternalError;
use Base\Tools\Resolver;
use PHPUnit\Framework\TestCase;

/**
 * Class without constructor.
 * @package Tests\Core
 */
class ResolverTestNoConstructor
{
    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }
}

/**
 * Class with constructor without params.
 * @package Tests\Core
 */
class ResolverTestNoParams
{
    /**
     * ResolverTestNoParams constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }
}

/**
 * Class with constructor with one class parameter.
 * @package Tests\Core
 */
class ResolverTestWithClassParam
{
    /**
     * @var ResolverTestNoConstructor
     */
    private $param;

    /**
     * ResolverTestWithClassParam constructor.
     * @param ResolverTestNoConstructor $param
     */
    public function __construct(ResolverTestNoConstructor $param)
    {
        $this->param = $param;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this) . "(" . $this->param . ")";
    }
}

/**
 * Class with constructor with one scalar parameter.
 * @package Tests\Core
 */
class ResolverTestWithScalarParam
{
    /**
     * @var int
     */
    private $param;

    /**
     * ResolverTestWithScalarParam constructor.
     * @param int $param
     */
    public function __construct(int $param)
    {
        $this->param = $param;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this) . "(" . $this->param . ")";
    }
}

/**
 * Class with constructor with two class parameters.
 * @package Tests\Core
 */
class ResolverTestWithClassParams
{
    /**
     * @var ResolverTestNoConstructor
     */
    private $param1;

    /**
     * @var ResolverTestNoParams
     */
    private $param2;

    /**
     * ResolverTestWithClassParams constructor.
     * @param ResolverTestNoConstructor $param1
     * @param ResolverTestNoParams $param2
     */
    public function __construct(ResolverTestNoConstructor $param1, ResolverTestNoParams $param2)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this) . "(" . $this->param1 . ", " . $this->param2 . ")";
    }
}

/**
 * Class with constructor with parameters which are dependent on other classes.
 * @package Tests\Core
 */
class ResolverTestWithDependencies
{
    /**
     * @var ResolverTestWithClassParam
     */
    private $param1;

    /**
     * @var ResolverTestWithClassParams
     */
    private $param2;

    /**
     * ResolverTestWithDependencies constructor.
     * @param ResolverTestWithClassParam $param1
     * @param ResolverTestWithClassParams $param2
     */
    public function __construct(ResolverTestWithClassParam $param1, ResolverTestWithClassParams $param2)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this) . "(" . $this->param1 . ", " . $this->param2 . ")";
    }
}

/**
 * Class with constructor with parameters which are dependent on other classes.
 * @package Tests\Core
 */
class ResolverTestWithDependenciesAndScalars
{
    /**
     * @var ResolverTestWithClassParam
     */
    private $param1;

    /**
     * @var ResolverTestWithClassParams
     */
    private $param2;

    /**
     * @var int
     */
    private $param3;

    /**
     * ResolverTestWithDependenciesAndScalars constructor.
     * @param ResolverTestWithClassParam $param1
     * @param ResolverTestWithClassParams $param2
     * @param int $param3
     */
    public function __construct(ResolverTestWithClassParam $param1, ResolverTestWithClassParams $param2, int $param3 = 200)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
        $this->param3 = $param3;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this) . "(" . $this->param1 . ", " . $this->param2 . ", " . $this->param3 . ")";
    }
}

/**
 * Class ResolverTest tests Resolver class.
 * @package Tests\Core
 */
final class ResolverTest extends TestCase
{
    /**
     * Tests creating of class ResolverTestNoConstructor.
     */
    public function testResolverTestNoConstructor()
    {
        $className = "Tests\\Core\\ResolverTestNoConstructor";
        $object = (new Resolver())->create($className);
        $this->assertTrue(get_class($object) === $className && $object == $className);
    }

    /**
     * Tests creating of class ResolverTestWithScalarParam.
     */
    public function testResolverTestWithScalarParam()
    {
        try
        {
            $className = "Tests\\Core\\ResolverTestWithScalarParam";
            (new Resolver())->create($className);
            $this->assertTrue(false, "Exception 'InternalError' should be thrown.");
        }
        catch (InternalError $internalError)
        {
            $this->assertTrue(true);
        }
        catch (\Exception $exception)
        {
            $this->assertTrue(false, $exception->getMessage());
        }
        catch (\Throwable $throwable)
        {
            $this->assertTrue(false, $throwable->getMessage());
        }
    }

    /**
     * Tests creating of class ResolverTestWithScalarParam.
     */
    public function testResolverTestWithScalarParamOverloadedType()
    {
        $resolver = new Resolver();
        $resolver->setDefaultTypeValue("int", 123);
        $className = "Tests\\Core\\ResolverTestWithScalarParam";
        $object = $resolver->create($className);
        $this->assertTrue(get_class($object) === $className && $object == $className . "(123)");
    }

    /**
     * Tests creating of class ResolverTestWithScalarParam.
     */
    public function testResolverTestWithScalarParamOverloadedClassParam()
    {
        $resolver = new Resolver();
        $className = "Tests\\Core\\ResolverTestWithScalarParam";
        $resolver->setDefaultClassParameterValue($className, "param", 234);
        $object = $resolver->create($className);
        $this->assertTrue(get_class($object) === $className && $object == $className . "(234)");
    }

    /**
     * Tests creating of class ResolverTestNoParams.
     */
    public function testResolverTestNoParams()
    {
        $className = "Tests\\Core\\ResolverTestNoParams";
        $object = (new Resolver())->create($className);
        $this->assertTrue(get_class($object) === $className && $object == $className);
    }

    /**
     * Tests creating of class ResolverTestWithClassParam.
     */
    public function testResolverTestWithClassParam()
    {
        $className = "Tests\\Core\\ResolverTestWithClassParam";
        $object = (new Resolver())->create($className);
        $this->assertTrue(get_class($object) === $className && $object == $className . "(Tests\\Core\\ResolverTestNoConstructor)");
    }

    /**
     * Tests creating of class ResolverTestWithClassParams.
     */
    public function testResolverTestWithClassParams()
    {
        $className = "Tests\\Core\\ResolverTestWithClassParams";
        $object = (new Resolver())->create($className);
        $this->assertTrue(get_class($object) === $className && $object == $className . "(Tests\\Core\\ResolverTestNoConstructor, Tests\\Core\\ResolverTestNoParams)");
    }

    /**
     * Tests creating of class ResolverTestWithDependencies.
     */
    public function testResolverTestWithDependencies()
    {
        $className = "Tests\\Core\\ResolverTestWithDependencies";
        $object = (new Resolver())->create($className);
        $this->assertTrue(get_class($object) === $className && $object == $className . "(Tests\\Core\\ResolverTestWithClassParam(Tests\\Core\\ResolverTestNoConstructor), Tests\\Core\\ResolverTestWithClassParams(Tests\\Core\\ResolverTestNoConstructor, Tests\\Core\\ResolverTestNoParams))");
    }

    /**
     * Tests creating of class ResolverTestWithDependenciesAndScalars.
     */
    public function testResolverTestWithDependenciesAndScalars()
    {
        $className = "Tests\\Core\\ResolverTestWithDependenciesAndScalars";
        $object = (new Resolver())->create($className);
        $this->assertTrue(get_class($object) === $className && $object == $className . "(Tests\\Core\\ResolverTestWithClassParam(Tests\\Core\\ResolverTestNoConstructor), Tests\\Core\\ResolverTestWithClassParams(Tests\\Core\\ResolverTestNoConstructor, Tests\\Core\\ResolverTestNoParams), 200)");
    }

    /**
     * Tests creating of class ResolverTestWithDependenciesAndScalars.
     */
    public function testResolverTestWithDependenciesAndScalarsOverloadedInt()
    {
        $resolver = new Resolver();
        $resolver->setDefaultTypeValue("int", 300);
        $className = "Tests\\Core\\ResolverTestWithDependenciesAndScalars";
        $object = $resolver->create($className);
        $this->assertTrue(get_class($object) === $className && $object == $className . "(Tests\\Core\\ResolverTestWithClassParam(Tests\\Core\\ResolverTestNoConstructor), Tests\\Core\\ResolverTestWithClassParams(Tests\\Core\\ResolverTestNoConstructor, Tests\\Core\\ResolverTestNoParams), 300)");
    }

    /**
     * Tests creating of class ResolverTestWithDependenciesAndScalars.
     */
    public function testResolverTestWithDependenciesAndScalarsOverloadedClassParam()
    {
        $resolver = new Resolver();
        $className = "Tests\\Core\\ResolverTestWithDependenciesAndScalars";
        $resolver->setDefaultClassParameterValue($className, "param3", 400);
        $object = $resolver->create($className);
        $this->assertTrue(get_class($object) === $className && $object == $className . "(Tests\\Core\\ResolverTestWithClassParam(Tests\\Core\\ResolverTestNoConstructor), Tests\\Core\\ResolverTestWithClassParams(Tests\\Core\\ResolverTestNoConstructor, Tests\\Core\\ResolverTestNoParams), 400)");
    }
}

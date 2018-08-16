<?php
/**
 * @project BasePHP Core
 * @file Resolver.php created by Ariel Bogdziewicz on 15/08/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Tools;

use Base\Exceptions\InternalError;

/**
 * Class Resolver allows to use automated dependency injection based on reflection.
 * @package Base\Tools
 */
class Resolver
{
    /**
     * Default values for specific parameters in constructor of specific classes.
     * @var array
     */
    private $defaultClassParameterValues = [];

    /**
     * Default values for specific types of parameters.
     * @var array
     */
    private $defaultTypeValues = [];

    /**
     * Sets default value for specific parameter in constructor of given class.
     * @param string $className Full class name together with namespace like 'My\Namespace\ClassName'.
     * @param string $paramName Name of parameter like 'param'.
     * @param mixed $value Value of any type.
     */
    public function setDefaultClassParameterValue(string $className, string $paramName, $value): void
    {
        $this->defaultClassParameterValues[$className][$paramName] = $value;
    }

    /**
     * Sets default value for specific type.
     * @param string $typeName
     * @param $value
     */
    public function setDefaultTypeValue(string $typeName, $value): void
    {
        $this->defaultTypeValues[$typeName] = $value;
    }

    /**
     * Creates instance of class with given name.
     * @param string $className Full class name together with namespace like 'My\Namespace\ClassName'.
     * @return object New object.
     * @throws InternalError
     */
    public function create(string $className)
    {
        // Get reflection for given class name.
        $class = new \ReflectionClass($className);
        if (!$class->isInstantiable())
        {
            throw new InternalError("Resolver: Class '{$className}' is not instantiable.");
        }

        // Get constructor of given class.
        $constructor = $class->getConstructor();
        if ($constructor === null)
        {
            return new $className;
        }

        // Gather dependencies.
        $dependencies = [];
        $parameters = $constructor->getParameters();
        foreach ($parameters as $parameter)
        {
            // Get basic info about parameter.
            $paramName = $parameter->getName();
            $paramType = $parameter->getType();
            $paramClass = $parameter->getClass();

            // Go through all possibilities of creating value for parameter.
            if (isset($this->defaultClassParameterValues[$className][$paramName]))
            {
                // Get default value for specific parameter in specific class.
                $dependencies[] = $this->defaultClassParameterValues[$className][$paramName];
            }
            else if (isset($this->defaultTypeValues[(string)$paramType]))
            {
                // Get default value for specific type.
                $dependencies[] = $this->defaultTypeValues[(string)$paramType];
            }
            else if ($paramClass)
            {
                // Parameter is class.
                $dependencies[] = $this->create($paramClass->getName());
            }
            else if ($parameter->isDefaultValueAvailable())
            {
                // Parameter is scalar with default value.
                $dependencies[] = $parameter->getDefaultValue();
            }
            else
            {
                // We cannot provide value for parameter.
                throw new InternalError("Resolver: Couldn't resolve parameter '{$paramName}' for constructor of class '{$className}'.");
            }
        }

        // Create instance of class with dependencies.
        return $class->newInstanceArgs($dependencies);
    }
}

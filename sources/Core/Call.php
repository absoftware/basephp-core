<?php
/**
 * @project BasePHP Core
 * @file Call.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

/**
 * Class Call holds controller, method and list of arguments.
 */
class Call
{
    /**
     * Controller.
     * @var BaseController
     */
    protected BaseController $controller;

    /**
     * Method name.
     * @var string
     */
    protected string $methodName;

    /**
     * @var mixed
     */
    protected mixed $ctx;

    /**
     * Parameters.
     * @var array
     */
    protected array $params;

    /**
     * Call constructor.
     * @param BaseController $controller
     * @param string $methodName
     * @param mixed $ctx
     * @param array $params
     */
    public function __construct(BaseController $controller, string $methodName, mixed $ctx, array $params = [])
    {
        $this->controller = $controller;
        $this->methodName = $methodName;
        $this->ctx = $ctx;
        $this->params = $params;
    }

    /**
     * Gets parameter.
     * @param string $name
     * @return mixed
     */
    public function param(string $name): mixed
    {
        return $this->params[$name] ?? null;
    }

    /**
     * Gets controller.
     * @return BaseController
     */
    public function controller(): BaseController
    {
        return $this->controller;
    }

    /**
     * Executes code of callback.
     * @return Response
     */
    public function call(): Response
    {
        // Parameters
        $params = [$this->ctx];
        foreach ($this->params as $param) {
            $params[] = $param;
        }

        // Check whether method name contains multiple method names in format "method1<method2<method3".
        $methods = explode("<", $this->methodName);
        if (!is_array($methods) || count($methods) <= 1) {
            // In case of single method in callback just call this method as provided.
            return call_user_func_array([$this->controller, $this->methodName], $params);
        }

        // In case of multiple method names call first one, but pass new callback as argument instead of params.
        $methodName = array_shift($methods);
        $callback = new Call($this->controller, implode("<", $methods), $this->ctx, $this->params);
        return call_user_func([$this->controller, $methodName], $this->ctx, $callback);
    }
}

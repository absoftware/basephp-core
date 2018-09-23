<?php
/**
 * @project BasePHP Core
 * @file Call.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Core\Response;

/**
 * Class Call holds controller, method and list of arguments.
 * @package Base\Core
 */
class Call
{
    /**
     * Controller.
     * @var Controller
     */
    protected $controller;

    /**
     * Method name.
     * @var string
     */
    protected $methodName;

    /**
     * Parameters.
     * @var array
     */
    protected $params;

    /**
     * Call constructor.
     * @param Controller $controller
     * @param string $methodName
     * @param array $params
     */
    public function __construct(Controller $controller, string $methodName, array $params = [])
    {
        $this->controller = $controller;
        $this->methodName = $methodName;
        $this->params = $params;
    }

    /**
     * Gets parameter.
     * @param string $name
     * @return mixed|null
     */
    public function param(string $name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }

    /**
     * Gets controller.
     * @return Controller
     */
    public function controller(): Controller
    {
        return $this->controller;
    }

    /**
     * Executes code of callback.
     * @return Response
     */
    public function call(): Response
    {
        // Check whether method name contains multiple method names in format "method1<method2<method3".
        $methods = explode("<", $this->methodName);
        if (!is_array($methods) || count($methods) <= 1) {
            // In case of single method in callback just call this method as provided.
            return call_user_func_array([$this->controller, $this->methodName], $this->params);
        }

        // In case of multiple method names call first one, but pass new callback as argument instead of params.
        $methodName = array_shift($methods);
        $callback = new Call($this->controller, implode("<", $methods), $this->params);
        return call_user_func_array([$this->controller, $methodName], ["callback" => $callback]);
    }
}

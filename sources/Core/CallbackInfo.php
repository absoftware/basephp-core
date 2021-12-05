<?php
/**
 * @project BasePHP Core
 * @file CallbackInfo.php created by Ariel Bogdziewicz on 17/08/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

/**
 * Class CallbackInfo represents callback description and list of parameters.
 * It contains all information needed to execute callback of route.
 */
class CallbackInfo
{
    /**
     * String describing whole callback.
     * For example, it may look like "My\Namespace\Controller::method1<method2<method3".
     * @var string
     */
    protected string $callback;

    /**
     * Parameters of method.
     * @var array
     */
    protected array $params;

    /**
     * CallbackInfo constructor.
     * @param string $callback
     *      String describing whole callback.
     *      The basic format looks like "My\Namespace\Controller::methodName".
     *      More complex example may look like "My\Namespace\Controller::method1<method2<method3".
     * @param array $params
     *      Parameters for last method from callback.
     */
    public function __construct(string $callback, array $params = [])
    {
        $this->callback = $callback;
        $this->params = $params;
    }

    /**
     * Returns true if callback has valid syntax.
     * @return bool
     */
    public function isValid(): bool
    {
        $callbackArray = explode("::", $this->callback);
        return is_array($callbackArray) && count($callbackArray) === 2 && $callbackArray[0] && $callbackArray[1];
    }

    /**
     * Returns string describing whole callback.
     * @return string
     */
    public function callback(): string
    {
        return $this->callback;
    }

    /**
     * Returns class name of callback or false if callback is not valid.
     * @return bool|string
     */
    public function className(): bool|string
    {
        if (!$this->isValid())
        {
            return false;
        }
        $callbackArray = explode("::", $this->callback);
        return $callbackArray[0];
    }

    /**
     * Returns method name of callback or false if callback is not valid.
     * @return bool|string
     */
    public function classMethod(): bool|string
    {
        if (!$this->isValid())
        {
            return false;
        }
        $callbackArray = explode("::", $this->callback);
        return $callbackArray[1];
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}

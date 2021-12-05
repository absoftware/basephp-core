<?php
/**
 * @project BasePHP Core
 * @file BaseApplication.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Exceptions\Exception;
use Base\Tools\Resolver;
use Throwable;

/**
 * Interface ApplicationDelegate. Derived classes must not throw exceptions
 * in their constructors. All initialization which throws exceptions
 * should be implemented in open() method.
 */
interface BaseApplication
{
    /**
     * It is good place to open common resources for all controllers
     * like database connection or other settings. They should be owned
     * by application delegate.
     * @param Resolver $resolver
     */
    public function open(Resolver $resolver): void;

    /**
     * @return Request
     */
    public function getRequest(): Request;

    /**
     * Returns instance of main router. Router is not added to application
     * as dependency because it has to be created where exception handling is available.
     * @return BaseRouter
     */
    public function getRouter(): BaseRouter;

    /**
     * Returns current request path. It depends on custom
     * project settings or custom rewrite rules for incoming URL.
     * This path will be processed by router.
     * @return string Current request path.
     */
    public function currentRequestPath(): string;

    /**
     * @return mixed
     */
    public function getContext(): mixed;

    /**
     * Returns response for exception which is defined by BasePHP Core.
     * @param Exception $exception
     * @return Response
     */
    public function responseForException(Exception $exception): Response;

    /**
     * Returns response for throwable.
     * @param Throwable $throwable
     * @return Response
     */
    public function responseForThrowable(Throwable $throwable): Response;

    /**
     * It is good place to close common resources which are owned by application delegate.
     */
    public function close(): void;
}

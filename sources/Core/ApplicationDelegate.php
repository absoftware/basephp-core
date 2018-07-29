<?php
namespace Base\Core;

use Base\Exceptions\Exception;
use Base\Responses\Response;

interface ApplicationDelegate
{
    /**
     * Registers all routes for project.
     * @param Router $router
     *      Router object which has to be used to register routes.
     */
    function registerRoutes(Router $router): void;
    
    /**
     * Returns current request path. It depends on custom
     * project settings or custom rewrite rules for incoming URL.
     * This path will be processed by router.
     * @param Request $request
     *      Request object which can be used to get current request path.
     * @return string
     *      Current request path.
     */
    function currentRequestPath(Request $request): string;

    /**
     * Returns response for exception which is defined by BasePHP Core.
     * @param Request $request
     * @param Exception $exception
     * @return Response
     */
    function responseForException(Request $request, Exception $exception): Response;

    /**
     * Returns response for throwable.
     * @param Request $request
     * @param \Throwable $throwable
     * @return Response
     */
    function responseForThrowable(Request $request, \Throwable $throwable): Response;
}

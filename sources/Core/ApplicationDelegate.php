<?php
namespace Base\Core;

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
}

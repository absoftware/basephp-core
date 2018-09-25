<?php
/**
 * @project BasePHP Core
 * @file ApplicationDelegate.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Exceptions\Exception;

/**
 * Interface ApplicationDelegate. Derived classes must not throw exceptions
 * in their constructors. All initialization which throws exceptions
 * should be implemented in open() method.
 * @package Base\Core
 */
interface ApplicationDelegate
{
    /**
     * It is good place to open common resources for all controllers
     * like database connection or other settings. They should be owned
     * by application delegate.
     * @param Request $request
     */
    function open(Request $request): void;

    /**
     * Returns domain for current session.
     *
     * If wildcard domain is returned with dot at the beginning like ".example.com"
     * then session will be available in all subdomains.
     *
     * If specific domain is returned without dot at the beginning like "example.com",
     * "subdomain.example.com" or null then session will be available only for current hostname.
     *
     * @param Request $request
     * @return string
     */
    function sessionDomain(Request $request): ?string;

    /**
     * Creates instance of main router. Router is not added to application
     * as dependency because it has to be created where exception handling is available.
     * @return Router
     */
    function createRouter(): Router;
    
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
     * Creates subpage which represents part of website or API indicated by subdomain
     * or external domain with separated set of authorization settings for visitor.
     * @param Request $request
     * @param Session $session
     * @return Subpage
     */
    function createSubpage(Request $request, Session $session): Subpage;

    /**
     * Creates instance of visitor.
     * @param Request $request
     * @param Session $session
     * @param Subpage $subpage
     * @return Visitor
     */
    function createVisitor(Request $request, Session $session, Subpage $subpage): Visitor;

    /**
     * Creates authorization service. Implementation depends on client.
     * @return Authorization
     */
    function createAuthorizationService(): Authorization;

    /**
     * Returns response for exception which is defined by BasePHP Core.
     * @param Request $request
     * @param Exception $exception
     * @return Response
     */
    function responseForException(?Request $request, Exception $exception): Response;

    /**
     * Returns response for throwable.
     * @param Request $request
     * @param \Throwable $throwable
     * @return Response
     */
    function responseForThrowable(?Request $request, \Throwable $throwable): Response;

    /**
     * It is good place to close common resources which are owned by application delegate.
     */
    function close(): void;
}

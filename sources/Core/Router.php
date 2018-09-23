<?php
/**
 * @project BasePHP Core
 * @file Router.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

/**
 * Class Router represents main route group with default settings.
 *
 * TODO: Implement middleware to rate limit access to routes within application.
 * TODO: Implement groups of routes that path prefixes or middleware limits could be assigned to each group.
 * TODO: Implement fallbacks (?) instead of immediately 404.
 * TODO: Implement redirections on Router level.
 *
 * @package Base\Core
 */
class Router extends RouteGroup
{
    /**
     * Router constructor.
     */
    public function __construct()
    {
        parent::__construct("", "");
    }
}

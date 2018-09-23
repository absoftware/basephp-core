<?php
/**
 * @project BasePHP Core
 * @file Visitor.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

/**
 * Interface Visitor.
 * @package Base\Core
 */
interface Visitor
{
    /**
     * Returns true if visitor is authenticated. In other words we know who is logged in.
     * @return bool
     */
    function isLoggedIn(): bool;

    /**
     * Returns false if visitor is not authorized for at least one authorization identifier.
     * @param array $authorizationIds
     * @return bool
     */
    function isAuthorized(array $authorizationIds): bool;
}

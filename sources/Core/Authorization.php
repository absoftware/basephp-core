<?php
/**
 * @project BasePHP Core
 * @file Authorization.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Exceptions\AuthorizationException;

/**
 * Interface Authorization represents authorization service.
 * @package Base\Core
 */
interface Authorization
{
    /**
     * Throws exception when user is not authorized.
     * @param Request $request
     * @param Session $session
     * @param Subpage $subpage
     * @param Visitor $visitor
     * @param array $authorizationIds
     * @throws AuthorizationException
     */
    public function authorize(Request $request, Session $session, Subpage $subpage, Visitor $visitor, array $authorizationIds): void;
}

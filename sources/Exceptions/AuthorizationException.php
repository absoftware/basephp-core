<?php
/**
 * @project BasePHP Core
 * @file AuthorizationException.php created by Ariel Bogdziewicz on 22/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Exceptions;

/**
 * Class AuthorizationException defines error caused by wrong authorization of visitor.
 * It is thrown when visitor tries to visit resources which he is not permitted to get.
 * @package Base\Exceptions
 */
class AuthorizationException extends Exception
{
    /**
     * AuthorizationException constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, 401, $code, $previous);
    }
}

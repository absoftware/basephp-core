<?php
/**
 * @project BasePHP Core
 * @file AuthenticationException.php created by Ariel Bogdziewicz on 22/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Exceptions;

/**
 * Class AuthenticationException defines error caused by wrong authentication process.
 * @package Base\Exceptions
 */
class AuthenticationException extends Exception
{
    /**
     * AuthenticationException constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, 401, $code, $previous);
    }
}

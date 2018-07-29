<?php
/**
 * @project BasePHP Core
 * @file BadRequest.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Exceptions;

/**
 * Class BadRequest defines error caused by invalid request.
 * @package Base\Exceptions
 */
class BadRequest extends Exception
{
    /**
     * BadRequest constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, 400, $code, $previous);
    }
}

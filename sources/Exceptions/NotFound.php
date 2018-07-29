<?php
/**
 * @project BasePHP Core
 * @file NotFound.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Exceptions;

/**
 * Class NotFound defines exception caused by missing data or wrong URL.
 * @package Base\Exceptions
 */
class NotFound extends Exception
{
    /**
     * NotFound constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, 404, $code, $previous);
    }
}

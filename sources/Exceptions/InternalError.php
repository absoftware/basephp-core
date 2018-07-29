<?php
/**
 * @project BasePHP Core
 * @file InternalError.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Exceptions;

/**
 * Class InternalError defines critical error of code execution.
 * @package Base\Exceptions
 */
class InternalError extends Exception
{
    /**
     * InternalError constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, 500, $code, $previous);
    }
}

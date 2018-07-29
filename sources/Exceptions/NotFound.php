<?php
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

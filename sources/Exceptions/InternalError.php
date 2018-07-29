<?php
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

<?php
namespace Base\Exceptions;

class Exception extends \Exception
{
    /**
     * Recommended HTTP code which should be returned for this exception.
     * @var int
     */
    protected $httpCode = 200;

    /**
     * Exception constructor.
     * @param string $message
     * @param int $httpCode
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", int $httpCode = 200, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->httpCode = $httpCode;
    }

    /**
     * Returns recommended HTTP code which should be returned in response
     * when this exception is thrown.
     * @return int HTTP code.
     */
    public function httpCode()
    {
        return $this->httpCode;
    }

    /**
     * Returns description of this exception.
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

<?php
namespace Base\Core;

class ArgumentException extends Exception
{
    protected $argumentName = "";
    
    public function __construct(string $argumentName = "", $message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->argumentName = $argumentName;
    }
    
    public function argumentName()
    {
        return $this->argumentName;
    }

    public function __toString()
    {
        if ($this->argumentName)
        {
            return __CLASS__ . ": ['{$this->argumentName}', {$this->code}]: {$this->message}\n";
        }
        return parent::__toString();
    }
}

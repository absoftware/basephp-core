<?php
/**
 * @project BasePHP Core
 * @file ArgumentException.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Exceptions;

/**
 * Class ArgumentException defines internal error related to wrong function argument.
 * @package Base\Exceptions
 */
class ArgumentException extends InternalError
{
    /**
     * Name of argument.
     * @var string
     */
    protected $argumentName = "";

    /**
     * ArgumentException constructor.
     * @param string $argumentName
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(string $argumentName = "", $message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->argumentName = $argumentName;
    }

    /**
     * Name of argument.
     * @return string
     */
    public function argumentName()
    {
        return $this->argumentName;
    }

    /**
     * Description of this exception.
     * @return string
     */
    public function __toString()
    {
        if ($this->argumentName)
        {
            return get_class($this) . " ['{$this->argumentName}', {$this->code}]: '{$this->message}' in {$this->file}({$this->line})\n{$this->getTraceAsString()}\n";
        }
        return parent::__toString();
    }
}

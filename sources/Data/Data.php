<?php
/**
 * @project BasePHP Core
 * @file Data.php created by Ariel Bogdziewicz on 21/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Data;

/**
 * Interface Data represents data of any type.
 * @package Base\Data
 */
abstract class Data
{
    /**
     * Returns HTTP content type.
     * @return string
     */
    abstract public function contentType(): string;

    /**
     * Returns raw data.
     * @return string
     */
    abstract function content(): string;

    /**
     * Returns content length.
     * @return int
     */
    public function contentLength(): int
    {
        return strlen($this->content());
    }

    /**
     * Returns this object as string.
     * @return string
     */
    public function __toString()
    {
        return $this->content();
    }
}

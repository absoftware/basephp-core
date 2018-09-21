<?php
/**
 * @project BasePHP Core
 * @file Raw.php created by Ariel Bogdziewicz on 21/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Data;

/**
 * Class Raw represents raw data.
 * @package Base\Data
 */
class Raw extends Data
{
    /**
     * Input data.
     * @var string
     */
    private $data;

    /**
     * Content type for HTTP header.
     * @var string
     */
    private $contentType;

    /**
     * Raw constructor.
     */
    protected function __construct()
    {
        $this->data = "";
    }

    /**
     * Creates Raw from string.
     * @param string $data
     * @param string $contentType
     * @return Raw
     */
    static public function fromString(string $data, string $contentType): Raw
    {
        $raw = new self;
        $raw->data = $data;
        $raw->contentType = $contentType;
        return $raw;
    }

    /**
     * Returns HTTP content type.
     * @return string
     */
    public function contentType(): string
    {
        return $this->contentType;
    }

    /**
     * Returns raw data.
     * @return mixed
     */
    function content(): string
    {
        return $this->data;
    }
}

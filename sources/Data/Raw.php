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
    protected $data;

    /**
     * Content type for HTTP header.
     * @var string
     */
    protected $contentType;

    /**
     * Raw constructor.
     * @param string $data
     * @param string $contentType
     * @param string $charset
     */
    public function __construct(string $data, string $contentType = "text/plain", string $charset = "utf-8")
    {
        $this->data = $data;
        $this->contentType = $contentType;
        parent::__construct(null, $charset);
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

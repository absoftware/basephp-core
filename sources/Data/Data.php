<?php
/**
 * @project BasePHP Core
 * @file Data.php created by Ariel Bogdziewicz on 21/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Data;

use Base\Http\HttpHeader;

/**
 * Interface Data represents data of any type.
 * @package Base\Data
 */
abstract class Data
{
    /**
     * HTTP header associated to this kind of data.
     * @var HttpHeader|null
     */
    protected $httpHeader;

    /**
     * Encoding of response.
     * @var string
     */
    protected $charset;

    /**
     * Data constructor.
     * @param HttpHeader|null $httpHeader
     * @param string $charset
     */
    protected function __construct(HttpHeader $httpHeader = null, string $charset = "")
    {
        $this->charset = $charset;
        $dataHttpHeader = new HttpHeader([
            "Content-Type" => $this->charset ? $this->contentType() : $this->contentType() . "; charset={$this->charset}",
            "Content-Length" => $this->contentLength()
        ]);
        $this->httpHeader = $httpHeader ? $dataHttpHeader->replace($httpHeader) : $dataHttpHeader;
    }

    /**
     * Returns HTTP header associated to this kind of data.
     * @return HttpHeader|null
     */
    public function httpHeader(): HttpHeader
    {
        return $this->httpHeader;
    }

    /**
     * Returns content length.
     * @return int
     */
    public function contentLength(): int
    {
        return strlen($this->content());
    }

    /**
     * Returns raw data.
     * @return string
     */
    abstract function content(): string;

    /**
     * Returns content type for HTTP header.
     * @return string
     */
    abstract function contentType(): string;

    /**
     * Returns this object as string.
     * @return string
     */
    public function __toString()
    {
        return $this->content();
    }
}

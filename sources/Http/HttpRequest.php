<?php
/**
 * @project BasePHP Core
 * @file HttpRequest.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Http;

use Base\Data\Data;

/**
 * Class HttpRequest represents HTTP request.
 * @package Base\Http
 */
class HttpRequest
{
    const DELETE = "DELETE";
    const GET = "GET";
    const PATCH = "PATCH";
    const POST = "POST";
    const PUT = "PUT";

    /**
     * Request method.
     * @var string
     */
    protected $method;

    /**
     * Initial request URL.
     * @var string
     */
    protected $url;

    /**
     * HTTP header.
     * @var HttpHeader
     */
    protected $header;

    /**
     * Content of request.
     * @var Data
     */
    protected $content;

    /**
     * HttpRequest constructor.
     * @param string $url
     * @param string $method
     * @param HttpHeader|null $header
     * @param Data|null $content
     */
    public function __construct(string $url, string $method = self::GET, HttpHeader $header = null, Data $content = null)
    {
        $this->url = $url;
        $this->method = $method;
        $this->header = $header;
        $this->content = $content;
    }

    /**
     * Returns request method.
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Returns initial request url.
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * Returns HTTP header.
     * @return HttpHeader|null
     */
    public function header(): ?HttpHeader
    {
        return $this->header;
    }

    /**
     * Returns content of request.
     * @return Data
     */
    public function content(): ?Data
    {
        return $this->content;
    }
}

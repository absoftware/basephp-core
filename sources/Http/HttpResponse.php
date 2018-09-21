<?php
/**
 * @project BasePHP Core
 * @file HttpResponse.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Http;

use Base\Data;

/**
 * Class HttpResponse represents HTTP response.
 * @package Base\Http
 */
class HttpResponse
{
    /**
     * HTPP code.
     * @var int
     */
    private $httpCode = 0;

    /**
     * HTTP headers.
     * @var array
     */
    private $headers;

    /**
     * Content of response.
     * @var Data
     */
    private $content;

    /**
     * HttpResponse constructor.
     * @param int $httpCode
     * @param array $headers
     * @param Data $body
     */
    public function __construct(int $httpCode, array $headers, Data $content)
    {
        $this->httpCode = $httpCode;
        $this->headers = $headers;
        $this->content = $content;
    }

    /**
     * Returns HTTP code of response.
     * @return int
     */
    public function httpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * Returns HTTP headers.
     * @return array
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Content of response.
     * @return Data
     */
    public function content(): Data
    {
        return $this->content;
    }
}

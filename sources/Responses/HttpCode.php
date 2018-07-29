<?php
/**
 * @project BasePHP Core
 * @file HttpCode.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Responses;

/**
 * Class HttpCode represents empty response with specified HTTP code.
 * @package Base\Responses
 */
class HttpCode implements Response
{
    /**
     * HTTP code of response.
     * @var int
     */
    protected $httpCode;

    /**
     * HttpCode constructor.
     * @param int $httpCode HTTP code.
     */
    public function __construct(int $httpCode)
    {
        $this->httpCode = $httpCode;
    }

    /**
     * Returns empty body of response.
     * @return string Empty content of response.
     */
    public function body(): string
    {
        return "";
    }

    /**
     * Sets HTTP code into output buffer.
     */
    public function display(): void
    {
        http_response_code($this->httpCode);
    }
}

<?php
/**
 * @project BasePHP Core
 * @file Response.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Data\Data;
use Base\Http\HttpHeader;
use Base\Http\HttpResponse;

/**
 * Class Response represents Application's output HTTP response.
 */
class Response extends HttpResponse
{
    /**
     * Response constructor.
     * @param Data|null $content
     * @param int $httpCode
     * @param HttpHeader|null $header
     */
    public function __construct(Data $content = null, int $httpCode = 200, HttpHeader $header = null)
    {
        $header = $header ?? new HttpHeader([]);
        if ($content !== null)
        {
            $header = $header->replace($content->httpHeader());
        }
        parent::__construct($httpCode, $header, $content);
    }

    /**
     * Sets HTTP headers and renders body of response into output buffer.
     */
    public function display(): void
    {
        // Display HTTP code.
        $httpCode = $this->httpCode();
        if ($httpCode > 0)
        {
            http_response_code($httpCode);
        }

        // Display headers.
        foreach ($this->header()->headers() as $key => $value)
        {
            header("{$key}: {$value}");
        }

        // Display content.
        echo $this->content();
    }
}

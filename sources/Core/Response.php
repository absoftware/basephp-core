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
 * @package Base\Core
 */
class Response extends HttpResponse
{
    /**
     * Response constructor.
     * @param int $httpCode
     * @param HttpHeader $header
     * @param Data $content
     */
    public function __construct(int $httpCode, HttpHeader $header, Data $content)
    {
        $contentHttpHeaders = $content->httpHeader()->headers();
        $responseHttpHeaders = $header->headers();
        $headers = array_replace_recursive($contentHttpHeaders, $responseHttpHeaders);
        parent::__construct($httpCode, new HttpHeader($headers), $content);
    }

    /**
     * Sets HTTP headers and renders body of response into output buffer.
     */
    public function display(): void
    {
        /** @var HttpHeader $httpHeader */
        $httpHeader = $this->header();

        /** @var array $headers */
        $headers = $httpHeader ? $httpHeader->headers() : [];

        // Display headers.
        foreach ($headers as $key => $value);
        {
            header("{$key}: {$value}");
        }

        // Display content.
        echo $this->content();
    }
}

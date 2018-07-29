<?php
namespace Base\Responses;

/**
 * Class Html represents HTML response.
 * @package Base\Responses
 */
class Html extends Raw
{
    /**
     * Html constructor.
     * @param string $html HTML source code of web page.
     * @param int $httpCode HTTP code.
     * @param string $charset Charset of web page.
     */
    public function __construct(string $html, int $httpCode = 200, string $charset = "utf-8")
    {
        parent::__construct($html, "text/html", $httpCode, $charset);
    }
}

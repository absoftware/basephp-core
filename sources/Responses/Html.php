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
     * @param string $charset Charset of web page.
     * @param int $httpCode HTTP code.
     */
    public function __construct(string $html, $charset = "utf-8", $httpCode = 200)
    {
        parent::__construct($html, "text/html", $charset, $httpCode);
    }
}

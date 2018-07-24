<?php
namespace Base\Responses;

class Html extends Raw
{
    public function __construct(string $html, $charset = "utf-8", $httpCode = 200)
    {
        parent::__construct($html, "text/html", $charset, $httpCode);
    }
}

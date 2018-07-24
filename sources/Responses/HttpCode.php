<?php
namespace Base\Responses;

class HttpCode implements Response
{
    protected $httpCode;
    
    public function __construct(int $httpCode)
    {
        $this->httpCode = $httpCode;
    }
    
    public function get()
    {
        return "";
    }
    
    public function output()
    {
        http_response_code($this->httpCode);
    }
}

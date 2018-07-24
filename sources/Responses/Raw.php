<?php
namespace Base\Responses;

class Raw implements Response
{
    protected $output;
    protected $contentType;
    protected $charset;
    protected $httpCode;
    
    public function __construct(string $output, string $contentType = "text/plain", string $charset = "utf-8", int $httpCode = 200)
    {
        $this->output = $output;
        $this->contentType = $contentType;
        $this->charset = $charset;
        $this->httpCode = $httpCode;
    }
    
    public function get()
    {
        return $this->output;
    }
    
    public function display()
    {
        http_response_code($this->httpCode);
        if ($this->contentType)
        {
            if ($this->charset)
            {
                header("Content-Type: {$this->contentType}; charset={$this->charset}");
            }
            else
            {
                header("Content-Type: {$this->contentType}");
            }
        }
        echo $this->get();
    }
}

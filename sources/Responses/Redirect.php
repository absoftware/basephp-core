<?php
namespace Base\Responses;

class Redirect implements Response
{
    protected $url;
    
    public function __construct(string $url)
    {
        $this->url = $url;
    }
    
    public function get()
    {
        return "";
    }
    
    public function display()
    {
        header("Location: " . $this->url);
    }
}

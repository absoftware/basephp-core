<?php
namespace Base\Core\Responses;

class Redirect implements Response
{
    protected $url;
    
    public function __construct(string $url)
    {
        $this->url = $url;
    }
    
    public function get()
    {
        return $this->url;
    }
    
    public function output()
    {
        header("Location: " . $this->get());
    }
}

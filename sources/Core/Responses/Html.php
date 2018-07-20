<?php
namespace Base\Core\Responses;

class Html implements Response
{
    protected $html;
    
    public function __construct(string $html)
    {
        $this->html = $html;
    }
    
    public function get()
    {
        return $this->html;
    }
    
    public function output()
    {
        echo $this->get();
    }
}

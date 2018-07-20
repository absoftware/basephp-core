<?php
namespace Base\Core\Responses;

class Raw implements Response
{
    protected $output;
    
    public function __construct(string $output)
    {
        $this->output = $output;
    }
    
    public function get()
    {
        return $this->output;
    }
    
    public function output()
    {
        echo $this->get();
    }
}

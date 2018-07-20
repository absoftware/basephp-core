<?php
namespace Base\Core\Responses;

class Json implements Response
{
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function get()
    {
        return $this->data;
    }
    
    public function output()
    {
        echo json_encode($this->get());
    }
}

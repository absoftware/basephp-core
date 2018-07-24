<?php
namespace Base\Templates;

class PhpTemplate implements Template
{
    protected $variables = [];
    
    public function assign($name, $value): void
    {
        $this->variables[$name] = $value;
    }
    
    public function fetch($templateFile): string
    {
        extract($this->variables);
        ob_start();
        include($templateFile);
        return ob_get_clean();
    }
    
    public function display($templateFile): void
    {
        echo $this->fetch($templateFile);
    }
    
    public function fileExtension(): string
    {
        return ".tpl.php";
    }
}

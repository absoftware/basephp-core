<?php
namespace Base\Core;

use Base\Templates\PhpTemplate;
use Base\Templates\Template;

abstract class Controller
{
    private $request;
    private $template;
    private $session;
    
    public function __construct(Request $request = null, Template $template = null, Session $session)
    {
        $this->request = $request ?? new Request();
        $this->template = $template ?? new PhpTemplate();
        $this->session = $template ?? new Session();
    }
    
    protected function get($name)
    {
        return $this->request->get($name);
    }
    
    protected function requiredGet($name)
    {
        $param = $this->request->get($name);
        if (!$param)
        {
            throw new BadRequest();
        }
        return $param;
    }
    
    protected function post($name)
    {
        return $this->request->post($name);
    }
    
    protected function requiredPost($name)
    {
        $param = $this->request->post($name);
        if (!$param)
        {
            throw new BadRequest();
        }
        return $param;
    }
    
    protected function cookie($name)
    {
        return $this->request->cookie($name);
    }
    
    protected function setCookie($name, $value)
    {
        // TODO: Shouldn't be it response?
        return $this->request->setCookie($name, $value);
    }
    
    protected function session($name)
    {
        return $this->session->get($name);
    }
    
    protected function setSession($name, $value)
    {
        return $this->session->set($name, $value);
    }
    
    protected function assign($name, $value)
    {
        return $this->template->assign($name, $value);
    }
    
    protected function fetch(string $templateFile = null)
    {
        if (!$templateFile)
        {
            $reflection = new ReflectionClass($this);
            $path = $reflection->getFileName();
            $directory = dirname($path) . PATH_SEPARATOR;
            $templateFile = $directory . basename($path, ".php") . $this->template->fileExtension();
        }
        return $this->template->fetch($templateFile);
    }
    
    /**
     * Returns raw content of view for example HTML code
     * or other content which can be outputted straight into
     * output buffer or included in template.
     * @return string Raw content of view.
     */
    abstract public function getOutput(): string;
    
    /**
     * Allows using this object as string.
     */
    public function __toString(): string
    {
        return $this->getOutput();
    }
}

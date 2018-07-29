<?php
namespace Base\Core;

use Base\Exceptions\BadRequest;
use Base\Templates\PhpTemplate;
use Base\Templates\Template;

/**
 * Class Controller is base class for all other controllers.
 * It delivers all resources like request, session and templates to its derived classes.
 * @package Base\Core
 */
abstract class Controller
{
    /**
     * Current request.
     * @var Request
     */
    private $request;

    /**
     * Interface for template engine.
     * @var Template
     */
    private $template;

    /**
     * Current session.
     * @var Session
     */
    private $session;

    /**
     * Controller constructor.
     * @param Request|null $request
     * @param Template|null $template
     * @param Session|null $session
     */
    public function __construct(Request $request = null, Template $template = null, Session $session = null)
    {
        $this->request = $request ?? new Request();
        $this->template = $template ?? new PhpTemplate();
        $this->session = $session ?? new Session();
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
            throw new BadRequest("Missing GET param '$name'.");
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
            throw new BadRequest("Missing POST param '$name'.");
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
        //$this->request->setCookie($name, $value);
    }
    
    protected function session($name)
    {
        return $this->session->get($name);
    }
    
    protected function setSession($name, $value)
    {
        $this->session->set($name, $value);
    }
    
    protected function assign($name, $value)
    {
        $this->template->assign($name, $value);
    }
    
    protected function fetch(string $templateFile = null)
    {
        if (!$templateFile)
        {
            $reflection = new \ReflectionClass($this);
            $path = $reflection->getFileName();
            $directory = dirname($path) . DIRECTORY_SEPARATOR;
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

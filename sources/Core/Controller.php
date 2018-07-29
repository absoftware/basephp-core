<?php
/**
 * @project BasePHP Core
 * @file Controller.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
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
     */
    public function __construct()
    {
        $this->request = null;
        $this->template = null;
        $this->session = null;
    }

    public function setRequestObject(Request $request)
    {
        $this->request = $request;
    }

    public function setSessionObject(Session $session)
    {
        $this->session = $session;
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
    
    protected function setCookie($name, $value, int $lifeTime = 31536000, bool $currentDomainOnly = false)
    {
        $this->session->setCookie($name, $value, $lifeTime, $currentDomainOnly);
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

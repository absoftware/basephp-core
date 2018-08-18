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
use Base\Exceptions\Exception;
use Base\Responses\Html;
use Base\Responses\HttpCode;
use Base\Responses\Json;
use Base\Responses\PhpInfo;
use Base\Responses\Raw;
use Base\Responses\Redirect;
use Base\Templates\Template;
use Base\Tools\Resolver;

/**
 * Class Controller is base class for all other controllers.
 * It delivers all resources like request, session and templates to its derived classes.
 * @package Base\Core
 */
abstract class Controller
{
    /**
     * Identifier of request in common resources.
     */
    const COMMON_REQUEST = "controller_common_request";

    /**
     * Identifier of session in common resources.
     */
    const COMMON_SESSION = "controller_common_session";

    /**
     * Identifier of DI resolver in common resources.
     */
    const COMMON_RESOLVER = "controller_common_resolver";

    /**
     * Current request.
     * @var Request
     */
    private $request = null;

    /**
     * Interface for template engine.
     * @var Template
     */
    private $template = null;

    /**
     * Current session.
     * @var Session
     */
    private $session = null;

    /**
     * Dependency injection resolver.
     * @var Resolver
     */
    private $resolver = null;

    /**
     * Controller constructor.
     * @param Request|null $request
     * @param Session|null $session
     * @param Resolver|null $resolver
     */
    public function __construct(Request $request = null, Session $session = null, Resolver $resolver = null)
    {
        $this->request = $request ?? Common::singleton()->get(self::COMMON_REQUEST);
        $this->session = $session ?? Common::singleton()->get(self::COMMON_SESSION);
        $this->resolver = $resolver ?? Common::singleton()->get(self::COMMON_RESOLVER);
    }

    /**
     * Sets request object.
     * @param Request $request
     */
    public function setRequestObject(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Sets session object.
     * @param Session $session
     */
    public function setSessionObject(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Sets template engine.
     * @param Template $template
     */
    public function setTemplateObject(Template $template)
    {
        $this->template = $template;
    }

    /**
     * Sets resolver object.
     * @param Resolver $resolver
     */
    public function setResolverObject(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Returns dependency injection resolver.
     * @return Resolver|null
     */
    public function resolver(): ?Resolver
    {
        return $this->resolver;
    }

    /**
     * Returns true if connection is performed through HTTPS protocol.
     * @return bool
     */
    protected function isHttps(): bool
    {
        return $this->request->isHttps();
    }

    /**
     * Returns true if request is an AJAX request.
     * @return bool
     */
    protected function isAjax(): bool
    {
        return $this->request->isAjax();
    }

    /**
     * Protocol.
     * @return string
     */
    public function protocol(): string
    {
        return $this->request->protocol();
    }

    /**
     * Full host name.
     * @return string
     */
    public function host(): string
    {
        return $this->request->host();
    }

    /**
     * Current port.
     * @return int
     */
    public function port(): int
    {
        return $this->request->port();
    }

    /**
     * Request URI which means path and arguments starting from slash character.
     * @return string
     */
    public function uri(): string
    {
        return $this->request->uri();
    }

    /**
     * Path of request without arguments starting from slash character.
     * @return string
     */
    public function path(): string
    {
        return $this->request->path();
    }

    /**
     * Gets GET param.
     * @param $name
     * @return array|float|null|string
     */
    protected function get($name)
    {
        return $this->request->get($name);
    }

    /**
     * Gets GET param or throws exception if param is not set.
     * @param $name
     * @return array|float|null|string
     * @throws BadRequest
     */
    protected function requiredGet($name)
    {
        $param = $this->request->get($name);
        if (!$param)
        {
            throw new BadRequest("Missing GET param '$name'.");
        }
        return $param;
    }

    /**
     * Gets POST param.
     * @param $name
     * @return array|float|null|string
     */
    protected function post($name)
    {
        return $this->request->post($name);
    }

    /**
     * Gets POST param or throws exception if param is not set.
     * @param $name
     * @return array|float|null|string
     * @throws BadRequest
     */
    protected function requiredPost($name)
    {
        $param = $this->request->post($name);
        if (!$param)
        {
            throw new BadRequest("Missing POST param '$name'.");
        }
        return $param;
    }

    /**
     * Gets cookie from request.
     * @param $name
     * @return null
     */
    protected function cookie($name)
    {
        return $this->request->cookie($name);
    }

    /**
     * Sets cookie.
     * @param $name
     * @param $value
     * @param int $lifeTime
     * @param bool $currentDomainOnly
     */
    protected function setCookie($name, $value, int $lifeTime = 31536000, bool $currentDomainOnly = false)
    {
        $this->session->setCookie($name, $value, $lifeTime, $currentDomainOnly);
    }

    /**
     * Gets session variable.
     * @param $name
     * @return mixed
     */
    protected function session($name)
    {
        return $this->session->get($name);
    }

    /**
     * Sets session variable.
     * @param $name
     * @param $value
     */
    protected function setSession($name, $value): void
    {
        $this->session->set($name, $value);
    }

    /**
     * Check existence of session variable.
     * @param $name
     * @return bool
     */
    protected function issetSession($name): bool
    {
        return $this->session->isset($name);
    }

    /**
     * Destroys session variable.
     * @param $name
     */
    protected function unsetSession($name): void
    {
        $this->session->unset($name);
    }

    /**
     * Assigns template variable.
     * @param $name
     * @param $value
     */
    protected function assign($name, $value)
    {
        $this->template->assign($name, $value);
    }

    /**
     * Returns rendered template.
     * @param string|null $templateFile
     * @return string
     */
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
     * Returns raw response.
     * @param string $output
     * @param string $contentType
     * @param int $httpCode
     * @param string $charset
     * @return Raw
     */
    protected function raw(string $output, string $contentType = "text/plain", int $httpCode = 200, string $charset = "utf-8"): Raw
    {
        return new Raw($output, $contentType, $httpCode, $charset);
    }

    /**
     * Returns HTML response.
     * @param string $html
     * @param int $httpCode
     * @param string $charset
     * @return Html
     */
    protected function html(string $html, int $httpCode = 200, string $charset = "utf-8"): Html
    {
        return new Html($html, $httpCode, $charset);
    }

    /**
     * Returns JSON response.
     * @param array $data
     * @param int $httpCode
     * @param string $charset
     * @return Json
     */
    protected function json(array $data, int $httpCode = 200, string $charset = "utf-8"): Json
    {
        return new Json($data, $httpCode, $charset);
    }

    /**
     * Returns redirect response.
     * @param string $url
     * @return Redirect
     */
    protected function redirect(string $url): Redirect
    {
        return new Redirect($url);
    }

    /**
     * Returns HTTP code without body.
     * @param int $httpCode
     * @return HttpCode
     */
    protected function httpCode(int $httpCode): HttpCode
    {
        return new HttpCode($httpCode);
    }

    /**
     * Returns PHP info response.
     * @return PhpInfo
     */
    protected function phpInfo(): PhpInfo
    {
        return new PhpInfo();
    }

    /**
     * Derived implementation of controller may deliver response in case of BasePHP exception.
     * @param Exception $exception
     * @return Response|null
     */
    public function responseForException(Exception $exception): ?Response
    {
        return null;
    }

    /**
     * Derived implementation of controller may deliver response in case of unknown exception.
     * @param \Throwable $throwable
     * @return Response|null
     */
    public function responseForThrowable(\Throwable $throwable): ?Response
    {
        return null;
    }
}

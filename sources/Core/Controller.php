<?php
/**
 * @project BasePHP Core
 * @file Controller.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Exceptions\Exception;
use Base\Data\Data;
use Base\Data\Html;
use Base\Data\Json;
use Base\Data\Raw;
use Base\Http\HttpHeader;
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
     * Identifier of visitor in common resources.
     */
    const COMMON_VISITOR = "controller_common_visitor";

    /**
     * Current request.
     * @var Request
     */
    protected $request = null;

    /**
     * Interface for template engine.
     * @var Template
     */
    protected $template = null;

    /**
     * Current session.
     * @var Session
     */
    protected $session = null;

    /**
     * Dependency injection resolver.
     * @var Resolver
     */
    protected $resolver = null;

    /**
     * Visitor.
     * @var Visitor
     */
    private $visitor = null;

    /**
     * Controller constructor.
     * @param Request|null $request
     * @param Session|null $session
     * @param Resolver|null $resolver
     * @param Visitor|null $visitor
     */
    public function __construct(Request $request = null, Session $session = null, Resolver $resolver = null, Visitor $visitor = null)
    {
        $this->request = $request ?? Common::singleton()->get(self::COMMON_REQUEST);
        $this->session = $session ?? Common::singleton()->get(self::COMMON_SESSION);
        $this->resolver = $resolver ?? Common::singleton()->get(self::COMMON_RESOLVER);
        $this->visitor = $visitor ?? Common::singleton()->get(self::COMMON_VISITOR);
    }

    /**
     * Returns rendered template.
     * @param string|null $templateFile
     * @param string $contentType
     * @param int $httpCode
     * @param string $charset
     * @return Response
     * @throws \ReflectionException
     */
    protected function template(string $templateFile = null, string $contentType = "text/html", int $httpCode = 200, string $charset = "utf-8"): Response
    {
        if (!$templateFile)
        {
            $reflection = new \ReflectionClass($this);
            $path = $reflection->getFileName();
            $directory = dirname($path) . DIRECTORY_SEPARATOR;
            $templateFile = $directory . basename($path, ".php") . $this->template->fileExtension();
        }

        $content = $this->template->fetch($templateFile);
        return new Response(new Raw($content, $contentType, $charset), $httpCode);
    }

    /**
     * Returns response for any type.
     * @param Data $data
     * @param int $httpCode
     * @return Response
     */
    protected function data(Data $data, int $httpCode = 200): Response
    {
        return new Response($data, $httpCode);
    }

    /**
     * Returns raw response.
     * @param string $output
     * @param string $contentType
     * @param int $httpCode
     * @param string $charset
     * @return Response
     */
    protected function raw(string $output, string $contentType = "text/plain", int $httpCode = 200, string $charset = "utf-8"): Response
    {
        return new Response(new Raw($output, $contentType, $charset), $httpCode);
    }

    /**
     * Returns HTML response.
     * @param string $html
     * @param int $httpCode
     * @param string $charset
     * @return Response
     */
    protected function html(string $html, int $httpCode = 200, string $charset = "utf-8"): Response
    {
        return new Response(new Html($html, $charset), $httpCode);
    }

    /**
     * Returns JSON response.
     * @param array $data
     * @param int $httpCode
     * @param string $charset
     * @return Response
     */
    protected function json(array $data, int $httpCode = 200, string $charset = "utf-8"): Response
    {
        return new Response(new Json($data, $charset), $httpCode);
    }

    /**
     * Returns redirect as response.
     * @param string $url
     * @return Response
     */
    protected function redirect(string $url): Response
    {
        // HTTP code will be set automatically for header Location.
        return new Response(null, 0, new HttpHeader(["Location" => $url]));
    }

    /**
     * Returns HTTP code without body.
     * @param int $httpCode
     * @return Response
     */
    protected function httpCode(int $httpCode): Response
    {
        return new Response(null, $httpCode, null);
    }

    /**
     * Derived implementation of controller may deliver response in case of BasePHP exception.
     * @param Exception $exception
     * @return Response|null
     */
    public function responseForException(/** @noinspection PhpUnusedParameterInspection */ Exception $exception): ?Response
    {
        return null;
    }

    /**
     * Derived implementation of controller may deliver response in case of unknown exception.
     * @param \Throwable $throwable
     * @return Response|null
     */
    public function responseForThrowable(/** @noinspection PhpUnusedParameterInspection */ \Throwable $throwable): ?Response
    {
        return null;
    }
}

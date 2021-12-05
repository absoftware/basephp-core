<?php
/**
 * @project BasePHP Core
 * @file BaseController.php created by Ariel Bogdziewicz on 29/07/2018
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
use Throwable;

/**
 * Class Controller is base class for all other controllers.
 * It delivers all resources like request, session and templates to its derived classes.
 */
abstract class BaseController
{
    /**
     * @var Template
     */
    protected Template $template;

    /**
     * Returns rendered template.
     * @param string|null $templateFile
     * @param string $contentType
     * @param int $httpCode
     * @param string $charset
     * @return Response
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
     * Returns HTTP codes without body.
     * @param int $httpCode
     * @return Response
     */
    protected function httpCode(int $httpCode): Response
    {
        return new Response(null, $httpCode, null);
    }

    /**
     * Derived implementation of controller may deliver response in case of BasePHP exception.
     * @param mixed $ctx
     * @param Exception $exception
     * @return Response|null
     */
    public function responseForException(/** @noinspection PhpUnusedParameterInspection */ mixed $ctx, Exception $exception): ?Response
    {
        return null;
    }

    /**
     * Derived implementation of controller may deliver response in case of unknown exception.
     * @param mixed $ctx
     * @param Throwable $throwable
     * @return Response|null
     */
    public function responseForThrowable(/** @noinspection PhpUnusedParameterInspection */ mixed $ctx, Throwable $throwable): ?Response
    {
        return null;
    }
}

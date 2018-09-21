<?php
/**
 * @project BasePHP Core
 * @file HttpConnection.php created by Ariel Bogdziewicz on 21/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Http;

use Base\Data\Data;
use Base\Data\Json;
use Base\Data\Raw;

/**
 * Class Connection represents single HTTP connection for one HTTP request.
 * @package Base\Http
 */
class HttpConnection
{
    /**
     * Initial HTTP request associated with this connection.
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * Final response of connection.
     * @var HttpResponse
     */
    private $httpResponse;

    /**
     * CURL error code.
     * @var int
     */
    private $errorCode;

    /**
     * CURL error message.
     * @var string
     */
    private $errorMessage;

    /**
     * Headers from response.
     * @var array
     */
    protected $responseHttpHeaders = [];

    /**
     * Returned content type.
     * @var string
     */
    protected $responseContentType = "";

    /**
     * HttpConnection constructor.
     * @param HttpRequest $httpRequest
     */
    public function __construct(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;
        $this->httpResponse = null;
        $this->errorCode = 0;
        $this->errorMessage = "";
    }

    /**
     * Returns initial HTTP request.
     * @return HttpRequest
     */
    public function request(): HttpRequest
    {
        return $this->httpRequest;
    }

    /**
     * Returns last response.
     * @return HttpResponse
     */
    public function response(): HttpResponse
    {
        return $this->httpResponse;
    }

    /**
     * Returns CURL error message.
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * Returns CURL error code.
     * @return int
     */
    public function errorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * Returns final response.
     * @return HttpResponse
     */
    public function send(): ?HttpResponse
    {
        // Create CURL connection.
        $curl = curl_init();
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->httpRequest->method());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $this->httpRequest->url());

        // Initialize headers.
        $header = $this->httpRequest->header();
        $headers = $header && is_array($header->headers()) ? $header->headers() : [];

        // Set content.
        $requestData = $this->httpRequest->content();
        if ($requestData)
        {
            if (!$header || !$header->contains("Content-Type"))
            {
                $headers["Content-Type"] = $requestData->contentType();
            }
            if (!$header || !$header->contains("Content-Length"))
            {
                $headers["Content-Length"] = (string)$requestData->contentLength();
            }
            curl_setopt($curl, CURLOPT_POST, $requestData->contentType() === "application/x-www-form-urlencoded" ? 1 : 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData->content());
        }

        // Set headers.
        $headerLines = [];
        foreach ($headers as $key => $value)
        {
            $headersLines[] = "{$key}:{$value}";
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerLines);

        // Set parser for response headers.
        curl_setopt($curl, CURLOPT_HEADERFUNCTION, array($this, 'readHeaderLine'));

        // Execute request.
        $responseContent = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->errorMessage = curl_error($curl);
        $this->errorCode = curl_errno($curl);

        // Close CURL connection.
        if (is_resource($curl))
        {
            curl_close($curl);
        }

        // Return null in case of error.
        if ($this->errorCode !== 0)
        {
            return null;
        }

        // Parse content.
        /** @var Data|null $responseData */
        $responseData = null;
        switch (mb_strtolower($this->responseContentType))
        {
            case Json::CONTENT_TYPE_JSON:
                $responseData = Json::fromString($responseContent);
                break;
            default:
                $responseData = Raw::fromString($responseContent, $this->responseContentType);
                break;
        }

        // Create response object.
        return new HttpResponse($httpCode, new HttpHeader($this->responseHttpHeaders), $responseData);
    }

    /**
     * Reads one line from response of CURL connection.
     * @param $curl
     * @param $headerLine
     * @return int
     */
    protected function readHeaderLine(/** @noinspection PhpUnusedParameterInspection */ $curl, $headerLine)
    {
        $line = trim($headerLine);
        if ($line)
        {
            $exploded = explode(":", $line);
            if (is_array($exploded) && count($exploded) > 2)
            {
                // Save header line.
                $key = trim($exploded[0]);
                $value = trim(implode(":", array_slice($exploded, 1)));
                $this->responseHttpHeaders[$key] = $value;

                // Get content type.
                if (strcasecmp($key, "Content-Type") === 0)
                {
                    $this->responseContentType = $value;
                }
            }
        }
        return strlen($headerLine);
    }
}

<?php
namespace Base\Responses;

/**
 * Class Raw represents raw response of any kind.
 * It just outputs response as provided.
 * @package Base\Responses
 */
class Raw implements Response
{
    protected $output;
    protected $contentType;
    protected $httpCode;
    protected $charset;

    /**
     * Raw constructor.
     * @param string $output Raw output for response.
     * @param string $contentType Content type of response.
     * @param int $httpCode HTTP code.
     * @param string $charset Charset of response.
     */
    public function __construct(string $output, string $contentType = "text/plain", int $httpCode = 200, string $charset = "utf-8")
    {
        $this->output = $output;
        $this->contentType = $contentType;
        $this->httpCode = $httpCode;
        $this->charset = $charset;
    }

    /**
     * Returns body of response as string.
     * @return string Raw body of response.
     */
    public function body(): string
    {
        return $this->output;
    }

    /**
     * Sets HTTP headers and renders raw body of response into output buffer.
     */
    public function display(): void
    {
        http_response_code($this->httpCode);
        if ($this->contentType)
        {
            if ($this->charset)
            {
                header("Content-Type: {$this->contentType}; charset={$this->charset}");
            }
            else
            {
                header("Content-Type: {$this->contentType}");
            }
        }
        echo $this->body();
    }
}

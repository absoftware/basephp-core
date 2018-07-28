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
    protected $charset;
    protected $httpCode;

    /**
     * Raw constructor.
     * @param string $output Raw output for response.
     * @param string $contentType Content type of response.
     * @param string $charset Charset of response.
     * @param int $httpCode HTTP code.
     */
    public function __construct(string $output, string $contentType = "text/plain", string $charset = "utf-8", int $httpCode = 200)
    {
        $this->output = $output;
        $this->contentType = $contentType;
        $this->charset = $charset;
        $this->httpCode = $httpCode;
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

<?php
namespace Base\Responses;

/**
 * Class Redirect performs redirect.
 * @package Base\Responses
 */
class Redirect implements Response
{
    /**
     * Redirect url.
     * @var string
     */
    protected $url;

    /**
     * Redirect constructor.
     * @param string $url Redirect url.
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Returns empty body of response.
     * @return string Empty content of response.
     */
    public function body(): string
    {
        return "";
    }

    /**
     * Sets HTTP header with redirect.
     */
    public function display(): void
    {
        header("Location: " . $this->url);
    }
}

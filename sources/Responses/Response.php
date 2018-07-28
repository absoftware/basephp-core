<?php
namespace Base\Responses;

/**
 * Interface Response represents any response sent back to client.
 * @package Base\Responses
 */
interface Response
{
    /**
     * Returns body of response as string.
     * It may be html code, text plain or binary content of image.
     * @return string Body of response.
     */
    function body(): string;

    /**
     * Sets HTTP headers and renders body of response into output buffer.
     */
    function display(): void;
}

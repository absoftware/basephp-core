<?php
namespace Base\Responses;

/**
 * Class Json represents JSON response.
 * @package Base\Responses
 */
class Json extends Raw
{
    /**
     * Json constructor.
     * @param array $data Dictionary with response.
     * @param int $httpCode HTTP code.
     * @param string $charset Charset of response.
     */
    public function __construct(array $data, int $httpCode = 200, string $charset = "utf-8")
    {
        $output = json_encode($data);
        if ($output === false)
        {
            $httpCode = 500;
            $output = json_encode([
                "errorCode" => json_last_error(),
                "errorMessage" => json_last_error_msg()
            ]);
        }
        parent::__construct($output, "application/json", $httpCode, $charset);
    }

    /**
     * Sets HTTP headers and renders JSON into output buffer.
     */
    public function display(): void
    {
        header('Access-Control-Allow-Origin: *');
        parent::display();
    }
}

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
     * @param string $charset Charset of response.
     * @param int $httpCode HTTP code.
     */
    public function __construct(array $data, $charset = "utf-8", $httpCode = 200)
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
        parent::__construct($output, "application/json", $charset, $httpCode);
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

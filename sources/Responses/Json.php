<?php
namespace Base\Responses;

class Json extends Raw
{
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
}

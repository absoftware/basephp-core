<?php
/**
 * @project BasePHP Core
 * @file Json.php created by Ariel Bogdziewicz on 21/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Data;

use Base\Exceptions\InternalError;
use Base\Http\HttpHeader;

/**
 * Class Json represents data in JSON format.
 * @package Base\Data
 */
class Json extends Data
{
    const CONTENT_TYPE_JSON = "application/json";

    /**
     * Input array.
     * @var array
     */
    private $data;

    /**
     * Json constructor.
     * @param array $data
     * @param string $charset
     */
    public function __construct(array $data, string $charset = "utf-8")
    {
        $this->data = $data;
        parent::__construct(new HttpHeader(["Access-Control-Allow-Origin" => "*"]), $charset);
    }

    /**
     * Returns data.
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Returns encoded JSON string.
     * @param array $data
     * @param string $charset
     * @return string
     * @throws InternalError
     */
    static public function encode(array $data, string $charset = "utf-8"): string
    {
        $json = new Json($data, $charset);
        return $json->content();
    }

    /**
     * Creates Json from string in JSON format.
     * @param string $jsonString
     * @return Json
     * @throws InternalError
     */
    static public function decode(string $jsonString): Json
    {
        $json = new self([]);
        $data = json_decode($jsonString, true);
        if (!is_array($data))
        {
            throw new InternalError("JSON decoding failed.");
        }
        $json->data = $data;
        return $json;
    }

    /**
     * Returns HTTP content type.
     * @return string
     */
    public function contentType(): string
    {
        return self::CONTENT_TYPE_JSON;
    }

    /**
     * Returns encoded JSON string.
     * @return string
     * @throws InternalError
     */
    function content(): string
    {
        $jsonString = json_encode($this->data, JSON_PRETTY_PRINT);
        if ($jsonString === false)
        {
            throw new InternalError("JSON encoding failed.");
        }
        return $jsonString;
    }
}

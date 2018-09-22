<?php
/**
 * @project BasePHP Core
 * @file Json.php created by Ariel Bogdziewicz on 21/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Data;

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
     */
    protected function __construct()
    {
        $this->data = [];
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
     * Creates Json from associative array.
     * @param array $data
     * @return Json
     */
    static public function fromDictionary(array $data): Json
    {
        $json = new self;
        $json->data = $data;
        return $json;
    }

    /**
     * Creates Json from string in JSON format.
     * @param string $jsonString
     * @return Json
     */
    static public function fromString(string $jsonString): Json
    {
        $json = new self;
        $json->data = json_decode($jsonString, true);
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
     * Returns raw data.
     * @return mixed
     */
    function content(): string
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}

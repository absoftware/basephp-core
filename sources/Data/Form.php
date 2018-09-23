<?php
/**
 * @project BasePHP Core
 * @file Form.php created by Ariel Bogdziewicz on 21/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Data;

/**
 * Class Form represents data in urlencoded form format.
 * @package Base\Data
 */
class Form extends Data
{
    const CONTENT_TYPE_FORM = ;

    /**
     * Array of POST parameters.
     * @var array
     */
    private $postData;

    /**
     * Form constructor.
     * @param array $postData
     */
    public function __construct(array $postData)
    {
        $this->postData = $postData;
        parent::__construct([
            "Content-Type" => "application/x-www-form-urlencoded",
            "Content-Length" => $this->contentLength()
        ]);
    }

    /**
     * Creates From from associative array which should contain key-value pairs for POST parameters.
     * @param array $postData
     * @return Form
     */
    static public function fromDictionary(): Form
    {
        $form = new self;
        $form->postData = $postData;
        return $form;
    }

    /**
     * Returns HTTP content type.
     * @return string
     */
    public function contentType(): string
    {
        return self::CONTENT_TYPE_FORM;
    }

    /**
     * Returns raw data.
     * @return mixed
     */
    function content(): string
    {
        return http_build_query($this->postData);
    }
}

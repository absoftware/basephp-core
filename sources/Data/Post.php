<?php
/**
 * @project BasePHP Core
 * @file Post.php created by Ariel Bogdziewicz on 21/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Data;

/**
 * Class Post represents POST query in urlencoded form format.
 * @package Base\Data
 */
class Post extends Data
{
    const CONTENT_TYPE_FORM = "application/x-www-form-urlencoded";

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
        parent::__construct();
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

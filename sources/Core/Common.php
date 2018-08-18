<?php
/**
 * @project BasePHP Core
 * @file Common.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

/**
 * Class Common is singleton which keeps collection of objects.
 * @package Base\Core
 */
class Common
{
    /**
     * Common resources.
     * @var array
     */
    private $commonResources = [];

    /**
     * The only instance of Common object.
     * @var Common|null
     */
    private static $instance = null;

    /**
     * Private Common constructor ensures that there is only one instance of this class.
     */
    private function __construct()
    {
    }

    /**
     * Returns the only instance of this class.
     * @return Common
     */
    public static function & singleton(): Common
    {
        if (self::$instance == null)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Returns resource with given key.
     * @param string $resourceKey
     * @param mixed|null $defaultResource
     * @return mixed|null
     */
    public function get(string $resourceKey, $defaultResource = null)
    {
        return isset($this->commonResources[$resourceKey]) ? $this->commonResources[$resourceKey] : $defaultResource;
    }

    /**
     * Sets resource with given key.
     * @param string $resourceKey
     * @param $resource
     */
    public function set(string $resourceKey, $resource)
    {
        $this->commonResources[$resourceKey] = $resource;
    }
}

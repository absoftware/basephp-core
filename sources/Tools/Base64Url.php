<?php
/**
 * @project BasePHP Core
 * @file Base64Url.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Tools;

/**
 * Class Base64Url.
 * @package Base\Tools
 */
class Base64Url
{
    /**
     * Encodes data using Base64Url method.
     * @param string $data
     * @return string
     */
    static public function encode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * Decodes data using Base64Url methods.
     * @param string $encodedData
     * @return string
     */
    static public function decode(string $encodedData): string
    {
        return base64_decode(strtr($encodedData, '-_', '+/'));
    }
}

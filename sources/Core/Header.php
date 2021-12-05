<?php
/**
 * @project BasePHP Core
 * @file Header.php created by Ariel Bogdziewicz on 21/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Http\HttpHeader;

/**
 * Class Header represents HTTP header of incoming request.
 */
class Header extends HttpHeader
{
    /**
     * Header constructor.
     */
    public function __construct()
    {
        parent::__construct($this->serverHeaders());
    }

    /**
     * Returns HTTP headers from $_SERVER array as associative array
     * with converted name of headers.
     * @return array
     */
    protected function serverHeaders(): array
    {
        if (!isset($_SERVER) || !is_array($_SERVER))
        {
            return [];
        }

        $headers = [];
        foreach ($_SERVER as $key => $value)
        {
            $exploded = explode("_", $key);
            $count = is_array($exploded) ? count($exploded) : 0;
            if ($count < 2 || $exploded[0] !== 'HTTP')
            {
                continue;
            }

            $keyExploded = array_slice($exploded, 1);
            foreach ($keyExploded as &$part)
            {
                $part = ucwords(mb_strtolower($part));
            }

            unset($part);
            $headers[implode("-", $keyExploded)] = $value;
        }

        return $headers;
    }
}

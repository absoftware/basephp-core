<?php
/**
 * @project BasePHP Core
 * @file HttpHeader.php created by Ariel Bogdziewicz on 21/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Http;

/**
 * Class HttpHeader represents HTTP header lines.
 * @package Base\Http
 */
class HttpHeader
{
    /**
     * Header lines.
     * @var array
     */
    protected $headers;

    /**
     * HttpHeader constructor.
     * @param array $headers
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * Returns all header lines as associative array.
     * @return array
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Returns value of header for given key line if available.
     * @param string $key
     * @return null|string
     */
    public function value(string $key): ?string
    {
        foreach ($this->headers as $headerKey => $value)
        {
            if (strcasecmp($key, $headerKey) === 0)
            {
                return $value;
            }
        }
        return null;
    }

    /**
     * Returns true if header contains line with given key.
     * @param $key
     * @return bool
     */
    public function contains($key): bool
    {
        return $this->value($key) != null;
    }
}

<?php
/**
 * @project BasePHP Core
 * @file JwtDecoded.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Tools;

use Base\Exceptions\ArgumentException;

/**
 * Class JwtDecoded represents decoded JSON Web Token.
 * @package Base\Tools
 */
class JwtDecoded
{
    /**
     * Header.
     * @var array
     */
    protected $header;

    /**
     * Payload.
     * @var array
     */
    protected $payload;

    /**
     * Secret phrase for HS256 algorithm.
     * @var string
     */
    protected $secret;

    /**
     * JwtDecoded constructor.
     * @param array $header
     * @param array $payload
     * @param string $secret
     * @throws ArgumentException
     */
    public function __construct(array $header, array $payload, string $secret)
    {
        // Validate supported algorithm.
        if (!isset($header['alg']) || $header['alg'] != "HS256")
        {
            throw new ArgumentException("header", "Wrong header field 'alg'. The only supported value is 'HS256'.");
        }

        // Validate supported token type.
        if (!isset($header['typ']) || $header['typ'] != "JWT")
        {
            throw new ArgumentException("header", "Wrong header field 'typ'. The only supported value is 'JWT'.");
        }

        // Assign attributes.
        $this->header = $header;
        $this->payload = $payload;
        $this->secret = $secret;
    }

    /**
     * Returns JWT header.
     * @return array
     */
    public function header(): array
    {
        return $this->header;
    }

    /**
     * Returns payload.
     * @return array
     */
    public function payload(): array
    {
        return $this->payload;
    }

    /**
     * Encodes JWT.
     * @return JwtEncoded
     */
    public function encode(): JwtEncoded
    {
        return new JwtEncoded($this, $this->secret);
    }

    /**
     * Returns encoded JWT as string.
     */
    public function __toString()
    {
        $header64 = Base64Url::encode(json_encode($this->header, JSON_PRETTY_PRINT));
        $payload64 = Base64Url::encode(json_encode($this->payload, JSON_PRETTY_PRINT));
        $data = $header64 . "." . $payload64;
        return $data . "." . Base64Url::encode(hash_hmac('sha256', $data, $this->secret, true));
    }
}

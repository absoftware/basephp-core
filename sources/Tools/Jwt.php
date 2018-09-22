<?php
/**
 * @project BasePHP Core
 * @file Jwt.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Tools;

use Base\Exceptions\ArgumentException;
use Base\Exceptions\InternalError;

/**
 * Class Jwt represents decoded JSON Web Token.
 * @package Base\Tools
 */
class Jwt
{
    /**
     * Encoded JSON Web Token generated from arrays.
     * @var string
     */
    protected $validToken;

    /**
     * Encoded JSON Web Token delivered by client.
     * @var string
     */
    protected $token;

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
     * Jwt constructor.
     * @param array $header
     * @param array $payload
     * @param string $secret
     * @throws ArgumentException
     * @throws InternalError
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
        $this->validToken = $this->__toString();
        $this->token = $this->validToken;
    }

    /**
     * Creates encoded JSON Web Token.
     * @param array $header
     * @param array $payload
     * @param string $secret
     * @return string
     * @throws ArgumentException
     * @throws InternalError
     */
    static public function encode(array $header, array $payload, string $secret): string
    {
        $jwt = new self($header, $payload, $secret);
        return $jwt->__toString();
    }

    /**
     * Creates decoded JSON Web Token.
     * @param string $token
     * @param string $secret
     * @return Jwt
     * @throws ArgumentException
     * @throws InternalError
     */
    static public function decode(string $token, string $secret): Jwt
    {
        // Explode token into 3 basic parts.
        $exploded = explode(".", $token);
        $count = is_array($exploded) ? count($exploded) : 0;
        $header = $count > 0 ? $exploded[0] : null;
        $payload = $count > 1 ? $exploded[1] : null;

        // Decode header.
        $decodedHeader = $header ? json_decode(Base64Url::decode($header), true) : null;
        if (!is_array($decodedHeader))
        {
            $decodedHeader = null;
        }

        // Decode payload.
        $decodedPayload = $payload ? json_decode(Base64Url::decode($payload), true) : null;
        if (!is_array($decodedPayload))
        {
            $decodedPayload = null;
        }

        // Create decoded object.
        $jwt = new Jwt($decodedHeader, $decodedPayload, $secret);
        $jwt->token = $token;
        return $jwt;
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
     * Returns true if token is valid.
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->token && mb_strlen($this->token) > 4 && $this->token === $this->validToken;
    }

    /**
     * Returns encoded JWT as string.
     * @return string
     * @throws InternalError
     */
    public function __toString()
    {
        $headerJson = json_encode($this->header, JSON_PRETTY_PRINT);
        if ($headerJson === false)
        {
            $errorCode = json_last_error();
            $errorMessage = json_last_error_msg();
            throw new InternalError("JSON encoding failed for JWT header with error = '{$errorMessage}', error code = {$errorCode}.");
        }

        $payloadJson = json_encode($this->payload, JSON_PRETTY_PRINT);
        if ($payloadJson === false)
        {
            $errorCode = json_last_error();
            $errorMessage = json_last_error_msg();
            throw new InternalError("JSON encoding failed for JWT payload with error = '{$errorMessage}', error code = {$errorCode}.");
        }

        $header64 = Base64Url::encode($headerJson);
        $payload64 = Base64Url::encode($payloadJson);
        $data = $header64 . "." . $payload64;
        return $data . "." . Base64Url::encode(hash_hmac('sha256', $data, $this->secret, true));
    }
}

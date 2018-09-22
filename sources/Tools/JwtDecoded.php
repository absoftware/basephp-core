<?php
/**
 * @project BasePHP Core
 * @file JwtDecoded.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Tools;

use Base\Data\Json;

/**
 * Class JwtDecoded represents decoded JSON Web Token.
 *
 * TODO: Implement custom headers for JwtDecoded and parse algorithm type. Throw exception if algorithm is not supported.
 *
 * @package Base\Tools
 */
class JwtDecoded
{
    /**
     * Header.
     * @var Json
     */
    protected $header;

    /**
     * Payload.
     * @var Json
     */
    protected $payload;

    /**
     * Secret phrase for HS256 algorithm.
     * @var string
     */
    protected $secret;

    /**
     * JwtDecoded constructor.
     * @param Json $payload
     * @param string $secret
     */
    public function __construct(Json $payload, string $secret)
    {
        $this->payload = $payload;
        $this->secret = $secret;
        $this->header = Json::fromDictionary([
            "alg" => "HS256",
            "typ" => "JWT"
        ]);
    }

    /**
     * Returns JWT header.
     * @return array
     */
    public function header(): array
    {
        return $this->header->data();
    }

    /**
     * Returns payload.
     * @return array
     */
    public function payload(): array
    {
        return $this->payload->data();
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
        $header64 = Base64Url::encode($this->header->content());
        $payload64 = Base64Url::encode($this->payload->content());
        $data = $header64 . "." . $payload64;
        return $data . "." . Base64Url::encode(hash_hmac('sha256', $data, $this->secret, true));
    }
}

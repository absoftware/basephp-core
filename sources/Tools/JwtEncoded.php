<?php
/**
 * @project BasePHP Core
 * @file JwtEncoded.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Tools;

use Base\Data\Json;

/**
 * Class JwtEncoded represents encoded JSON Web Token.
 * @package Base\Tools
 */
class JwtEncoded
{
    /**
     * Encoded JSON Web Token as string.
     * @var string
     */
    protected $token;

    /**
     * Secret phrase for HS256 algorithm.
     * @var string
     */
    protected $secret;

    /**
     * Encoded header.
     * @var string
     */
    protected $header;

    /**
     * Encoded payload.
     * @var string
     */
    protected $payload;

    /**
     * Encoded signature.
     * @var string
     */
    protected $signature;

    /**
     * JwtEncoded constructor.
     * @param string $token
     * @param string $secret
     */
    public function __construct(string $token, string $secret)
    {
        $this->token = $token;
        $this->secret = $secret;
        $exploded = explode(".", $token);
        $count = count($exploded);
        $this->header = is_array($exploded) && $count > 0 ? $exploded[0] : "";
        $this->payload = is_array($exploded) && $count > 1 ? $exploded[1] : "";
        $this->signature = is_array($exploded) && $count > 2 ? $exploded[2] : "";
    }

    /**
     * Returns true if provided JWT is valid and signed properly.
     * @return bool
     * @throws \Base\Exceptions\ArgumentException
     */
    public function isValid(): bool
    {
        return $this->decode() != null;
    }

    /**
     * Returns only valid token.
     * @return JwtDecoded|null
     * @throws \Base\Exceptions\ArgumentException
     */
    public function decode(): ?JwtDecoded
    {
        // All fields must be set.
        if (!$this->header || !$this->payload || !$this->signature)
        {
            return null;
        }

        // Validate signature.
        $data = $this->header . "." . $this->payload;
        $validSignature = Base64Url::encode(hash_hmac('sha256', $data, $this->secret, true));
        if ($validSignature != $this->signature)
        {
            return null;
        }

        $decodedHeader = json_decode(Base64Url::decode($this->header), true);
        $decodedPayload = json_decode(Base64Url::decode($this->payload), true);
        return new JwtDecoded($decodedHeader, $decodedPayload, $this->secret);
    }

    /**
     * Returns encoded JWT as string.
     * @return string
     */
    public function __toString()
    {
        return $this->token;
    }
}

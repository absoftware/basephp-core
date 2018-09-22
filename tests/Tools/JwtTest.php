<?php
/**
 * @project BasePHP Core
 * @file JwtTest.php created by Ariel Bogdziewicz on 22/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Tests\Core;

use Base\Tools\Jwt;
use PHPUnit\Framework\TestCase;

/**
 * Class JwtTest
 * @package Tests\Core
 */
final class JwtTest extends TestCase
{
    /**
     * Valid JWT token.
     * @var string
     */
    protected $validToken = "ewogICAgImFsZyI6ICJIUzI1NiIsCiAgICAidHlwIjogIkpXVCIKfQ.ewogICAgIm5hbWUiOiAiSm9obiBEb2UiLAogICAgImFkbWluIjogdHJ1ZQp9.aDzg64cCiVYmbRq8Rkl8Ztxgta2vopyhiUbDiUFOZiw";

    /**
     * Invalid JWT token.
     * @var string
     */
    protected $invalidToken = "ewogICAgImFsZyI6ICJIUzI1NiIsCiAgICAidHlwIjogIkpXVCIKfQ.ewogICAgIm5hbWUiOiAiSm9obiBEb2UiLAogICAgImFkbWluIjogdHJ1ZQp9.aDzg64cCiVYmbRq8Rkl8ZtxgtFOZiw";

    /**
     * Test header for JWT.
     * @var array
     */
    protected $header = [
        'alg' => 'HS256',
        'typ' => 'JWT'
    ];

    /**
     * Test payload for JWT.
     * @var array
     */
    protected $payload = [
        "name" => "John Doe",
        "admin" => true
    ];

    /**
     * Test secret for signatures.
     * @var string
     */
    protected $secret = "1234567890";

    /**
     * Test of creation.
     */
    public function testJwt_create()
    {
        $jwt = new Jwt($this->header, $this->payload, $this->secret);
        $this->assertTrue((string)$jwt === $this->validToken);
        $this->assertTrue($jwt->isValid());
    }

    /**
     * Test of encoding.
     */
    public function testJwt_encode()
    {
        $token = Jwt::encode($this->header, $this->payload, $this->secret);
        $this->assertTrue($token == $this->validToken);
    }

    /**
     * Validation.
     */
    public function testJwt_decodeValid()
    {
        $jwt = Jwt::decode($this->validToken, $this->secret);
        $this->assertTrue($jwt->isValid());
    }

    /**
     * Validation.
     */
    public function testJwt_decodeInvalid()
    {
        $jwt = Jwt::decode($this->invalidToken, $this->secret);
        $this->assertTrue(!$jwt->isValid());
    }

    /**
     * Test of decoding.
     */
    public function testJwt_decodedData()
    {
        $jwt = Jwt::decode($this->validToken, $this->secret);
        $payload = $jwt->payload();
        $this->assertTrue(isset($payload["name"]) && $payload["name"] == "John Doe");
    }
}

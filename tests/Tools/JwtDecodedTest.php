<?php
/**
 * @project BasePHP Core
 * @file JwtDecodedTest.php created by Ariel Bogdziewicz on 22/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Tests\Core;

use Base\Tools\JwtDecoded;
use Base\Tools\JwtEncoded;
use PHPUnit\Framework\TestCase;

/**
 * Class JwtDecodedTest
 * @package Tests\Core
 */
final class JwtDecodedTest extends TestCase
{
    /**
     * Valid JWT token.
     * @var string
     */
    protected $validToken = "ewogICAgImFsZyI6ICJIUzI1NiIsCiAgICAidHlwIjogIkpXVCIKfQ.ewogICAgIm5hbWUiOiAiSm9obiBEb2UiLAogICAgImFkbWluIjogdHJ1ZQp9.aDzg64cCiVYmbRq8Rkl8Ztxgta2vopyhiUbDiUFOZiw";

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
    public function testJwtDecoded_create()
    {
        $jwtDecoded = new JwtDecoded($this->header, $this->payload, $this->secret);
        $this->assertTrue((string)$jwtDecoded == $this->validToken);
    }

    /**
     * Test of encoding.
     */
    public function testJwtDecoded_encode()
    {
        $jwtDecoded = new JwtDecoded($this->header, $this->payload, $this->secret);
        /** @var JwtEncoded $jwtEncoded */
        $jwtEncoded = $jwtDecoded->encode();
        $this->assertTrue($jwtEncoded->isValid() && (string)$jwtEncoded == (string)$jwtDecoded);
    }
}

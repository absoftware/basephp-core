<?php
/**
 * @project BasePHP Core
 * @file JwtEncodedTest.php created by Ariel Bogdziewicz on 22/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Tests\Core;

use Base\Tools\JwtDecoded;
use Base\Tools\JwtEncoded;
use PHPUnit\Framework\TestCase;

/**
 * Class JwtEncodedTest
 * @package Tests\Core
 */
final class JwtEncodedTest extends TestCase
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
     * Test secret for signatures.
     * @var string
     */
    protected $secret = "1234567890";

    /**
     * Test of creation.
     */
    public function testJwtEncoded_create()
    {
        $jwtEncoded = new JwtEncoded($this->validToken, $this->secret);
        $this->assertTrue($jwtEncoded->isValid());
    }

    /**
     * Test of decoding.
     */
    public function testJwtEncoded_decode()
    {
        $jwtEncoded = new JwtEncoded($this->validToken, $this->secret);
        /** @var JwtDecoded $jwtDecoded */
        $jwtDecoded = $jwtEncoded->decode();
        $payload = $jwtDecoded->payload();
        $this->assertTrue(isset($payload["name"]) && $payload["name"] == "John Doe");
    }

    /**
     * Test invalid token.
     */
    public function testJwtEncoded_invalid()
    {
        $jwtEncoded = new JwtEncoded($this->invalidToken, $this->secret);
        $this->assertTrue(!$jwtEncoded->isValid());
    }
}

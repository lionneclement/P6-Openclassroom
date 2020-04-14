<?php

namespace App\Tests\Tools;

use App\Tools\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function testLenght()
    {
        $token = new Token();
        $result = $token->generator(12);
        $this->assertEquals(12, strlen($result));
    }
    public function testLenghtError()
    {
        $token = new Token();
        $result = $token->generator(15);
        $this->assertNotEquals(14, strlen($result));
    }
    public function testWordGenerator()
    {
        $token = new Token();
        $result = $token->generator(15);
        $result1 = $token->generator(15);
        $this->assertNotEquals($result, $result1);
    }
}
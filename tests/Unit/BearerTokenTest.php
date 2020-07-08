<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\Api\BearerToken;
use Actengage\LaravelMessageGears\Tests\TestCase;
use GuzzleHttp\Psr7\Response;

class BearerTokenTest extends TestCase
{
    /**
     * Test that a BearerToken is not expired.
     * 
     * @return void
     */
    public function testBearerToken()
    {
        $token = new BearerToken('test', now()->addMinute());

        $this->assertTrue($token->isActive());
    }

    /**
     * Test that a BearerToken can expire.
     * 
     * @return void
     */
    public function testExpiredBearerToken()
    {
        $token = new BearerToken('test', now()->subMinute());

        $this->assertTrue($token->isExpired());
    }

    /**
     * Test creating BearerToken from a Guzzle response.
     * 
     * @return void
     */
    public function testBearerFromResponse()
    {
        $response = new Response(200, [], json_encode([
            'expiresAt' => now()->addMinute(),
            'token' => 'test'
        ]));

        $token = BearerToken::response($response);

        $this->assertTrue($token->isActive());
        $this->assertEquals('test', (string) $token);
    }
}
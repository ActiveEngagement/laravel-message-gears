<?php

namespace Tests\Unit;

use Actengage\MessageGears\Facades\Cloud;
use Carbon\Carbon;
use Tests\TestCase;

class CloudTest extends TestCase
{
    public function test_base_uri()
    {
        $this->assertEquals('https://api.messagegears.net/', Cloud::instance()->baseUri);
    }

    public function test_uri()
    {
        $this->assertEquals('/test', Cloud::uri('/test'));
        $this->assertEquals('v5.1/test', Cloud::uri('test'));
        $this->assertEquals('v5.1/a/1/b/2', Cloud::uri('a', 1, 'b', '2'));
        $this->assertEquals('v5.1/a/1/b/2', Cloud::uri(['a', 1, 'b', '2']));
        $this->assertEquals('v5/test', Cloud::uri('v5/test'));
        $this->assertEquals('/v5/test', Cloud::uri('/v5/test'));
        $this->assertEquals('v5.1/v5test', Cloud::uri('v5test'));
    }

    public function test_authentication()
    {
        Cloud::mock([
            $mock = $this->authenticate()
        ]);

        extract(json_decode($mock->getBody(), true));

        $bearerToken = Cloud::authenticate()->bearerToken;

        $this->assertEquals($token, $bearerToken->token);
        $this->assertEquals(Carbon::make($expirationDate), $bearerToken->expirationDate);
    }

    public function test_post_request()
    {
        Cloud::mock([
            $this->authenticate(),
            $this->ok()
        ]);
        
        $response = Cloud::post('provisioning/account/1');
        
        $this->assertEquals(200, $response->getStatusCode());
    }
}
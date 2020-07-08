<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\Api\Accelerator;
use Actengage\LaravelMessageGears\Api\BearerToken;
use Actengage\LaravelMessageGears\Api\Cloud;
use Actengage\LaravelMessageGears\Tests\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ServiceProviderTest extends TestCase
{
    public function testCloudApiInstance()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'token' => 'test',
                'expirationDate' => now()->addMinutes(15)
            ])),
            new Response(200, [], null),
            new Response(200, [], null),
            new Response(200, [], null),
            new Response(200, [], null),
            new Response(200, [], null),
            new Response(200, [], null)
        ]);

        app('mg.api.cloud')->client([
            'handler' => $mock
        ]);
        
        $this->assertInstanceOf(Cloud::class, app('mg.api.cloud'));
        $this->assertInstanceOf(Response::class, app('mg.api.cloud')->request('GET', 'test'));
        $this->assertTrue(app('mg.api.cloud')->isAuthenticated());
        $this->assertIsString(app('mg.api.cloud')->apiKey());
        $this->assertInstanceOf(Cloud::class, app('mg.api.cloud')->apiKey(1));
        $this->assertEquals(1, app('mg.api.cloud')->apiKey());
        $this->assertIsString(app('mg.api.cloud')->accountId());
        $this->assertInstanceOf(Cloud::class, app('mg.api.cloud')->accountId(1));
        $this->assertEquals(1, app('mg.api.cloud')->accountId());
        $this->assertIsArray(app('mg.api.cloud')->headers());
        $this->assertInstanceOf(Response::class, app('mg.api.cloud')->get('test'));
        $this->assertInstanceOf(Response::class, app('mg.api.cloud')->post('test'));
        $this->assertInstanceOf(Response::class, app('mg.api.cloud')->put('test'));
        $this->assertInstanceOf(Response::class, app('mg.api.cloud')->patch('test'));
        $this->assertInstanceOf(Response::class, app('mg.api.cloud')->delete('test'));
    }

    public function testAcceleratorApiInstance()
    {
        $this->assertInstanceOf(Accelerator::class, app('mg.api.accelerator'));
    }
}
<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\MessageGears;
use Actengage\LaravelMessageGears\Tests\TestCase;
use Actengage\LaravelMessageGears\Xml;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ServiceProviderTest extends TestCase
{
    /**
     * Check that the multiply method returns correct result
     * @return void
     */
    public function testServiceProviderConfig()
    {
        $this->assertInstanceOf(MessageGears::class, app('messagegears'));
        $this->assertEquals('API_KEY', app('messagegears')->apiKey());
        $this->assertEquals('ACCOUNT_ID', app('messagegears')->accountId());
        $this->assertEquals('CAMPAIGN_ID', app('messagegears')->campaignId());
    }

    public function testCanSendTransactionalCampaign()
    {
        $xml = new Xml('<TransactionalJobSubmitResponse/>');
        $xml->addChild('RequestId', 't23110-a9e77b7c-c980-4ff5-8ede-546fbe0bad66');
        $xml->addChild('Result', 'REQUEST_SUCCESSFUL');
        
        $mock = new MockHandler([
            new Response(200, [], $xml->toString())
        ]);

        app('messagegears')->mock($mock);
        
        $response = app('messagegears')->submitTransactionalCampaign([
            'recipient' => [
                'email' => 'test@test.com'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
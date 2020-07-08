<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\Messages\TransactionalCampaignSubmit;
use Actengage\LaravelMessageGears\Recipient;
use Actengage\LaravelMessageGears\Tests\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class TransactionalCampaignSubmitTest extends TestCase
{
    /**
     * Check that the multiply method returns correct result.
     * 
     * @return void
     */
    public function testMessageCanBeCreated()
    {
        $message = new TransactionalCampaignSubmit([
            'accountId' => 1,
            'apiKey' => 123,
            'context' => [ 
                'a' => 1
            ]
        ]);

        $message->campaignVersion(1);

        $this->assertEquals(1, $message->campaignVersion);

        $this->assertEquals([
            'a' => 1
        ], $message->context()->toArray());

        $this->assertEquals(1, $message->accountId);
        $this->assertEquals(123, $message->apiKey);

        $message->context([
            'b' => 2,
            'c' => 3
        ]);
        
        $message->context([
            'd' => 4
        ]);

        $this->assertEquals([
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4
        ], $message->context->toArray());

        $message->recipient([
            'email' => 'test@test.com',
            'first_name' => 'test',
            'last_name' => 'test'
        ]);

        $this->assertInstanceOf(Recipient::class, $message->recipient);
    }

    /**
     * Test that a TransactionalCampaignSubmit can be sent.
     * 
     * @return void
     */
    public function testCanSendTransactionalCampaign()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode($body = [
                'requestId' => '02-b18123-12fe6438a10daaa7ce44a8fff4e894a'
            ]))
        ]);

        app('mg.api.cloud')->client([
            'handler' => $mock
        ]);
        
        $message = new TransactionalCampaignSubmit([
            'recipient' => [
                'email' => 'test@test.com'
            ],
            'context' => [
                'test' => 123
            ]
        ]);

        $xml = '<?xml version="1.0"?><Recipient><EmailAddress>test@test.com</EmailAddress></Recipient>';

        $this->assertEquals($xml, str_replace("\n", '', $message->toArray()['recipient']));

        $context = '<?xml version="1.0"?><ContextData><test>123</test></ContextData>';

        $this->assertEquals($context, str_replace("\n", '', $message->toArray()['context']));

        $response = $message->send();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($body, json_decode($response->getBody(), true));
    }

}
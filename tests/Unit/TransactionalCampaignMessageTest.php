<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\Recipient;
use Actengage\LaravelMessageGears\Tests\SendTransactionalCampaign;
use Actengage\LaravelMessageGears\Tests\TestCase;
use Actengage\LaravelMessageGears\Tests\User;
use Actengage\LaravelMessageGears\TransactionalCampaignMessage;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Notification;

class TransactionalCampaignMessageTest extends TestCase
{
    /**
     * Check that the multiply method returns correct result
     * @return void
     */
    public function testMessageCanBeCreated()
    {
        $message = new TransactionalCampaignMessage([
            'accountId' => 1,
            'apiKey' => 123
        ]);

        $this->assertEquals(1, $message->accountId);
        $this->assertEquals(123, $message->apiKey);

        $message->attachment(1, 2, 3, 4);

        $this->assertEquals([
            [
                'AttachmentUrl' => 1,
                'AttachmentContent' => 2,
                'AttachmentName' => 3,
                'AttachmentContentType' => 4
            ]
        ], $message->attachments);

        $message->context([
            'a' => 1,
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

    public function testMessageCanBeCastAsRequest()
    {
        $message = new TransactionalCampaignMessage([
            'accountId' => 'ACCOUNT_ID',
            'apiKey' => 'API_KEY',
            'campaignId' => 'CAMPAIGN_ID',
            'recipient' => [
                'email' => 'test@test.com'
            ],
            'attachments' => [
                [1, 2, 3, 4],
                [1, 2, 3, 4]
            ]
        ]);
        
        $request = $message->toRequest();
    
        $this->assertInstanceOf(Request::class, $request);
        
        $query = 'Action=TransactionalCampaignSubmit&CampaignId=CAMPAIGN_ID&ApiKey=API_KEY&AccountId=ACCOUNT_ID&RecipientXml=%3CRecipient%3E%3CEmailAddress%3Etest%40test.com%3C%2FEmailAddress%3E%3C%2FRecipient%3E&AttachmentUrl.1=1&AttachmentContent.1=2&AttachmentName.1=3&AttachmentContentType.1=4&AttachmentUrl.2=1&AttachmentContent.2=2&AttachmentName.2=3&AttachmentContentType.2=4';

        $this->assertEquals($query, $request->getUri()->getQuery());
    }

    public function testContextIsBeingPassed()
    {
        $message = new TransactionalCampaignMessage([
            'context' => [
                'a' => [
                    'a' => 1
                ],
                'a.b' => 2
            ]
        ]);
        
        $this->assertEquals([
            'a' => [
                'a' => 1,
                'b' => 2
            ]
        ], $message->context()->toArray());
    }
}
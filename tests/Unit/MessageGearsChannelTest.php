<?php

namespace Tests\Unit;

use Actengage\MessageGears\Api\BearerToken;
use Actengage\MessageGears\Exceptions\InvalidTransactionalCampaignSubmit;
use Actengage\MessageGears\Exceptions\MissingRecipient;
use Actengage\MessageGears\MessageGearsChannel;
use Actengage\MessageGears\Notifications\SendTransactionalCampaign;
use Actengage\MessageGears\Notifications\Test;
use Actengage\MessageGears\Notifications\TransactionalEmail;
use Actengage\MessageGears\Tests\FailedNotification;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Tests\User;

class MessageGearsChannelTest extends TestCase
{
    /**
     * Test that the message can be sent.
     * 
     * @return void
     */
    public function testMessageCanBeSent()
    {
        Notification::fake();

        $campaignId = config('services.messagegears.campaign_id');

        $notification = TransactionalEmail::make()
            ->campaignId($campaignId)
            ->context([
                'SubjectLine' => 'test',
                'HtmlContent' => 'Hello!',
                'TextContent' => 'Hellp!'
            ]);

        $user = new User();
        $user->name = 'test';
        $user->password = Hash::make('test');
        $user->email = 'jkimbrell@actengage.com';
        $user->save();
        $user->notify($notification);


        Notification::assertSentTo($user, TransactionalEmail::class);
    }

    // /**
    //  * Test that the message can be sent.
    //  * 
    //  * @return void
    //  */
    // public function testMessageFailsWithoutMessageInterface()
    // {
    //     $this->expectException(InvalidTransactionalCampaignSubmit::class);

    //     $channel = new MessageGearsChannel;
    //     $channel->send(new User, new FailedNotification);
    // }

    // /**
    //  * Check that the message can be sent.
    //  * 
    //  * @return void
    //  */
    // public function testMessageSend()
    // {
    //     $mock = new MockHandler([
    //         new Response(200, [], json_encode([
    //             'token' => 'test',
    //             'expirationDate' => now()->addMinutes(15)
    //         ])),
    //         new Response(200, [], 'test')
    //     ]);

    //     app('mg.api.cloud')->client([
    //         'handler' => $mock
    //     ]);
        
    //     $user = new User();
    //     $user->id = 1;
    //     $user->email = 'test@test.com';

    //     $notification = new SendTransactionalCampaign;

    //     $channel = new MessageGearsChannel;
    //     $channel->send($user, $notification);

    //     $request = $mock->getLastRequest();

    //     $this->assertEquals('https://api.messagegears.net/v5/campaign/transactional/CAMPAIGN_ID', (string) $request->getUri());
    // }

    // /**
    //  * Check that the message cannot be sent without a recipient.
    //  * 
    //  * @return void
    //  */
    // public function testSendingMessageToMissingRecipient()
    // {
    //     $this->expectException(MissingRecipient::class);

    //     $mock = new MockHandler([
    //         new Response(200, [], json_encode([
    //             'token' => 'test',
    //             'expirationDate' => now()->addMinutes(15)
    //         ])),
    //         new Response(200, [], 'test')
    //     ]);

    //     app('mg.api.cloud')->client([
    //         'handler' => $mock
    //     ]);

    //     $notification = new SendTransactionalCampaign;

    //     $channel = new MessageGearsChannel;
    //     $channel->send(new User(), $notification);
    // }

    // /**
    //  * Test that an expired auth token is refreshed before the request is made.
    //  * 
    //  * @return void
    //  */
    // public function testAuthBearerWithRefresh()
    // {
    //     $mock = new MockHandler([
    //         new Response(200, [], json_encode([
    //             'token' => '2',
    //             'expirationDate' => now()->addMinute()
    //         ])),
    //         new Response(200, [], 'Success!'),
    //     ]);

    //     app('mg.api.cloud')->client([
    //         'handler' => $mock
    //     ]);
        
    //     // Create an expired token to simulate a post request.
    //     app('mg.api.cloud')->bearerToken = new BearerToken('1', now()->subMinute());

    //     $response = app('mg.api.cloud')->post('test');

    //     $this->assertEquals('2', app('mg.api.cloud')->bearerToken);
    //     $this->assertEquals('Success!', (string) $response->getBody());
    // }
}
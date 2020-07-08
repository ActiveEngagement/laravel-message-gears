<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\MessageGearsChannel;
use Actengage\LaravelMessageGears\Notifications\SendTransactionalCampaign;
use Actengage\LaravelMessageGears\Tests\TestCase;
use Actengage\LaravelMessageGears\Tests\User;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Notification;

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

        $user = new User();
        $user->id = 1;
        $user->email = 'test@test.com';
        $user->notify(new SendTransactionalCampaign());

        Notification::assertSentTo($user, SendTransactionalCampaign::class);
    }

    /**
     * Check that the message can be sent.
     * 
     * @return void
     */
    public function testMessageSend()
    {
        $mock = new MockHandler([
            new Response(200, [], 'test')
        ]);

        app('mg.api.cloud')->client([
            'handler' => $mock
        ]);

        $user = new User();
        $user->id = 1;
        $user->email = 'test@test.com';

        $notification = new SendTransactionalCampaign;

        $channel = new MessageGearsChannel;
        $channel->send($user, $notification);

        $request = $mock->getLastRequest();

        $this->assertEquals('https://api.messagegears.net/campaign/transaction/CAMPAIGN_ID', (string) $request->getUri());
    }
}
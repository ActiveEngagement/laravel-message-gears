<?php

namespace Tests\Unit;

use Actengage\MessageGears\Api\BearerToken;
use Actengage\MessageGears\Exceptions\InvalidTransactionalCampaignSubmit;
use Actengage\MessageGears\Exceptions\MissingRecipient;
use Actengage\MessageGears\Facades\Cloud;
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
}
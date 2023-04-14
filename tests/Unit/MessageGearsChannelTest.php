<?php

namespace Tests\Unit;

use Actengage\MessageGears\Notifications\Test;
use Actengage\MessageGears\Notifications\TransactionalEmail;
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
                'TextContent' => 'Hellp!',
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

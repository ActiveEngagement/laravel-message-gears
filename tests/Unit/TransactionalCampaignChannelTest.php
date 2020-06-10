<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\SendTransactionalCampaign;
use Actengage\LaravelMessageGears\Tests\TestCase;
use Actengage\LaravelMessageGears\Tests\User;
use Illuminate\Support\Facades\Notification;

class TransactionalCampaignChannelTest extends TestCase
{
    /**
     * Check that the multiply method returns correct result
     * @return void
     */
    public function testMessageCanBeSent()
    {
        Notification::fake();

        $user = new User();
        $user->id = 1;
        $user->email = 'test@test.com';
        $user->notify(new SendTransactionalCampaign([
            'email' => 'overridden@test.com'
        ]));

        Notification::assertSentTo($user, SendTransactionalCampaign::class);
    }
}
<?php

declare(strict_types=1);

use Actengage\MessageGears\Facades\Cloud as CloudFacade;
use Actengage\MessageGears\MessageGearsChannel;
use Actengage\MessageGears\Notifications\TransactionalEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\User;

it('can send a notification via the message gears channel', function (): void {
    Notification::fake();

    $campaignId = config('services.messagegears.campaign_id');

    $notification = TransactionalEmail::make()
        ->campaignId($campaignId)
        ->context([
            'SubjectLine' => 'test',
            'HtmlContent' => 'Hello!',
            'TextContent' => 'Hello!',
        ]);

    $user = new User;
    $user->name = 'test';
    $user->password = Hash::make('test');
    $user->email = 'test@example.com';
    $user->save();
    $user->notify($notification);

    Notification::assertSentTo($user, TransactionalEmail::class);
});

it('sends notification through the channel directly', function (): void {
    CloudFacade::mock([
        authenticate(),
        ok(),
    ]);

    $channel = new MessageGearsChannel;

    $notification = TransactionalEmail::make()
        ->campaignId('test-campaign')
        ->context(['SubjectLine' => 'Direct test']);

    $user = new User;
    $user->name = 'test';
    $user->password = Hash::make('test');
    $user->email = 'test@example.com';
    $user->save();

    $channel->send($user, $notification);

    // If we reach here without exception, the channel sent successfully
    expect(true)->toBeTrue();
});

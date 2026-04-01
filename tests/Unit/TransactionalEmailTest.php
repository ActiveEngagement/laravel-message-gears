<?php

declare(strict_types=1);

use Actengage\MessageGears\Context;
use Actengage\MessageGears\Facades\Cloud as CloudFacade;
use Actengage\MessageGears\MessageGearsChannel;
use Actengage\MessageGears\Notifications\TransactionalEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Tests\User;

it('can be created with make', function (): void {
    $notification = TransactionalEmail::make();

    expect($notification)->toBeInstanceOf(TransactionalEmail::class);
});

it('sets account id from cloud instance', function (): void {
    $notification = TransactionalEmail::make();

    expect($notification->accountId)->toBe(CloudFacade::instance()->accountId);
});

it('initializes with empty context', function (): void {
    $notification = TransactionalEmail::make();

    expect($notification->context)->toBeInstanceOf(Context::class);
    expect($notification->context->toArray())->toBe([]);
});

it('can set campaign id', function (): void {
    $notification = TransactionalEmail::make()->campaignId('camp-123');

    expect($notification->campaignId)->toBe('camp-123');
});

it('can set campaign version', function (): void {
    $notification = TransactionalEmail::make()
        ->campaignId('camp-123')
        ->campaignVersion('v2');

    expect($notification->campaignVersion)->toBe('v2');
});

it('can set context from array', function (): void {
    $notification = TransactionalEmail::make()
        ->context(['SubjectLine' => 'Test']);

    expect($notification->context->toArray())->toBe(['SubjectLine' => 'Test']);
});

it('can set context from Context object', function (): void {
    $context = new Context(['SubjectLine' => 'Test']);
    $notification = TransactionalEmail::make()->context($context);

    expect($notification->context)->toBe($context);
});

it('can set category', function (): void {
    $notification = TransactionalEmail::make()->category('marketing');

    expect($notification->category)->toBe('marketing');
});

it('can set correlation id', function (): void {
    $notification = TransactionalEmail::make()->correlationId('corr-123');

    expect($notification->correlationId)->toBe('corr-123');
});

it('can set latest send time from carbon', function (): void {
    $time = Date::parse('2030-01-01 12:00:00');
    $notification = TransactionalEmail::make()->latestSendTime($time);

    expect($notification->latestSendTime->eq($time))->toBeTrue();
});

it('can set latest send time from string', function (): void {
    $notification = TransactionalEmail::make()->latestSendTime('2030-01-01 12:00:00');

    expect($notification->latestSendTime)->toBeInstanceOf(Carbon::class);
});

it('can set notification email address', function (): void {
    $notification = TransactionalEmail::make()
        ->notificationEmailAddress('notify@example.com');

    expect($notification->notificationEmailAddress)->toBe('notify@example.com');
});

it('can set sender details', function (): void {
    $notification = TransactionalEmail::make()
        ->fromAddress('from@example.com')
        ->fromName('Test Sender')
        ->replyToAddress('reply@example.com');

    expect($notification->fromAddress)->toBe('from@example.com');
    expect($notification->fromName)->toBe('Test Sender');
    expect($notification->replyToAddress)->toBe('reply@example.com');
});

it('routes via message gears channel', function (): void {
    $notification = TransactionalEmail::make();
    $user = new User;

    expect($notification->via($user))->toBe(MessageGearsChannel::class);
});

it('builds the correct uri', function (): void {
    $notification = TransactionalEmail::make()->campaignId('camp-123');

    expect($notification->uri())->toBe('v5.1/campaign/transactional/camp-123');
});

it('throws when sending without campaign id', function (): void {
    $notification = TransactionalEmail::make();

    $user = new User;
    $user->name = 'test';
    $user->password = Hash::make('test');
    $user->email = 'test@example.com';
    $user->save();

    $notification->send($user);
})->throws(InvalidArgumentException::class, 'The campaign ID is required to send transactional emails.');

it('sends the notification via cloud api', function (): void {
    CloudFacade::mock([
        authenticate(),
        ok(),
    ]);

    $notification = TransactionalEmail::make()
        ->campaignId('camp-123')
        ->context(['SubjectLine' => 'Hello']);

    $user = new User;
    $user->name = 'test';
    $user->password = Hash::make('test');
    $user->email = 'test@example.com';
    $user->save();

    $notification->send($user);

    // If we reach here without exception, the request was sent successfully
    expect(true)->toBeTrue();
});

it('gets recipient from notifiable', function (): void {
    $notification = TransactionalEmail::make()->campaignId('camp-123');

    $user = new User;
    $user->email = 'test@example.com';

    $recipient = $notification->recipient($user);

    expect($recipient->toArray())->toBe([
        'EmailAddress' => 'test@example.com',
    ]);
});

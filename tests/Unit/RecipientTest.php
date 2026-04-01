<?php

declare(strict_types=1);

use Actengage\MessageGears\Recipient;

it('can set email fluently', function (): void {
    $recipient = new Recipient;
    $result = $recipient->email('test@example.com');

    expect($result)->toBe($recipient);
    expect($recipient->email)->toBe('test@example.com');
});

it('can set recipient id fluently', function (): void {
    $recipient = new Recipient;
    $result = $recipient->recipientId('12345');

    expect($result)->toBe($recipient);
    expect($recipient->recipientId)->toBe('12345');
});

it('converts to array with email', function (): void {
    $recipient = (new Recipient)->email('test@example.com');

    expect($recipient->toArray())->toBe([
        'EmailAddress' => 'test@example.com',
    ]);
});

it('converts to array with email and recipient id', function (): void {
    $recipient = (new Recipient)
        ->email('test@example.com')
        ->recipientId('12345');

    expect($recipient->toArray())->toBe([
        'EmailAddress' => 'test@example.com',
        'RecipientId' => '12345',
    ]);
});

it('filters null values in toArray', function (): void {
    $recipient = new Recipient;

    expect($recipient->toArray())->toBe([]);
});

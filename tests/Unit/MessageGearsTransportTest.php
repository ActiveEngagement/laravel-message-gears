<?php

declare(strict_types=1);

use Actengage\MessageGears\Cloud;
use Actengage\MessageGears\MessageGearsTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

it('casts to string as mg', function (): void {
    $cloud = new Cloud;
    $transport = new MessageGearsTransport($cloud, 'campaign-123');

    expect((string) $transport)->toBe('mg');
});

it('sends an email via the messagegears api', function (): void {
    $cloud = new Cloud('account-123', 'api-key');
    $cloud->mock([
        authenticate(),
        ok(),
    ]);

    $transport = new MessageGearsTransport($cloud, 'campaign-456');

    $email = (new Email)
        ->from(new Address('sender@example.com', 'Test Sender'))
        ->to(new Address('recipient@example.com'))
        ->subject('Test Subject')
        ->text('Test text body')
        ->html('<p>Test HTML body</p>');

    $transport->send($email);

    expect(true)->toBeTrue();
});

it('sends an email with json body merge', function (): void {
    $cloud = new Cloud('account-123', 'api-key');
    $cloud->mock([
        authenticate(),
        ok(),
    ]);

    $transport = new MessageGearsTransport($cloud, 'campaign-789', [
        'custom' => 'data',
    ]);

    $email = (new Email)
        ->from(new Address('sender@example.com', 'Sender'))
        ->to(new Address('recipient@example.com'))
        ->subject('Merged')
        ->text('Body');

    $transport->send($email);

    expect(true)->toBeTrue();
});

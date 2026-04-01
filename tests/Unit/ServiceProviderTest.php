<?php

declare(strict_types=1);

use Actengage\MessageGears\Accelerator;
use Actengage\MessageGears\Cloud;
use Actengage\MessageGears\MessageGearsTransport;
use Illuminate\Support\Facades\Mail;

it('registers cloud api as singleton', function (): void {
    $cloud = resolve(Cloud::class);

    expect($cloud)->toBeInstanceOf(Cloud::class);
    expect(resolve(Cloud::class))->toBe($cloud);
});

it('registers cloud api alias', function (): void {
    expect(resolve('mg.api.cloud'))->toBeInstanceOf(Cloud::class);
});

it('registers accelerator api as singleton', function (): void {
    $accelerator = resolve(Accelerator::class);

    expect($accelerator)->toBeInstanceOf(Accelerator::class);
    expect(resolve(Accelerator::class))->toBe($accelerator);
});

it('registers accelerator api alias', function (): void {
    expect(resolve('mg.api.accelerator'))->toBeInstanceOf(Accelerator::class);
});

it('registers messagegears transport singleton binding', function (): void {
    expect(app()->bound(MessageGearsTransport::class))->toBeTrue();
});

it('resolves messagegears transport with correct campaign id', function (): void {
    config(['services.messagegears.campaign_id' => 'test-campaign-id']);

    app()->forgetInstance(MessageGearsTransport::class);

    $transport = resolve(MessageGearsTransport::class);

    expect($transport)->toBeInstanceOf(MessageGearsTransport::class);
    expect((string) $transport)->toBe('mg');
});

it('extends the mail manager with messagegears driver', function (): void {
    config([
        'mail.mailers.messagegears' => [
            'transport' => 'messagegears',
            'campaign_id' => 'mail-campaign-id',
        ],
    ]);

    $transport = Mail::mailer('messagegears')->getSymfonyTransport();

    expect($transport)->toBeInstanceOf(MessageGearsTransport::class);
    expect((string) $transport)->toBe('mg');
});

it('supports custom resolver in mail config', function (): void {
    // Create an anonymous invokable class for resolution
    $resolverClass = (new class
    {
        public function __invoke(mixed $app, array $config): MessageGearsTransport
        {
            return new MessageGearsTransport(
                new Cloud,
                (string) ($config['campaign_id'] ?? 'resolved')
            );
        }
    })::class;

    config([
        'mail.mailers.messagegears' => [
            'transport' => 'messagegears',
            'resolver' => $resolverClass,
            'campaign_id' => 'custom-campaign',
        ],
    ]);

    $transport = Mail::mailer('messagegears')->getSymfonyTransport();

    expect($transport)->toBeInstanceOf(MessageGearsTransport::class);
});

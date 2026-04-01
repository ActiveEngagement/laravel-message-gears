<?php

declare(strict_types=1);

use Actengage\MessageGears\Accelerator;
use Actengage\MessageGears\Facades\Accelerator as AcceleratorFacade;

it('has the correct default base uri', function (): void {
    expect(AcceleratorFacade::instance()->baseUri)
        ->toBe('http://gears.listelixr.net:8080/');
});

it('has the correct version constant', function (): void {
    expect(Accelerator::VERSION)->toBe('beta');
});

it('has the correct version pattern', function (): void {
    expect(Accelerator::VERSION_PATTERN)->toBe('/beta\//');
});

it('builds uris correctly', function (string $input, string $expected): void {
    expect(AcceleratorFacade::uri($input))->toBe($expected);
})->with([
    'absolute path is not prepended' => ['/test', '/test'],
    'version path is not duplicated' => ['beta/test', 'beta/test'],
    'absolute version path stays absolute' => ['/beta/test', '/beta/test'],
    'non-version path is prepended' => ['betatest', 'beta/betatest'],
]);

it('can send a post request', function (): void {
    AcceleratorFacade::mock([ok()]);

    $response = AcceleratorFacade::post('test');

    expect($response->getStatusCode())->toBe(200);
});

it('creates an http client with correct headers', function (): void {
    $accelerator = new Accelerator('account-123', 'key-456');
    $client = $accelerator->createHttpClient();

    $config = $client->getConfig();

    expect($config['base_uri']->__toString())->toBe('http://gears.listelixr.net:8080/');
    expect($config['headers']['Accept'])->toBe('application/json');
    expect($config['headers']['ApiKey'])->toBe('key-456');
    expect($config['headers']['CustomerId'])->toBe('account-123');
});

it('can be constructed with credentials', function (): void {
    $accelerator = new Accelerator('my-account', 'my-key');

    expect($accelerator->accountId)->toBe('my-account');
    expect($accelerator->apiKey)->toBe('my-key');
});

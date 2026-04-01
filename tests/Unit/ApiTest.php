<?php

declare(strict_types=1);

use Actengage\MessageGears\Accelerator;
use Actengage\MessageGears\Facades\Accelerator as AcceleratorFacade;
use GuzzleHttp\Client;

it('can set account id fluently', function (): void {
    $api = new Accelerator;
    $result = $api->accountId('test-account');

    expect($result)->toBe($api);
    expect($api->accountId)->toBe('test-account');
});

it('can set api key fluently', function (): void {
    $api = new Accelerator;
    $result = $api->apiKey('test-key');

    expect($result)->toBe($api);
    expect($api->apiKey)->toBe('test-key');
});

it('can set base uri fluently', function (): void {
    $api = new Accelerator;
    $result = $api->baseUri('https://example.com');

    expect($result)->toBe($api);
    expect($api->baseUri)->toBe('https://example.com');
});

it('can set headers fluently', function (): void {
    $api = new Accelerator;
    $result = $api->headers(['X-Custom' => 'value']);

    expect($result)->toBe($api);
    expect($api->headers)->toBe(['X-Custom' => 'value']);
});

it('can set a single header fluently', function (): void {
    $api = new Accelerator;
    $api->header('X-First', 'one');
    $api->header('X-Second', 'two');

    expect($api->headers)->toBe([
        'X-First' => 'one',
        'X-Second' => 'two',
    ]);
});

it('can configure from array', function (): void {
    $api = new Accelerator;
    $api->configure([
        'accountId' => 'configured-account',
        'apiKey' => 'configured-key',
        'baseUri' => 'https://configured.example.com',
    ]);

    expect($api->accountId)->toBe('configured-account');
    expect($api->apiKey)->toBe('configured-key');
    expect($api->baseUri)->toBe('https://configured.example.com');
});

it('can set a custom guzzle client', function (): void {
    $api = new Accelerator;
    $client = new Client;
    $result = $api->client($client);

    expect($result)->toBe($api);
    expect($api->client)->toBe($client);
});

it('returns self from instance method', function (): void {
    expect(AcceleratorFacade::instance())
        ->toBeInstanceOf(Accelerator::class);
});

it('can mock responses', function (): void {
    $api = new Accelerator('account', 'key');
    $result = $api->mock([ok()]);

    expect($result)->toBe($api);
    expect($api->client)->toBeInstanceOf(Client::class);
});

it('delegates method calls to request via __call', function (): void {
    AcceleratorFacade::mock([ok()]);

    // Use delete() which is not explicitly defined, triggering __call
    $response = AcceleratorFacade::delete('test');

    expect($response->getStatusCode())->toBe(200);
});

it('can send get requests via explicit method', function (): void {
    AcceleratorFacade::mock([ok()]);

    $response = AcceleratorFacade::get('test');

    expect($response->getStatusCode())->toBe(200);
});

it('can send post requests via explicit method', function (): void {
    AcceleratorFacade::mock([ok()]);

    $response = AcceleratorFacade::post('test');

    expect($response->getStatusCode())->toBe(200);
});

it('uses custom client when set', function (): void {
    $api = new Accelerator('account', 'key');
    $api->mock([ok()]);

    $response = $api->request('get', 'test');

    expect($response->getStatusCode())->toBe(200);
});

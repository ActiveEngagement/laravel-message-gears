<?php

declare(strict_types=1);

use Actengage\MessageGears\BearerToken;
use Actengage\MessageGears\Cloud;
use Actengage\MessageGears\Facades\Cloud as CloudFacade;
use Illuminate\Support\Facades\Date;

it('has the correct default base uri', function (): void {
    expect(CloudFacade::instance()->baseUri)
        ->toBe('https://api.messagegears.net/v5');
});

it('has the correct version constant', function (): void {
    expect(Cloud::VERSION)->toBe('v5.1');
});

it('has the correct version pattern', function (): void {
    expect(Cloud::VERSION_PATTERN)->toBe('/v\d(\.\d)?\//');
});

it('builds uris correctly', function (string $input, string $expected, array $args = []): void {
    expect(CloudFacade::uri($input, ...$args))->toBe($expected);
})->with([
    'absolute path is not prepended' => ['/test', '/test'],
    'path without version is prepended' => ['test', 'v5.1/test'],
    'sprintf formatting works' => ['a/%d/b/%s', 'v5.1/a/1/b/2', [1, '2']],
    'v5 path is not duplicated' => ['v5/test', 'v5/test'],
    'absolute v5 path stays absolute' => ['/v5/test', '/v5/test'],
    'v5-prefixed word is prepended' => ['v5test', 'v5.1/v5test'],
]);

it('authenticates and stores bearer token', function (): void {
    $mock = authenticate();
    CloudFacade::mock([$mock]);

    $decoded = json_decode((string) $mock->getBody(), true);

    $bearerToken = CloudFacade::authenticate()->bearerToken;

    expect($bearerToken)->toBeInstanceOf(BearerToken::class);
    expect($bearerToken->token)->toBe($decoded['token']);
    expect($bearerToken->expirationDate->eq(Date::make($decoded['expirationDate'])->subSeconds(30)))->toBeTrue();
});

it('does not re-authenticate when token is active', function (): void {
    $mock = authenticate();
    CloudFacade::mock([$mock]);

    $cloud = CloudFacade::authenticate();
    $firstToken = $cloud->bearerToken;

    // Second call should not trigger another HTTP request (would fail if it did since mock is exhausted)
    $cloud = CloudFacade::authenticate();

    expect($cloud->bearerToken)->toBe($firstToken);
});

it('can send authenticated post requests', function (): void {
    CloudFacade::mock([
        authenticate(),
        ok(),
    ]);

    $response = CloudFacade::post('provisioning/account/1');

    expect($response->getStatusCode())->toBe(200);
});

it('creates an http client with correct config', function (): void {
    $cloud = new Cloud('account-123', 'key-456');
    $client = $cloud->createHttpClient();

    $config = $client->getConfig();

    expect($config['base_uri']->__toString())->toBe('https://api.messagegears.net/v5');
    expect($config['headers']['Accept'])->toBe('application/json');
});

it('reports authenticated state correctly', function (): void {
    $cloud = new Cloud;

    expect($cloud->isAuthenticated())->toBeFalse();

    $cloud->bearerToken(new BearerToken(
        md5('token'),
        Date::now()->addHour()
    ));

    expect($cloud->isAuthenticated())->toBeTrue();
});

it('reports not authenticated when token is expired', function (): void {
    $cloud = new Cloud;

    $cloud->bearerToken(new BearerToken(
        md5('token'),
        Date::now()->subHour()
    ));

    expect($cloud->isAuthenticated())->toBeFalse();
});

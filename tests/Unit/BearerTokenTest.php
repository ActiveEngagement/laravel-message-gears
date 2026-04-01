<?php

declare(strict_types=1);

use Actengage\MessageGears\BearerToken;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Date;

it('can be created with token string and expiration', function (): void {
    $token = new BearerToken('my-token', Date::now()->addHour());

    expect($token->token)->toBe('my-token');
    expect($token->isActive())->toBeTrue();
    expect($token->isExpired())->toBeFalse();
});

it('subtracts 30 seconds from expiration date', function (): void {
    $expiration = Date::parse('2030-01-01 12:00:00', 'utc');
    $token = new BearerToken('my-token', $expiration);

    expect($token->expirationDate->eq(
        Date::parse('2030-01-01 12:00:00', 'utc')->subSeconds(30)
    ))->toBeTrue();
});

it('can be created from a guzzle response', function (): void {
    $body = json_encode([
        'token' => 'response-token',
        'expirationDate' => Date::now()->addHour()->toIso8601String(),
    ]);

    $response = new Response(200, [], $body);
    $token = BearerToken::response($response);

    expect($token->token)->toBe('response-token');
    expect($token->isActive())->toBeTrue();
});

it('can be created from response via constructor', function (): void {
    $body = json_encode([
        'token' => 'constructor-token',
        'expirationDate' => Date::now()->addHour()->toIso8601String(),
    ]);

    $response = new Response(200, [], $body);
    $token = new BearerToken($response);

    expect($token->token)->toBe('constructor-token');
    expect($token->isActive())->toBeTrue();
});

it('detects expired tokens', function (): void {
    $token = new BearerToken('expired', Date::now()->subHour());

    expect($token->isExpired())->toBeTrue();
    expect($token->isActive())->toBeFalse();
});

it('casts to string', function (): void {
    $token = new BearerToken('string-token', Date::now()->addHour());

    expect((string) $token)->toBe('string-token');
});

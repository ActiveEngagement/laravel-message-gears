<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

uses(TestCase::class)->in('Unit', 'Feature');

function authenticate(): Response
{
    return ok([
        'token' => md5('test-token'),
        'expirationDate' => now()->addHour()->toIso8601String(),
    ]);
}

function ok(array $body = []): Response
{
    return mockResponse(200, $body);
}

function mockResponse(int $status = 200, array $body = []): Response
{
    return new Response($status, [], json_encode($body));
}

<?php

declare(strict_types=1);

namespace Actengage\MessageGears;

use Actengage\MessageGears\Concerns\HasApiCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

abstract class Api
{
    use HasApiCredentials;

    /**
     * The default API version.
     */
    public const ?string VERSION = null;

    /**
     * The version pattern.
     */
    public const string|false VERSION_PATTERN = false;

    /**
     * The Guzzle client.
     */
    public ?Client $client = null;

    /**
     * Create a new instance.
     */
    public function __construct(?string $accountId = null, ?string $apiKey = null)
    {
        $this->accountId($accountId);
        $this->apiKey($apiKey);
    }

    /**
     * Call the method on the Guzzle client.
     *
     * @param  array<int, mixed>  $args
     */
    public function __call(string $method, array $args): Response
    {
        /** @var array{0: array<int, string>|string, 1?: array<string, mixed>} $args */
        return $this->request($method, ...$args);
    }

    /**
     * Send a POST request.
     *
     * @param  array<int, string>|string  $uri
     * @param  array<string, mixed>  $options
     */
    public function post(array|string $uri, array $options = []): Response
    {
        return $this->request('post', $uri, $options);
    }

    /**
     * Send a GET request.
     *
     * @param  array<int, string>|string  $uri
     * @param  array<string, mixed>  $options
     */
    public function get(array|string $uri, array $options = []): Response
    {
        return $this->request('get', $uri, $options);
    }

    /**
     * Set the `client` property.
     */
    public function client(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Create a new HTTP client.
     */
    abstract public function createHttpClient(): Client;

    /**
     * Get this instance.
     */
    public function instance(): self
    {
        return $this;
    }

    /**
     * Mock a Guzzle client.
     *
     * @param  array<int, mixed>  $requests
     */
    public function mock(array $requests): self
    {
        return $this->client(
            new Client([
                'handler' => HandlerStack::create(
                    new MockHandler($requests)
                ),
            ])
        );
    }

    /**
     * Send an HTTP request.
     *
     * @param  array<int, string>|string  $uri
     * @param  array<string, mixed>  $options
     */
    public function request(string $method, array|string $uri, array $options = []): Response
    {
        $client = $this->client ?? $this->createHttpClient();

        /** @var Response */
        return $client->$method($this->uri($uri), array_merge_recursive($options, [
            'headers' => $this->headers,
        ]));
    }

    /**
     * Build a uri string
     *
     * @param  array<int, mixed>|string  ...$args
     */
    public function uri(mixed ...$args): string
    {
        $flatArgs = collect([...$args])->flatten()->values()->all();

        /** @var string $format */
        $format = array_shift($flatArgs);

        /** @var array<int, bool|float|int|string|null> $sprintfArgs */
        $sprintfArgs = $flatArgs;

        return $this->prependVersion(sprintf($format, ...$sprintfArgs));
    }

    /**
     * Determine if the version should be prepended to the URI.
     */
    public function shouldPrependVersion(string $uri): bool
    {
        if (! static::VERSION_PATTERN
            || preg_match('/^\//', $uri)
            || preg_match(static::VERSION_PATTERN, (string) $this->baseUri)) {
            return false;
        }

        return ! preg_match(static::VERSION_PATTERN, $uri);
    }

    /**
     * Prepend the version.
     */
    public function prependVersion(string $uri): string
    {
        if (! $this->shouldPrependVersion($uri)) {
            return $uri;
        }

        return sprintf('%s/%s', static::VERSION, $uri);
    }
}

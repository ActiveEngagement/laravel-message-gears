<?php

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
     *
     * @var string
     */
    public const VERSION = null;

    /**
     * The version pattern.
     *
     * @var string
     */
    public const VERSION_PATTERN = false;

    /**
     * The Guzzle client.
     *
     * @var \GuzzleHttp\Client|null
     */
    public ?Client $client = null;

    /**
     * Create a new instance.
     */
    public function __construct(string $accountId = null, string $apiKey = null, string $baseUri = null)
    {
        $this->accountId($accountId);
        $this->apiKey($apiKey);
        $this->baseUri($baseUri);
    }

    /**
     * Call the method on the Guzzle client.
     *
     * @param string $method
     * @param array $args
     * @return \GuzzleHttp\Psr7\Response
     */
    public function __call($method, $args)
    {
        return $this->request($method, ...$args);
    }

    /**
     * Set the `client` property.
     *
     * @param string $client
     * @return self
     */
    public function client(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Create a new HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    abstract public function createHttpClient(): Client;

    /**
     * Get this instance.
     *
     * @return self
     */
    public function instance(): self
    {
        return $this;
    }

    /**
     * Mock a Guzzle client.
     *
     * @param array<\GuzzleHttp\Psr7\Request> $requests
     * @return self
     */
    public function mock(array $requests): self
    {
        return $this->client(
            new Client([
                'handler' => HandlerStack::create(
                    new MockHandler($requests)
                )
            ])
        );
    }

    /**
     * Send an HTTP request.
     *
     * @param string $method
     * @param array|string $uri
     * @param array $options
     * @return \GuzzleHttp\Psr7\Response
     */
    public function request(string $method, array|string $uri, array $options = []): Response
    {
        $client = $this->client ?? $this->createHttpClient();

        return $client->$method($this->uri($uri), array_merge_recursive($options, [
            'headers' => $this->headers
        ]));
    }

    /**
     * Build a uri string
     *
     * @param array|string ...$args
     * @return string
     */
    public function uri(...$args): string
    {
        return $this->prependVersion(sprintf(
            ...collect([...$args])->flatten()->values()
        ));
    }

    /**
     * Determine if the version should be prepended to the URI.
     *
     * @param string $uri
     * @return bool
     */
    public function shouldPrependVersion(string $uri): bool
    {
        if(!static::VERSION_PATTERN
            || preg_match('/^\//', $uri)
            || preg_match(static::VERSION_PATTERN, $this->baseUri)) {
            return false;
        }

        return !preg_match(static::VERSION_PATTERN, $uri);
    }

    /**
     * Prepend the version.
     *
     * @param string $uri
     * @return string
     */
    public function prependVersion(string $uri)
    {
        if(!$this->shouldPrependVersion($uri)) {
            return $uri;
        }
        
        return sprintf('%s/%s', static::VERSION, $uri);
    }
}
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
     * @var \GuzzleHttp\Client
     */
    public Client $client;

    /**
     * Create a new instance.
     */
    public function __construct(string $accountId, string $apiKey)
    {
        $this->accountId($accountId);
        $this->apiKey($apiKey);
        $this->client(new Client([
            'base_uri' => $this->baseUri,
            'headers' => [  
                'Accept' => 'application/json'
            ]
        ]));
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
        return $this->client->$method($this->uri($uri), array_merge_recursive($options, [
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
        return $this->prependVersion(
            collect([...$args])->flatten()->implode('/')
        );
    }

    /**
     * Determine if the version should be prepended to the URI.
     *
     * @param string $uri
     * @return bool
     */
    public function shouldPrependVersion(string $uri): bool
    {
        if(!static::VERSION_PATTERN || preg_match('/^\//', $uri)) {
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
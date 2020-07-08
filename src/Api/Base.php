<?php

namespace Actengage\LaravelMessageGears\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;

abstract class Base {

    /**
     * The api key.
     * 
     * @var string
     */
    protected $apiKey;

    /**
     * The account id.
     * 
     * @var string
     */
    protected $accountId;

    /**
     * The global Guzzle client.
     * 
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The MessageGears constructor.
     * 
     * @return void
     */
    public function __construct(?array $config = null)
    {
        $config = $config ?: [];

        $this->apiKey = Arr::get($config, 'api_key');
        $this->accountId = Arr::get($config, 'account_id');
        $this->client = $this->client(Arr::get($config, 'http_client'));
    }

    /**
     * Get the api key.
     * 
     * @return string
     */
    public function apiKey()
    {
        return $this->apiKey;
    }

    /**
     * Get the account id.
     * 
     * @return string
     */
    public function accountId()
    {
        return $this->accountId;
    }

    /**
     * Get the default request headers.
     * 
     * @return array
     */
    public function headers()
    {
        return [];
    }
    
    /**
     * Get the Guzzle client.
     * 
     * @param  array  $client
     * @return \GuzzleHttp\Client
     */
    public function client(array $params = null): Client
    {
        if(!$this->client || $params) {
            $this->client = new Client(array_merge([
                'base_uri' => $this->baseUri(),
                'headers' => array_filter(array_merge([
                    'Accept' => 'application/json',
                ], $this->headers()))
            ], ($params ?: [])));
        }

        return $this->client;
    }
    
    /**
     * Send an HTTP request.
     * 
     * @param  string  $method
     * @param  string  $uri
     * @param  array   $options
     * @return \GuzzleHttp\Client
     */
    public function request(string $method, string $uri, array $options = []): Response
    {
        return $this->client->request($method, $uri, $options);
    }
    
    /**
     * Send a POST request.
     * 
     * @param  string  $uri
     * @param  array   $options
     * @return \GuzzleHttp\Client
     */
    public function get(string $uri, array $options = []): Response
    {
        return $this->client->post($uri, $options);
    }
    
    /**
     * Send a POST request.
     * 
     * @param  string  $uri
     * @param  array   $options
     * @return \GuzzleHttp\Client
     */
    public function post(string $uri, array $options = []): Response
    {
        return $this->client->post($uri, $options);
    }
    
    /**
     * Send a PUT request.
     * 
     * @param  string  $uri
     * @param  array   $options
     * @return \GuzzleHttp\Client
     */
    public function put(string $uri, array $options = []): Response
    {
        return $this->client->put($uri, $options);
    }
    
    /**
     * Send a PATCH request.
     * 
     * @param  string  $uri
     * @param  array   $options
     * @return \GuzzleHttp\Client
     */
    public function patch(string $uri, array $options = []): Response
    {
        return $this->client->patch($uri, $options);
    }
    
    /**
     * Send a DELETE request.
     * 
     * @param  string  $uri
     * @param  array   $options
     * @return \GuzzleHttp\Client
     */
    public function delete(string $uri, array $options = []): Response
    {
        return $this->client->delete($uri, $options);
    }

    /**
     * Define the API base endpoint URI.
     * 
     * @abstract
     * @return string
     */
    abstract public function baseUri();
}
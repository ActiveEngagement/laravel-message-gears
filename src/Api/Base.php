<?php

namespace Actengage\LaravelMessageGears\Api;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Actengage\LaravelMessageGears\Concerns\HasAccount;
use Actengage\LaravelMessageGears\Concerns\HasCampaign;

abstract class Base {
    
    use HasAccount;

    /**
     * Override the default uri.
     * 
     * @var string
     */
    protected $baseUri;

    /**
     * The global Guzzle client.
     * 
     * @var \GuzzleHttp\Client
     */
    public $client;

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
        $this->baseUri = Arr::get($config, 'base_uri', $this->defaultBaseUri());
        $this->client(Arr::get($config, 'client'));
    }

    /**
     * Get/set the api key.
     * 
     * @return string
     */
    public function apiKey($apiKey = null)
    {
        if(!count(func_get_args())){
            return $this->apiKey;
        }

        $this->apiKey = $apiKey;
        $this->client();

        return $this;
    }

    /**
     * Get/set the account id.
     * 
     * @return string
     */
    public function accountId($accountId = null)
    {
        if(!count(func_get_args())){
            return $this->accountId;
        }

        $this->accountId = $accountId;
        $this->client();

        return $this;
    }
    
    /**
     * Get/set the baseUri.
     * 
     * @return string
     */
    public function baseUri($baseUri = null)
    {
        if(!count(func_get_args())){
            return $this->baseUri;
        }

        $this->baseUri = $baseUri ?: $this->defaultBaseUri();
        $this->client();

        return $this;
    }

    /**
     * Get the Guzzle client.
     * 
     * @param  array  $client
     * @return \GuzzleHttp\Client
     */
    public function client($config = null): Client
    {
        $mergedConfig = array_merge((
            $this->client ? $this->client->getConfig() : []
        ), [
            'base_uri' => $this->baseUri(),
            'headers' => array_merge([
                'Accept' => 'application/json',
            ], array_filter($this->headers())) 
        ], (
            is_array($config) ? $config : []
        ));
        
        return $this->client = new Client($mergedConfig);
    }
        
    /**
     * Send an HTTP request.
     * 
     * @param  string  $method
     * @param  string  $uri
     * @param  array   $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        return $this->client->request($method, $uri, $options);
    }
    
    /**
     * Send a GET request.
     * 
     * @param  string  $uri
     * @param  array   $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(string $uri, array $options = []): ResponseInterface
    {
        return $this->client->get($uri, $options);
    }
    
    /**
     * Send a POST request.
     * 
     * @param  string  $uri
     * @param  array   $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function post(string $uri, array $options = []): ResponseInterface
    {
        return $this->client->post($uri, $options);
    }
    
    /**
     * Send a PUT request.
     * 
     * @param  string  $uri
     * @param  array   $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function put(string $uri, array $options = []): ResponseInterface
    {
        return $this->client->put($uri, $options);
    }
    
    /**
     * Send a PATCH request.
     * 
     * @param  string  $uri
     * @param  array   $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function patch(string $uri, array $options = []): ResponseInterface
    {
        return $this->client->patch($uri, $options);
    }
    
    /**
     * Send a DELETE request.
     * 
     * @param  string  $uri
     * @param  array   $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(string $uri, array $options = []): ResponseInterface
    {
        return $this->client->delete($uri, $options);
    }

    /**
     * Define the default API base URI.
     * 
     * @abstract
     * @return string
     */
    abstract public function defaultBaseUri();

    /**
     * Get the default request headers.
     * 
     * @return array
     */
    abstract public function headers();
}
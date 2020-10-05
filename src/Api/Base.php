<?php

namespace Actengage\LaravelMessageGears\Api;

use Actengage\LaravelMessageGears\Concerns\HasAccount;
use Actengage\LaravelMessageGears\Concerns\HasCampaign;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;

abstract class Base {
    
    use HasAccount;

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
        $this->client(Arr::get($config, 'client'));
    }

    /**
     * Get/set the api key.
     * 
     * @return string
     */
    public function apiKey($apiKey = null)
    {
        if(is_null($apiKey)){
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
        if(is_null($accountId)){
            return $this->accountId;
        }

        $this->accountId = $accountId;
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
        return $this->client->get($uri, $options);
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

    /**
     * Get the default request headers.
     * 
     * @return array
     */
    abstract public function headers();
}
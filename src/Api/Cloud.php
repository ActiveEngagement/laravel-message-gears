<?php

namespace Actengage\LaravelMessageGears\Api;

use Actengage\LaravelMessageGears\Api\Base;
use Actengage\LaravelMessageGears\Concerns\HasCampaign;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;

class Cloud extends Base {

    use HasCampaign;

    /**
     * The authentication bearer.
     * 
     * @var \Actengage\LaravelMessageGears\Api\BearerToken
     */
    public $bearerToken;

    /**
     * The MessageGears constructor.
     * 
     * @return void
     */
    public function __construct(?array $config = null)
    {
        $this->campaignId = Arr::get($config, 'campaign_id');
        $this->campaignVersion = Arr::get($config, 'campaign_version');
        
        parent::__construct($config);
    }

    /**
     * Define the API base endpoint URI.
     * 
     * @return string
     */
    public function defaultBaseUri()
    {
        return 'https://api.messagegears.net/v5/';    
    }

    /**
     * Retrieve a new authentication bearer token
     * 
     * @return \Actengage\LaravelMessageGears\Api\BearerToken;
     */
    public function bearerToken()
    {
        $response = parent::post('provisioning/login', [
            'json' => [
                'username' => $this->accountId,
                'password' => $this->apiKey,
            ] 
        ]);

        return $this->bearerToken = BearerToken::response($response);
    }

    /**
     * Get the default request headers.
     * 
     * @return array
     */
    public function headers()
    {
        return [
            'Authorization' => (string) $this->bearerToken
        ];
    }

    /**
     * Is the authentication bearer token valid.
     * 
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->bearerToken && $this->bearerToken->isActive();
    }

    /**
     * Authenticate the account with the set credentials.
     * 
     * @return \Actengage\LaravelMessageGears\Api\BearerToken
     */
    public function authenticate()
    {
        $this->bearerToken();
        $this->client();

        return $this->bearerToken;
    }

    /**
     * Attempt to authenticate user. Ignore if already authenticated.
     * 
     * @return \Actengage\LaravelMessageGears\Api\BearerToken
     */
    public function attemptToAuthenticate()
    {
        if(!$this->isAuthenticated()) {
            return $this->authenticate();
        }

        return $this->bearerToken;
    }
    
    /**
     * Send an HTTP request.
     * 
     * @param  array  $client
     * @return \GuzzleHttp\Client
     */
    public function request(string $method, string $uri, array $options = []): Response
    {
        $this->authenticate();

        return parent::request($method, $uri, $options);
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
        $this->authenticate();

        return parent::get($uri, $options);
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
        $this->authenticate();        

        return parent::post($uri, $options);
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
        $this->authenticate();

        return parent::put($uri, $options);
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
        $this->authenticate();

        return parent::patch($uri, $options);
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
        $this->authenticate();

        return parent::delete($uri, $options);
    }
}
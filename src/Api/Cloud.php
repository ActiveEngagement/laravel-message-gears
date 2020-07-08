<?php

namespace Actengage\LaravelMessageGears\Api;

use Actengage\LaravelMessageGears\Api\Base;
use GuzzleHttp\Psr7\Response;

class Cloud extends Base {

    /**
     * The authentication bearer.
     * 
     * @var \Actengage\LaravelMessageGears\Api\BearerToken
     */
    protected $bearerToken;

    /**
     * Define the API base endpoint URI.
     * 
     * @return string
     */
    public function baseUri()
    {
        return 'https://api.messagegears.net/v5';    
    }

    /**
     * Retrieve a new authentication bearer token
     * 
     * @return \Actengage\LaravelMessageGears\Api\BearerToken;
     */
    public function bearerToken()
    {
        $response = $this->post('provisioning/login', [
            'json' => [
                'username' => $this->accountId,
                'password' => $this->apiKey,
            ] 
        ]);

        return BearerToken::response($response);
    }

    /**
     * Get the default request headers.
     * 
     * @return array
     */
    public function headers()
    {
        return [
            'Authorization' => $this->bearerToken
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
     * Send an HTTP request.
     * 
     * @param  array  $client
     * @return \GuzzleHttp\Client
     */
    public function request(string $method, string $uri, array $options = []): Response
    {
        if(!$this->isAuthenticated()) {
            $this->bearerToken = $this->bearerToken();
        }

        return parent::request($method, $uri, $options);
    }
}
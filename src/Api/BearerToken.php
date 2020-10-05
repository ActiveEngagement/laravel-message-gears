<?php

namespace Actengage\LaravelMessageGears\Api;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;

class BearerToken {

    /**
     * The bearer expiration.
     * 
     * @var \Carbon\Carbon
     */
    protected $expiresAt;

    /**
     * The bearer token.
     * 
     * @var string
     */
    protected $token;

    /**
     * The BearerToken constructor.
     * 
     * @return void
     */
    public function __construct(string $token, Carbon $expiresAt)
    {
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Is the bearer token expired.
     * 
     * @return bool
     */
    public function isExpired()
    {
        return $this->expiresAt->isPast();
    }

    /**
     * Is the bearer token active.
     * 
     * @return bool
     */
    public function isActive()
    {
        return !$this->isExpired();
    }

    /**
     * Cast the token as a string.
     * 
     * @return bool
     */
    public function __toString()
    {
        return $this->token;   
    }

    /**
     * Create a bearer token from a guzzle response.
     * 
     * @param \GuzzleHttp\Psr7\Response
     * @return \Actengage\LaravelMessageGears\Api\BearerToken
     */
    public static function response(Response $response)
    {
        $body = json_decode($response->getBody());

        

        return new BearerToken($body->token, Carbon::make($body->expirationDate));
    }
}
<?php

namespace Actengage\MessageGears;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;

class BearerToken {

    /**
     * The bearer expiration.
     * 
     * @var \Carbon\Carbon
     */
    public Carbon $expirationDate;

    /**
     * The bearer token.
     * 
     * @var string
     */
    public string $token;

    /**
     * Create a new instance.
     *
     * @param Response|string $token
     * @param Carbon|null $expirationDate
     */
    public function __construct(Response|string $token, Carbon $expirationDate = null)
    {        
        if($token instanceof Response) {
            extract(json_decode($token->getBody(), true));
        }

        $this->token = $token;
        $this->expirationDate = Carbon::make($expirationDate);
    }

    /**
     * Checks if the bearer token expired.
     * 
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expirationDate->isPast();
    }

    /**
     * Checks if the bearer token active.
     * 
     * @return bool
     */
    public function isActive(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Cast the instance as a string.
     * 
     * @return string
     */
    public function __toString(): string
    {
        return $this->token;   
    }

    /**
     * Create a bearer token instance from a GuzzleResponse.
     * 
     * @param \GuzzleHttp\Psr7\Response
     * @return \Actengage\MessageGears\BearerToken
     */
    public static function response(Response $response)
    {
        return new BearerToken($response);
    }
}
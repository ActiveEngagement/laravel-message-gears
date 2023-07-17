<?php

namespace Actengage\MessageGears;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;

class BearerToken
{
    /**
     * The bearer expiration.
     */
    public Carbon $expirationDate;

    /**
     * The bearer token.
     */
    public string $token;

    /**
     * Create a new instance.
     */
    public function __construct(Response|string $token, Carbon $expirationDate = null)
    {
        if ($token instanceof Response) {
            extract(json_decode($token->getBody(), true));
        }

        $this->token = $token;
        $this->expirationDate = Carbon::make($expirationDate)->subSeconds(30);
    }

    /**
     * Checks if the bearer token expired.
     */
    public function isExpired(): bool
    {
        return $this->expirationDate->isPast();
    }

    /**
     * Checks if the bearer token active.
     */
    public function isActive(): bool
    {
        return ! $this->isExpired();
    }

    /**
     * Cast the instance as a string.
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

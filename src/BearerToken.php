<?php

declare(strict_types=1);

namespace Actengage\MessageGears;

use Carbon\CarbonInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Date;
use Stringable;

class BearerToken implements Stringable
{
    /**
     * The bearer expiration.
     */
    public CarbonInterface $expirationDate;

    /**
     * The bearer token.
     */
    public string $token;

    /**
     * Create a new instance.
     */
    public function __construct(Response|string $token, ?CarbonInterface $expirationDate = null)
    {
        if ($token instanceof Response) {
            /** @var array{token: string, expirationDate: string} $data */
            $data = json_decode((string) $token->getBody(), true);
            $token = $data['token'];
            $expirationDate = Date::parse($data['expirationDate']);
        }

        $this->token = $token;
        $this->expirationDate = Date::parse($expirationDate, 'utc')->subSeconds(30);
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
     */
    public static function response(Response $response): self
    {
        return new self($response);
    }
}

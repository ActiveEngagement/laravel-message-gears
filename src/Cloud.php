<?php

declare(strict_types=1);

namespace Actengage\MessageGears;

use GuzzleHttp\Client;
use Override;

class Cloud extends Api
{
    /**
     * The default API version.
     */
    public const string VERSION = 'v5.1';

    /**
     * The version pattern.
     */
    public const string VERSION_PATTERN = '/v\d(\.\d)?\//';

    /**
     * The MessageGears endpoint base URI.
     */
    public ?string $baseUri = 'https://api.messagegears.net/v5';

    /**
     * The authentication bearer token.
     */
    public ?BearerToken $bearerToken = null;

    /**
     * Ensures the requests are authenticated.
     */
    public function authenticate(): static
    {
        if ($this->isAuthenticated()) {
            return $this;
        }

        $response = $this->post('v5/provisioning/login', [
            'json' => [
                'username' => $this->accountId,
                'password' => $this->apiKey,
            ],
        ]);

        $this->bearerToken(BearerToken::response($response));

        /** @var BearerToken $bearerToken */
        $bearerToken = $this->bearerToken;
        $this->header('Authorization', $bearerToken->token);

        return $this;
    }

    /**
     * Set the `bearerToken` property.
     */
    public function bearerToken(BearerToken $token): self
    {
        $this->bearerToken = $token;

        return $this;
    }

    /**
     * Create a new HTTP client.
     */
    #[Override]
    public function createHttpClient(): Client
    {
        return new Client([
            'base_uri' => $this->baseUri,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Checks it an active bearer token exists.
     */
    public function isAuthenticated(): bool
    {
        return $this->bearerToken instanceof BearerToken && $this->bearerToken->isActive();
    }
}

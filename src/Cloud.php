<?php

namespace Actengage\MessageGears;

use GuzzleHttp\Client;

class Cloud extends Api
{
    /**
     * The default API version.
     *
     * @var string
     */
    public const VERSION = 'v5.1';

    /**
     * The version pattern.
     *
     * @var string
     */
    public const VERSION_PATTERN = '/v\d(\.\d)?\//';

    /**
     * The MessageGears endpoint base URI.
     */
    public ?string $baseUri = 'https://api.messagegears.net/';

    /**
     * The authentication bearer token.
     *
     * @var \Actengage\MessageGears\BearerToken|null
     */
    public ?BearerToken $bearerToken = null;

    /**
     * Ensures the requests are authenticated.
     */
    public function authenticate(): self
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

        return $this
            ->bearerToken(BearerToken::response($response))
            ->header('Authorization', $this->bearerToken->token);
    }

    /**
     * Set the `bearerToken` property.
     *
     * @param  \Actengage\MessageGears\BearerToken  $token
     */
    public function bearerToken(BearerToken $token): self
    {
        $this->bearerToken = $token;

        return $this;
    }

    /**
     * Create a new HTTP client.
     */
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
        return $this->bearerToken && $this->bearerToken->isActive();
    }
}

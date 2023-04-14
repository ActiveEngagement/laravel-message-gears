<?php

namespace Actengage\MessageGears\Concerns;

trait HasApiCredentials
{
    /**
     * The MessageGears account ID.
     */
    public ?string $accountId = null;

    /**
     * The MessageGears API key.
     */
    public ?string $apiKey = null;

    /**
     * The MessageGears endpoint base URI.
     */
    public ?string $baseUri = null;

    /**
     * The request headers.
     */
    public array $headers = [];

    /**
     * Set the `accountId` property.
     */
    public function accountId(?string $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Set the `apiKey` property.
     */
    public function apiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set the `baseUri` property.
     */
    public function baseUri(?string $baseUri): self
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * Set the configurations using an array.
     */
    public function configure(array $config): self
    {
        foreach ($config as $key => $value) {
            $this->$key($value);
        }

        return $this;
    }

    /**
     * Set the `header` property.
     *
     * @param  array  $key
     * @param  array|null  $value
     */
    public function header(string $key, ?string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set the `headers` property.
     */
    public function headers(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }
}

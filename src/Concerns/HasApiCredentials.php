<?php

declare(strict_types=1);

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
     *
     * @var array<string, string|null>
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
     *
     * @param  array<string, mixed>  $config
     */
    public function configure(array $config): static
    {
        foreach ($config as $key => $value) {
            $this->$key($value);
        }

        return $this;
    }

    /**
     * Set a single header.
     */
    public function header(string $key, ?string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set the `headers` property.
     *
     * @param  array<string, string|null>  $headers
     */
    public function headers(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }
}

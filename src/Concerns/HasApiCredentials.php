<?php

namespace Actengage\MessageGears\Concerns;

trait HasApiCredentials
{
    /**
     * The MessageGears account ID.
     *
     * @var string
     */
    public string $accountId;

    /**
     * The MessageGears API key.
     *
     * @var string
     */
    public string $apiKey;

    /**
     * The MessageGears endpoint base URI.
     *
     * @var string
     */
    public string $baseUri;

    /**
     * The request headers.
     *
     * @var array
     */
    public array $headers = [];

    /**
     * Set the `accountId` property.
     *
     * @param string $accountId
     * @return self
     */
    public function accountId(string $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Set the `apiKey` property.
     *
     * @param string $apiKey
     * @return self
     */
    public function apiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set the `baseUri` property.
     *
     * @param string $baseUri
     * @return self
     */
    public function baseUri(string $baseUri): self
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * Set the `header` property.
     *
     * @param array $key
     * @param array|null $value
     * @return self
     */
    public function header(string $key, ?string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set the `headers` property.
     *
     * @param array $headers
     * @return self
     */
    public function headers(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }
}
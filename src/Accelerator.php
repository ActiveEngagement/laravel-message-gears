<?php

declare(strict_types=1);

namespace Actengage\MessageGears;

use GuzzleHttp\Client;
use Override;

class Accelerator extends Api
{
    /**
     * The default API version.
     */
    public const string VERSION = 'beta';

    /**
     * The version pattern.
     */
    public const string VERSION_PATTERN = '/beta\//';

    /**
     * The MessageGears endpoint base URI.
     */
    public ?string $baseUri = 'http://gears.listelixr.net:8080/';

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
                'ApiKey' => $this->apiKey,
                'CustomerId' => $this->accountId,
            ],
        ]);
    }
}

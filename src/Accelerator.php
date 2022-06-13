<?php

namespace Actengage\MessageGears;

use GuzzleHttp\Client;

class Accelerator extends Api
{
    /**
     * The default API version.
     *
     * @var string
     */
    public const VERSION = 'beta';

    /**
     * The version pattern.
     *
     * @var string
     */
    public const VERSION_PATTERN = '/beta\//';

    /**
     * The MessageGears endpoint base URI.
     *
     * @var string
     */
    public string $baseUri = 'http://gears.listelixr.net:8080/';

    /**
     * Create a new instance.
     */
    public function __construct(string $accountId, string $apiKey)
    {
        $this->accountId($accountId);
        $this->apiKey($apiKey);
        $this->client(new Client([
            'base_uri' => $this->baseUri,
            'headers' => [  
                'Accept' => 'application/json',
                'ApiKey' => $this->apiKey,
                'CustomerId' => $this->accountId,
            ]
        ]));
    }
}
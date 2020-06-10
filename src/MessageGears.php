<?php

namespace Actengage\LaravelMessageGears;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class MessageGears {

    const BASE_URI = 'https://api.messagegears.net/3.1/WebService';

    /**
     * The global api key.
     * 
     * @var string
     */
    protected $apiKey;

    /**
     * The global account id.
     * 
     * @var string
     */
    protected $accountId;

    /**
     * The global campaign id.
     * 
     * @var string
     */
    protected $campaignId;

    /**
     * The global Guzzle client.
     * 
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The MessageGears constructor.
     * 
     * @return void
     */
    public function __construct(?array $config = null)
    {
        $config = $config ?: [];

        $this->apiKey = Arr::get($config, 'api_key');
        $this->accountId = Arr::get($config, 'account_id');
        $this->campaignId = Arr::get($config, 'campaign_id');
        $this->client = $this->client();
    }

    /**
     * Get the api key.
     * 
     * @return string
     */
    public function apiKey()
    {
        return $this->apiKey;
    }

    /**
     * Get the account id.
     * 
     * @return string
     */
    public function accountId()
    {
        return $this->accountId;
    }

    /**
     * Get the campaign id.
     * 
     * @return string
     */
    public function campaignId()
    {
        return $this->campaignId;
    }

    /**
     * Get the Guzzle client.
     * 
     * @param  array  $client
     * @return \GuzzleHttp\Client
     */
    public function client(array $params = null)
    {
        if(!$this->client || $params) {
            $this->client = new Client(array_merge([
                'base_uri' => static::BASE_URI
            ], ($params ?: [])));
        }

        return $this->client;
    }

    /**
     * Mock the Guzzle request.
     * 
     * @return \GuzzleHttp\Client
     */
    public function mock($mock)
    {
        $this->client([
            'handler' => $mock
        ]);

        return $this;
    }

    /**
     * Submit a transactional campaign.
     * 
     * @param \Actengage\LaravelMessageGears\TransactionalCampaignMessage  $message
     * @return \GuzzleHttp\Psr7\Response
     */
    public function submitTransactionalCampaign($message): Response
    {
        if(!$message instanceof TransactionalCampaignMessage) {
            $message = new TransactionalCampaignMessage($message);
        }

        $request = $message->toRequest([
            'AccountId' => $this->accountId,
            'ApiKey' => $this->apiKey,
            'CampaignId' => $this->campaignId,
        ]);

        return $this->client->send($request);
    }

}
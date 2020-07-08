<?php

namespace Actengage\LaravelMessageGears\Api;

use Illuminate\Contracts\Support\Arrayable;

abstract class Adapter {

    /**
     * The message that is being adapted.
     * 
     * @var Illuminate\Contracts\Support\Arrayable
     */
    protected $message;

    /**
     * Get the api key.
     * 
     * @return string
     */
    public function __construct(Arrayable $message)
    {
        $this->message = $message;
    }

    /**
     * Send the adapted message.
     * 
     * @return \GuzzleHttp\Psr7\Response
     */
    public function send()
    {
        $uri = 'campaign/transactional/'.$this->message->campaignId;

        return app('mg.api.cloud')->post($uri, [
            'accountId' => $this->message->accountId,
            'recipient' => $this->recipient->toXml()->toString(),
            'context' => $this->context->toXml()->toString(),
            'campaign'
        ]);
    }
}
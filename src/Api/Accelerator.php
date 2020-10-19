<?php

namespace Actengage\LaravelMessageGears\Api;

use Actengage\LaravelMessageGears\Api\Base;

class Accelerator extends Base {

    protected $baseUri = 'http://gears.listelixr.net:8080/beta/';

    /**
     * Get/set the account id.
     * 
     * @return string
     */
    public function baseUri($baseUri = null)
    {
        if(is_null($baseUri)){
            return $this->baseUri;
        }

        $this->baseUri = $baseUri;
        $this->client();

        return $this;
    }

    /**
     * Get the default request headers.
     * 
     * @return array
     */
    public function headers()
    {
        return [
            'ApiKey' => $this->apiKey,
            'CustomerId' => $this->accountId,
        ];
    }

}
<?php

namespace Actengage\LaravelMessageGears\Api;

use Actengage\LaravelMessageGears\Api\Base;

class Accelerator extends Base {

    /**
     * Define the API base endpoint URI.
     * 
     * @return string
     */
    public function defaultBaseUri()
    {
        return 'http://gears.listelixr.net:8080/beta/';
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
<?php

namespace Actengage\LaravelMessageGears\Concerns;

trait HasApiKey {

    /**
     * The api key.
     *
     * @var string
     */
    public $apiKey;

    /**
     * Get/set the api key of the notification.
     *
     * @param  string  $apiKey
     * @return mixed
     */
    public function apiKey($apiKey = null)
    {
        if(is_null($apiKey)) {
            return $this->apiKey;
        }

        $this->apiKey = $apiKey;

        return $this;
    }

}
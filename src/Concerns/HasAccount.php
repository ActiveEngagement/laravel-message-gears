<?php

namespace Actengage\LaravelMessageGears\Concerns;

trait HasAccount {
    
    /**
     * The account id.
     *
     * @var string
     */
    public $accountId;

    /**
     * The api key.
     *
     * @var string
     */
    public $apiKey;

    /**
     * Get/set the account id of the notification.
     *
     * @param  string  $accountId
     * @return mixed|static
     */
    public function accountId($accountId = null)
    {
        if(is_null($accountId)) {
            return $this->accountId;
        }

        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Get/set the api key of the notification.
     *
     * @param  string  $apiKey
     * @return mixed|static
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
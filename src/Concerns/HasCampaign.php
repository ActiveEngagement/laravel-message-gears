<?php

namespace Actengage\LaravelMessageGears\Concerns;

trait HasCampaign {
    
    /**
     * The campaign id.
     *
     * @var string
     */
    public $campaignId;
    
    /**
     * The campaign version.
     *
     * @var string
     */
    public $campaignVersion;

    /**
     * Get/set the campaign id.
     *
     * @param  string  $campaignId
     * @return mixed
     */
    public function campaignId(string $campaignId = null)
    {
        if(is_null($campaignId)) {
            return $this->campaignId;
        }

        $this->campaignId = $campaignId;

        return $this;
    }

    /**
     * Get/set the campaign version.
     *
     * @param  string  $campaignId
     * @return mixed
     */
    public function campaignVersion(string $campaignVersion = null)
    {
        if(is_null($campaignVersion)) {
            return $this->campaignVersion;
        }

        $this->campaignVersion = $campaignVersion;

        return $this;
    }

}
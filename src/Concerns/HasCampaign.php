<?php

namespace Actengage\MessageGears\Concerns;

trait HasCampaign
{
    /**
     * The campaign ID.
     */
    public string $campaignId;

    /**
     * The campaign version.
     */
    public ?string $campaignVersion = null;

    /**
     * Set the `campaignId` property.
     */
    public function campaignId(string $campaignId): self
    {
        $this->campaignId = $campaignId;

        return $this;
    }

    /**
     * Set the `campaignVersion` property.
     */
    public function campaignVersion(string $campaignVersion): self
    {
        $this->campaignVersion = $campaignVersion;

        return $this;
    }
}

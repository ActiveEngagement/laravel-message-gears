<?php

namespace Actengage\MessageGears\Concerns;

trait HasSender
{
    /**
     * The email from address.
     */
    public string $fromAddress;

    /**
     * The email from name.
     */
    public string $fromName;

    /**
     * The email reply to address.
     */
    public string $replyToAddress;

    /**
     * Set the `fromAddress` property.
     *
     * @param  string  $subject
     */
    public function fromAddress(string $fromAddress): self
    {
        $this->fromAddress = $fromAddress;

        return $this;
    }

    /**
     * Set the `fromName` property.
     */
    public function fromName(string $fromName): self
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * Set the `replyToAddress` property.
     */
    public function replyToAddress(string $replyToAddress): self
    {
        $this->replyToAddress = $replyToAddress;

        return $this;
    }
}

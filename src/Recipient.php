<?php

namespace Actengage\MessageGears;

use Illuminate\Contracts\Support\Arrayable;

class Recipient implements Arrayable
{
    /**
     * The email address.
     *
     * @var string
     */
    public $email;

    /**
     * The id of the recipient.
     *
     * @var string
     */
    public $recipientId;

    /**
     * Set the `email` property.
     *
     * @param string $email
     * @return self
     */
    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the `recipientId` property.
     *
     * @param string $recipientId
     * @return self
     */
    public function recipientId(string $recipientId): self
    {
        $this->recipientId = $recipientId;

        return $this;
    }

    /**
     * Convert the instance to an array.
     * 
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'EmailAddress' => $this->email,
            'RecipientId' => $this->recipientId,
        ]);
    }
}
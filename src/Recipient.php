<?php

declare(strict_types=1);

namespace Actengage\MessageGears;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string>
 */
class Recipient implements Arrayable
{
    /**
     * The email address.
     */
    public ?string $email = null;

    /**
     * The id of the recipient.
     */
    public ?string $recipientId = null;

    /**
     * Set the `email` property.
     */
    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the `recipientId` property.
     */
    public function recipientId(string $recipientId): self
    {
        $this->recipientId = $recipientId;

        return $this;
    }

    /**
     * Convert the instance to an array.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return array_filter([
            'EmailAddress' => $this->email,
            'RecipientId' => $this->recipientId,
        ]);
    }
}

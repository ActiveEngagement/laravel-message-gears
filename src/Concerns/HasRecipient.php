<?php

namespace Actengage\LaravelMessageGears\Concerns;

use Actengage\LaravelMessageGears\Recipient;
use Illuminate\Foundation\Auth\User;

trait HasRecipient {

    /**
     * The email recipient.
     *
     * @var Actenage\LaravelMessageGears\Recipient
     */
    public $recipient;

    /**
     * Set the email recipient of the notification.
     *
     * @param  mixed  $recipient
     * @return $this
     */
    public function recipient($recipient)
    {
        if(is_callable($recipient)) {
            $this->recipient = $recipient(new Recipient());
        }
        else if($recipient instanceof User) {
            $this->recipient = new Recipient($recipient->toArray());
        }
        else if($recipient) {
            $this->recipient = new Recipient($recipient);
        }
        else {
            $this->recipient = null;
        }

        return $this;
    }

    /**
     * An alias for the recipient method.
     *
     * @param  string  $recipient
     * @return $this
     */
    public function to(...$args)
    {
        return $this->recipient(...$args);
    }

}
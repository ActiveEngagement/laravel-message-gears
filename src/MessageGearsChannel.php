<?php

namespace Actengage\LaravelMessageGears;

use Actengage\LaravelMessageGears\Contracts\HttpMessage;
use Actengage\LaravelMessageGears\Exceptions\InvalidRequestMessage;
use Actengage\LaravelMessageGears\Exceptions\InvalidTransactionalCampaignSubmit;
use Illuminate\Notifications\Notification;

class MessageGearsChannel
{
    /**
     * Send the given notification to MessageGears.
     *
     * @param  mixed  $notifiable
     * @param  \Actengage\Notifications\SendTransactionalCampaign  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toMessageGears($notifiable);

        if(!$message instanceof HttpMessage) {
            throw new InvalidTransactionalCampaignSubmit;
        }

        $message->send();
    }

}
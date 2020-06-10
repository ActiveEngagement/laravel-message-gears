<?php

namespace Actengage\LaravelMessageGears;

use Actengage\LaravelMessageGears\Exceptions\InvalidTransactionalCampaignMesssage;
use Illuminate\Notifications\Notification;

class TransactionalCampaignChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toTransactionalCampaign($notifiable);
        
        if(!$message instanceof TransactionalCampaignMessage) {
            throw new InvalidTransactionalCampaignMesssage();
        }
        
        $message->recipient(
            $notifiable->routeNotificationFor('transactional_campaign', $notification) ?: $notifiable->email
        );

        app('messagegears')->submitTransactionalCampaign($message);
    }
}
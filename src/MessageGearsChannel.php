<?php

namespace Actengage\MessageGears;

use Actengage\MessageGears\Notifications\Notification;

class MessageGearsChannel
{
    /**
     * Send the given notification to MessageGears.
     *
     * @param  mixed  $notifiable
     * @param  \Actengage\MessageGears\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $notification->send($notifiable);
    }
}
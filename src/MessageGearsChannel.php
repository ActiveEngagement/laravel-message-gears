<?php

declare(strict_types=1);

namespace Actengage\MessageGears;

use Actengage\MessageGears\Notifications\Notification;

class MessageGearsChannel
{
    /**
     * Send the given notification to MessageGears.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $notification->send($notifiable);
    }
}

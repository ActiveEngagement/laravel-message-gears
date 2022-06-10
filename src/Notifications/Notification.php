<?php

namespace Actengage\MessageGears\Notifications;

use Actengage\MessageGears\Concerns\HasApiCredentials;
use Actengage\MessageGears\Recipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as BaseNotification;

abstract class Notification extends BaseNotification implements ShouldQueue
{
    use HasApiCredentials, Queueable;

    /**
     * Retrieve the recipient from the notifiable.
     *
     * @param  mixed $notifiable
     * @return Recipient
     */
    public function recipient($notifiable): Recipient
    {
        $email = $notifiable->routeNotificationFor('message_gears', $this);
        
        return (new Recipient())->email($email ?: $notifiable->email);
    }

    /**
     * Send the notification.
     *
     * @param object $notifiable
     * @return void
     */
    abstract public function send($notifiable);

    /**
     * Get the endpoint URI for the notification.
     *
     * @return string
     */
    abstract public function uri(): string;

    /**
     * Statically create a new instance.
     *
     * @return static
     */
    public static function make(): static
    {
        return new static();
    }
}
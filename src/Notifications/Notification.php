<?php

declare(strict_types=1);

namespace Actengage\MessageGears\Notifications;

use Actengage\MessageGears\Concerns\HasApiCredentials;
use Actengage\MessageGears\Recipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as BaseNotification;

/**
 * @phpstan-consistent-constructor
 */
abstract class Notification extends BaseNotification implements ShouldQueue
{
    use HasApiCredentials;
    use Queueable;

    /**
     * Retrieve the recipient from the notifiable.
     */
    public function recipient(object $notifiable): Recipient
    {
        $email = null;

        if (method_exists($notifiable, 'routeNotificationFor')) {
            $email = $notifiable->routeNotificationFor('message_gears', $this);
        }

        if (! is_string($email) && isset($notifiable->email)) {
            $email = $notifiable->email;
        }

        return (new Recipient)->email(is_string($email) ? $email : '');
    }

    /**
     * Send the notification.
     */
    abstract public function send(object $notifiable): void;

    /**
     * Get the endpoint URI for the notification.
     */
    abstract public function uri(): string;

    /**
     * Statically create a new instance.
     */
    public static function make(): static
    {
        return new static;
    }
}

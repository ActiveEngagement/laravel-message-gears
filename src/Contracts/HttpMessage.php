<?php

declare(strict_types=1);

namespace Actengage\MessageGears\Contracts;

use GuzzleHttp\Psr7\Response;

interface HttpMessage
{
    /**
     * Send the message as an http request
     */
    public function send(): Response;

    /**
     * Cast the message as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}

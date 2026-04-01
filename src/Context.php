<?php

declare(strict_types=1);

namespace Actengage\MessageGears;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class Context extends Repository implements Arrayable
{
    /**
     * Convert the instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        /** @var array<string, mixed> */
        return collect($this->items)->toArray();
    }
}

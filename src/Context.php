<?php

namespace Actengage\MessageGears;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Support\Arrayable;

class Context extends Repository implements Arrayable
{
    /**
     * Convert the instance to an array.
     * 
     * @return array
     */
    public function toArray()
    {
        return collect($this->items)->toArray();
    }
}
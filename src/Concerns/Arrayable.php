<?php

namespace Actengage\LaravelMessageGears\Concerns;

use Illuminate\Contracts\Support\Arrayable as ArrayableInterface;

trait Arrayable {

    /**
     * Cast the message as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return collect((array) $this)
            ->map(function($value) {
                if($value instanceof ArrayableInterface) {
                    return $value->toArray();
                }

                return $value;
            })
            ->all();
    }

}
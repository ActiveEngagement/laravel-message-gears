<?php

namespace Actengage\LaravelMessageGears;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr as BaseArr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Arr extends BaseArr {

    /**
     * Convert the array keys to studly strings.
     * 
     * @param  array  $subject
     * @return array
     */
    static public function studlyKeys(array $subject)
    {
        return (new Collection($subject))
            ->mapWithKeys(function($value, $key) {
                return [Str::studly($key) => $value];
            })
            ->all();
    }
    
    /**
     * Recursively convert an array.
     * 
     * @param  array  $items
     * @return array
     */
    static public function toArray(array $items)
    {
        $return = [];

        foreach($items as $key => $value) {
            if($value instanceof Arrayable) {
                $value = $value->toArray();
            }

            if(is_object($value)) {
                $value = (array) $value;
            }

            if(is_array($value)) {
                $return[$key] = static::toArray($value);
            }
            else {
                $return[$key] = $value;
            }
        }

        return $return;
    }

}
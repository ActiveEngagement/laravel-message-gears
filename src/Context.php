<?php

namespace Actengage\LaravelMessageGears;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Support\Arrayable;

class Context extends Repository implements Arrayable, Xmlable {

    /**
     * Merge an array into the context.
     * 
     * @param  array  $items
     * @return $this
     */
    public function merge(array $items)
    {
        foreach($items as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }
    
    /**
     * Cast the context as an array.
     * 
     * @return array
     */
    public function toArray()
    {
        return Arr::toArray($this->items);
    }
    
    /**
     * Cast the context as XML.
     * 
     * @return \Actengage\LaravelMessageGears\Xml
     */
    public function toXml()
    {
        return Xml::fromArray([
            'ContextData' => $this->toArray()
        ]);
    }
}
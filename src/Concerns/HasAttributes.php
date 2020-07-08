<?php

namespace Actengage\LaravelMessageGears\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

trait HasAttributes {
    
    /**
     * Set the message properties.
     *
     * @param  string|array  $params
     * @param  mixed  $value
     * @return $this
     */
    public function get($key, $default = null) {
        return Arr::get($this->toArray(), $key, $default);
    }

    /**
     * Set the message properties.
     *
     * @param  string|array  $params
     * @param  mixed  $value
     * @return $this
     */
    public function set($params, $value = null)
    {
        if(is_array($params)) {  
            foreach($params as $key => $value) {
                $this->set($key, $value);
            }
        }
        else {
            if(method_exists($this, $params)) {
                $this->$params($value);
            }
            else if(property_exists($this, $params)) {
                $this->$params = $value;
            }
            else if(property_exists($this, $params = Str::camel($params))) {
                $this->$params = $value;
            }
        }

        return $this;
    }

    /**
     * Set the default message properties.
     *
     * @param  string|array  $params
     * @param  mixed  $value
     * @return $this
     */
    public function defaults(array $default)
    {
        return (new Collection($default))
            ->each(function($value, $param) {
                if(is_null($this->$param)) {
                    $this->set($param, $value);
                }
            });
    }

}
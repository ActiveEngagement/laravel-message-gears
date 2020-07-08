<?php

namespace Actengage\LaravelMessageGears\Concerns;

use Illuminate\Support\Str;

trait HasAttributes {
    
    /**
     * Set the message properties.
     *
     * @param  string|array  $params
     * @param  mixed  $value
     * @return mixed
     */
    public function get($key, $default = null) {
        foreach([$key, Str::camel($key)] as $key) {
            if(property_exists($this, $key)) {
                return !is_null($this->$key) ? $this->$key : $default;
            }
        }
        
        return $default;
    }

    /**
     * Set the message properties.
     *
     * @param  string|array  $params
     * @param  mixed  $value
     * @return static
     */
    public function set($params, $value = null)
    {
        if(is_array($params)) {  
            foreach($params as $key => $value) {
                $this->set($key, $value);
            }
        }
        else {
            foreach([$params, Str::camel($params)] as $key) {
                if(method_exists($this, $key)) {
                    $this->$key($value);
                }
                else if(property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        return $this;
    }

}
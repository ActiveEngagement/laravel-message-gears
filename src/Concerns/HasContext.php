<?php

namespace Actengage\LaravelMessageGears\Concerns;

use Actengage\LaravelMessageGears\Context;

trait HasContext {
    
    /**
     * The email context.
     *
     * @var \Actengage\LaravelMessageGears\Context
     */
    public $context;

    /**
     * Get/merge the array into the notification context.
     *
     * @param  string|array  $context
     * @return static|\Actengage\LaravelMessageGears\Context
     */
    public function context($context = null, $value = null)
    {
        // If no context is set, instantiate and set the default context. 
        if(!$this->context) {
            $this->context = new Context;
        }

        // If the setter is null, get the current context
        if(is_null($context)) {
            return $this->context;
        }

        if($context instanceof Context) {
            $this->context = $context;
        }
        else if(is_array($context)) {
            $this->context->merge($context);
        }
        else {
            $this->context->set($context, $value);
        }

        return $this;
    }

}
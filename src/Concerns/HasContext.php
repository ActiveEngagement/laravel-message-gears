<?php

namespace Actengage\LaravelMessageGears\Concerns;

use Actengage\LaravelMessageGears\Context;

trait HasContext {
    
    /**
     * The email context.
     *
     * @var Actengage\LaravelMessageGears\Context
     */
    public $context;

    /**
     * Get/merge the array into the notification context.
     *
     * @param  string|array  $context
     * @return $this
     */
    public function context($context = null, $value = null)
    {
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
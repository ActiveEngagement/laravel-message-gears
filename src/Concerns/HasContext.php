<?php

namespace Actengage\MessageGears\Concerns;

use Actengage\MessageGears\Context;

trait HasContext
{
    /**
     * The template context data.
     *
     * @var Context
     */
    public Context $context;

    /**
     * Set the `context` property.
     *
     * @param \Actengage\MessageGears\Context|array $context
     * @return self
     */
    public function context(Context|array $context): self
    {
        if(is_array($context)) {
            $context = new Context($context);
        }

        $this->context = $context;

        return $this;
    }
}
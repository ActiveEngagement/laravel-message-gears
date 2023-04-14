<?php

namespace Actengage\MessageGears\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Actengage\MessageGears\Accelerator
 */
class Accelerator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Actengage\MessageGears\Accelerator::class;
    }
}

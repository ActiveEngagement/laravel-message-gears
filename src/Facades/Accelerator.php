<?php

declare(strict_types=1);

namespace Actengage\MessageGears\Facades;

use Illuminate\Support\Facades\Facade;
use Override;

/**
 * @see \Actengage\MessageGears\Accelerator
 */
class Accelerator extends Facade
{
    #[Override]
    protected static function getFacadeAccessor(): string
    {
        return \Actengage\MessageGears\Accelerator::class;
    }
}

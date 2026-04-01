<?php

declare(strict_types=1);

namespace Actengage\MessageGears\Facades;

use Illuminate\Support\Facades\Facade;
use Override;

/**
 * @see \Actengage\MessageGears\Cloud
 */
class Cloud extends Facade
{
    #[Override]
    protected static function getFacadeAccessor(): string
    {
        return \Actengage\MessageGears\Cloud::class;
    }
}

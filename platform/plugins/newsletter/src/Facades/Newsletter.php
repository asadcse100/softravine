<?php

namespace Botble\Newsletter\Facades;

use Botble\Newsletter\Contracts\Factory;
use Illuminate\Support\Facades\Facade;

class Newsletter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Factory::class;
    }
}

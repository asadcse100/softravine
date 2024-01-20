<?php

namespace Botble\Location\Facades;

use Botble\Location\Location as BaseLocation;
use Illuminate\Support\Facades\Facade;

class Location extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseLocation::class;
    }
}

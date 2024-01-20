<?php

namespace Botble\SocialLogin\Facades;

use Botble\SocialLogin\Supports\SocialService as SocialServiceSupport;
use Illuminate\Support\Facades\Facade;

class SocialService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SocialServiceSupport::class;
    }
}

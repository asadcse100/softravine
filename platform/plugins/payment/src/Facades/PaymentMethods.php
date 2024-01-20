<?php

namespace Botble\Payment\Facades;

use Botble\Payment\Supports\PaymentMethods as PaymentMethodsSupport;
use Illuminate\Support\Facades\Facade;

class PaymentMethods extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PaymentMethodsSupport::class;
    }
}

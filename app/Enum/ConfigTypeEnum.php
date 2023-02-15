<?php

namespace App\Enum;

use Nasyrov\Laravel\Enums\Enum;

class ConfigTypeEnum extends Enum
{
    const IDEAPAY_FEE_FIXED = 1;
    const IDEAPAY_FEE_PERCENTAGE = 2;
    const PHP_RATE_FOR_USD = 3;
    const REGISTRATION_SWITCH = 4;
    const ABSTRACT_SWITCH = 5;
    const VCC_OPENING_DATE = 6;
    const WORKSHOP_PAYMENT_SWITCH = 7;
}
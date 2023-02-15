<?php

namespace App\Enum;

use Nasyrov\Laravel\Enums\Enum;

class WorkshopEnum extends Enum
{
    const AESTHETIC = 1;
    const LASER = 2;
    const BOTH_AESTHETIC_AND_LASER = 3;
}
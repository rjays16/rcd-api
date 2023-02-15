<?php

namespace App\Enum;

use Nasyrov\Laravel\Enums\Enum;

class UserStatusEnum extends Enum
{
    const IMPORTED_PENDING = 1;
    const REGISTERED = 2;
    const DECLINED = 3;
}
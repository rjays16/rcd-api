<?php

namespace App\Enum;

use Nasyrov\Laravel\Enums\Enum;

class VIPEnum extends Enum
{
    const SPEAKER_SESSION = 1;
    const WORKSHOP_CHAIR = 2;
    const COUNCIL_OF_ADVISER = 3;
    const BOARD_OF_DIRECTOR = 4;
    const ORGANIZING_COMMITTEE = 5;
}
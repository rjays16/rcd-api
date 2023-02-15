<?php

namespace App\Enum;

use Nasyrov\Laravel\Enums\Enum;

class RegistrationTypeEnum extends Enum
{
    const SPEAKER_SESSION_WORKSHOP_CHAIR = 1;
    const INTERNATIONAL_LADS = 2;
    const INTERNATIONAL_NON_LADS = 3;
    const INTERNATIONAL_RESIDENT = 4;
    const LOCAL_PDS_MEMBER = 5;
    const LOCAL_PDS_RESIDENT = 6;
    const LOCAL_NON_PDS_MD = 7;
    const LOCAL_NON_PDS_RESIDENT_OF_APPLICANTS_INSTITUTIONS = 8;
    const INTERNATIONAL_LADS_OFFICER = 9;
    const LOCAL_PDS_COA_BOD_OC = 10;
}
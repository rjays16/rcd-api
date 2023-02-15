<?php

namespace App\Services;

use App\Models\Fee;

use App\Enum\FeeEnum;
use App\Enum\FeeTypeEnum;
use App\Enum\WorkshopEnum;
use App\Enum\RegistrationTypeEnum;

use AmrShawky\LaravelCurrency\Facade\Currency;

class FeeService {
    private $registration_type;
    private $is_interested_for_ws;
    private $ws_to_attend;
    protected $code = 200;

    public function __construct($registration_type, $is_interested_for_ws, $ws_to_attend) {
        $this->registration_type = $registration_type;
        $this->is_interested_for_ws = $is_interested_for_ws;
        $this->ws_to_attend = $ws_to_attend;
    }

    public function getRegistrationFee() {
        $data = array();
        $registration_fee_id = null;
        $data["fee"] = null;

        $data["fee"] = Fee::where('type', FeeTypeEnum::REGISTRATION)
            ->where('registration_type', $this->registration_type)
            ->first();

        if(is_null($data["fee"])) {
            $data["message"] = "Registration fee for this type has not been set yet.";
            $data["registration_type"] = $this->registration_type;
            $data["code"] = 404;
        }

        return $data;
    }

    public function getWorkshopFee() {
        $data = array();
        $data["is_interest_valid"] = false;
        // $data["message"] = "Something went wrong, please contact the site admin.";
        $data["fee"] = null;

        $data["code"] = $this->code;

        if($this->registration_type == RegistrationTypeEnum::LOCAL_NON_PDS_MD) {
            $data["message"] = "This type has no registration fee";
            $data["is_interest_valid"] = true;
            $data["registration_type"] = $this->registration_type;
            $data["workshop_type"] = $this->ws_to_attend;
        } else {
            if($this->is_interested_for_ws && !is_null($this->ws_to_attend)) {
                $data["is_interest_valid"] = true;

                $data["fee"] = Fee::where('type', FeeTypeEnum::WORKSHOP)
                    ->where('registration_type', $this->registration_type)
                    ->where('workshop_type', $this->ws_to_attend)
                    ->first();
                /*
                    If the interest is valid, and the workshop fee has not been set yet, return an error
                    Valid interest means that:
                        - the value for $validated["is_interested_for_ws"] is true AND
                        - the value for $validated["ws_to_attend"] is not null
                */
                if($data["is_interest_valid"] && is_null($data["fee"])) {
                    $data["message"] = "Workshop fee for this registration type has not been set yet.";
                    $data["registration_type"] = $this->registration_type;
                    $data["workshop_type"] = $this->ws_to_attend;
                    $data["code"] = 404;

                    return $data;
                }
            } else if(!$this->is_interested_for_ws) {
                $data["is_interest_valid"] = true;
                $data["fee"] = null;
            } else {
                $data["message"] = "Workshop interest is invalid.";
                $data["registration_type"] = $this->registration_type;
                $data["workshop_type"] = $this->ws_to_attend;
                $data["code"] = 400;
            }
        }

        return $data;
    }
}
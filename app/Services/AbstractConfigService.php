<?php

namespace App\Services;

use App\Models\Config;
use App\Enum\ConfigTypeEnum;

class AbstractConfigService {
    private $role;
    protected $code = 200;

    public function __construct() {
    }

    public function checkSubmissibleStatus() {
        $data = array();
        $data["is_submission_allowed"] = true;
        $data["message"] = "Abstracts can be submitted.";
        $data["code"] = $this->code;

        $switch = Config::where('type', ConfigTypeEnum::ABSTRACT_SWITCH)->first();
        if(!is_null($switch)) {
            $data["is_submission_allowed"] = $switch->value == "Yes" ? true : false;
        }

        if(!$data["is_submission_allowed"]) {
            $data["message"] = 'Abstract submission is disabled as of the moment.';
            $data["code"] = 400;
        }

        $data["is_submission_allowed"] = $data["code"] == 200 ? true : false;
        return $data;
    }
}
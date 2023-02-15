<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Config;
use App\Models\Banner;
use App\Models\WelcomeMessage;
use App\Models\PrivacyPolicy;
use App\Models\TermsAndConditions;
use App\Models\EventSchedule;

use App\Enum\ConfigTypeEnum;

use App\Http\Requests\IdeapayFee\Update;
use App\Http\Requests\Banner\Update as BannerUpdate;
use App\Http\Requests\WelcomeMessage\Update as WelcomeMessageUpdate;
use App\Http\Requests\PrivacyPolicy\Update as PrivacyPolicyUpdate;
use App\Http\Requests\TermsAndConditions\Update as TermsAndConditionsUpdate;
use App\Http\Requests\Registration\UpdateSettings as RegistrationSettings;
use App\Http\Requests\Abstracts\UpdateSettings as AbstractSettings;
use App\Http\Requests\Workshop\UpdateSettings as WorkshopSettings;

use Exception;
use DB;

class ConfigController extends Controller
{
    public function getIdeapayFee() {
        $ideapay_fee = Config::where('type', ConfigTypeEnum::IDEAPAY_FEE_FIXED)
            ->where('name', 'Fixed')
            ->first();

        if(!is_null($ideapay_fee)) {
            return response()->json($ideapay_fee);
        } else {
            return response()->json(['message' => 'Fee has not been set yet'], 404);
        }
    }

    public function updateIdeapayFee(Update $request) {
        $validated = $request->validated();

        $ideapay_fee = Config::where('type', ConfigTypeEnum::IDEAPAY_FEE_FIXED)
            ->where('name', 'Fixed')
            ->first();

        DB::beginTransaction();
        try {
            if(is_null($ideapay_fee)) {
                $ideapay_fee = new Config();
                $ideapay_fee->type = ConfigTypeEnum::IDEAPAY_FEE_FIXED;
                $ideapay_fee->name = 'Fixed';
            }

            $ideapay_fee->value = $validated["value"];
            $ideapay_fee->save();   

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated fee'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getIdeapayRate() {
        return response()->json([
            'rate' => Config::getIdeapayRate()
        ]);
    }

    public function getPHPRateForUSD() {
        return response()->json([
            'php_rate' => Config::getPHPRateForUSD()
        ]);
    }

    public function getBanner() {
        $banner = Banner::first();

        if(!is_null($banner)) {
            return response()->json($banner);
        } else {
            return response()->json(['message' => 'Banner info has not been set yet'], 404);
        }
    }

    public function updateBanner(BannerUpdate $request) {
        $validated = $request->validated();

        $banner = Banner::first();

        DB::beginTransaction();
        try {
            if(is_null($banner)) {
                $banner = new Banner();
            }

            if($request->hasFile('photo')) {
                $fileExtension = $request->file('photo')->getClientOriginalName();
                $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                $extension = $request->file('photo')->getClientOriginalExtension();
                $fileStore = $file.'_'.time().'.'.$extension;
                $request->file('photo')->storeAs('public/images/banners', $fileStore);
                $validated["photo"] = config('settings.APP_URL')."/storage/images/banners/".$fileStore;
            }

            $banner->fill($validated);
            $banner->save();  

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated banner info'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getWelcomeMessage() {
        $message = WelcomeMessage::first();

        if(!is_null($message)) {
            return response()->json($message);
        } else {
            return response()->json(['message' => 'Welcome Message info has not been set yet'], 404);
        }
    }

    public function updateWelcomeMessage(WelcomeMessageUpdate $request) {
        $validated = $request->validated();

        $message = WelcomeMessage::first();

        DB::beginTransaction();
        try {
            if(is_null($message)) {
                $message = new WelcomeMessage();
            }

            $message->fill($validated);
            $message->save();  

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated Welcome Message info'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getPrivacyPolicy() {
        $privacy_policy = PrivacyPolicy::first();

        if(!is_null($privacy_policy)) {
            return response()->json($privacy_policy);
        } else {
            return response()->json(['message' => 'Privacy Policy info has not been set yet'], 404);
        }
    }

    public function updatePrivacyPolicy(PrivacyPolicyUpdate $request) {
        $validated = $request->validated();

        $privacy_policy = PrivacyPolicy::first();

        DB::beginTransaction();
        try {
            if(is_null($privacy_policy)) {
                $privacy_policy = new PrivacyPolicy();
            }

            if($request->hasFile('banner')) {
                $fileExtension = $request->file('banner')->getClientOriginalName();
                $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                $extension = $request->file('banner')->getClientOriginalExtension();
                $fileStore = $file.'_'.time().'.'.$extension;
                $request->file('banner')->storeAs('public/images/banners', $fileStore);
                $validated["banner"] = config('settings.APP_URL')."/storage/images/banners/".$fileStore;
            }

            $privacy_policy->fill($validated);
            $privacy_policy->save();  

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated Privacy Policy info'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getTerms() {
        $terms = TermsAndConditions::first();

        if(!is_null($terms)) {
            return response()->json($terms);
        } else {
            return response()->json(['message' => 'Terms and Condtions info has not been set yet'], 404);
        }
    }

    public function updateTerms(TermsAndConditionsUpdate $request) {
        $validated = $request->validated();

        $terms = TermsAndConditions::first();

        DB::beginTransaction();
        try {
            if(is_null($terms)) {
                $terms = new TermsAndConditions();
            }

            if($request->hasFile('banner')) {
                $fileExtension = $request->file('banner')->getClientOriginalName();
                $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                $extension = $request->file('banner')->getClientOriginalExtension();
                $fileStore = $file.'_'.time().'.'.$extension;
                $request->file('banner')->storeAs('public/images/banners', $fileStore);
                $validated["banner"] = config('settings.APP_URL')."/storage/images/banners/".$fileStore;
            }

            $terms->fill($validated);
            $terms->save();  

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated Terms and Condtions info'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getEventSchedule() {
        $schedule = EventSchedule::first();

        if(!is_null($schedule)) {
            return response()->json($schedule);
        } else {
            return response()->json(['message' => 'Event Schedule has not been set yet'], 404);
        }
    }

    public function updateEventSchedule(Request $request) {
        $schedule = EventSchedule::first();

        DB::beginTransaction();
        try {
            if(is_null($schedule)) {
                $schedule = new EventSchedule();
            }

            if($request->hasFile('photo')) {
                $fileExtension = $request->file('photo')->getClientOriginalName();
                $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                $extension = $request->file('photo')->getClientOriginalExtension();
                $fileStore = $file.'_'.time().'.'.$extension;
                $request->file('photo')->storeAs('public/images/event_schedule', $fileStore);
                $schedule->photo = config('settings.APP_URL')."/storage/images/event_schedule/".$fileStore;
                $schedule->save();
                
                DB::commit();
                return response()->json([
                    'message' => 'Successfully updated Event Schedule'
                ]);
            } else {
                return response()->json([
                    'message' => 'No photo was selected'
                ], 400);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getRegistrationSwitch() {
        $switch = Config::where('type', ConfigTypeEnum::REGISTRATION_SWITCH)->first();

        if(!is_null($switch)) {
            $value = $switch->value == "Yes" ? true : false;
            return response()->json($value);
        } else {
            return response()->json(['message' => 'Registration setting has not been set yet'], 404);
        }
    }

    public function updateRegistrationSwitch(RegistrationSettings $request) {
        $validated = $request->validated();

        $switch = Config::where('type', ConfigTypeEnum::REGISTRATION_SWITCH)->first();

        DB::beginTransaction();
        try {
            if(is_null($switch)) {
                $switch = new Config();
                $switch->type = ConfigTypeEnum::REGISTRATION_SWITCH;
                $switch->name = 'Enabled';
            }

            $switch->value = $validated["value"] == true ? "Yes" : "No";
            $switch->save();   

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated registration setting',
                'status' => $switch->value == "Yes" ? "Enabled" : "Disabled"
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAbstactSwitch() {
        $switch = Config::where('type', ConfigTypeEnum::ABSTRACT_SWITCH)->first();

        if(!is_null($switch)) {
            $value = $switch->value == "Yes" ? true : false;
            return response()->json($value);
        } else {
            return response()->json(['message' => 'Abstract submission switch setting has not been set yet'], 404);
        }
    }

    public function updateAbstractSwitch(AbstractSettings $request) {
        $validated = $request->validated();

        $switch = Config::where('type', ConfigTypeEnum::ABSTRACT_SWITCH)->first();

        DB::beginTransaction();
        try {
            if(is_null($switch)) {
                $switch = new Config();
                $switch->type = ConfigTypeEnum::ABSTRACT_SWITCH;
                $switch->name = 'Enabled';
            }

            $switch->value = $validated["value"] == true ? "Yes" : "No";
            $switch->save();   

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated the abstract submission switch setting',
                'status' => $switch->value == "Yes" ? "Enabled" : "Disabled"
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getWorkshopPaymentSwitch() {
        $switch = Config::where('type', ConfigTypeEnum::WORKSHOP_PAYMENT_SWITCH)->first();

        if(!is_null($switch)) {
            $value = $switch->value == "Yes" ? true : false;
            return response()->json($value);
        } else {
            return response()->json(['message' => 'Workshop payment switch setting has not been set yet'], 404);
        }
    }

    public function updateWorkshopPaymentSwitch(WorkshopSettings $request) {
        $validated = $request->validated();

        $switch = Config::where('type', ConfigTypeEnum::WORKSHOP_PAYMENT_SWITCH)->first();

        DB::beginTransaction();
        try {
            if(is_null($switch)) {
                $switch = new Config();
                $switch->type = ConfigTypeEnum::WORKSHOP_PAYMENT_SWITCH;
                $switch->name = 'Enabled';
            }

            $switch->value = $validated["value"] == true ? "Yes" : "No";
            $switch->save();   

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated the workshop payment switch setting',
                'status' => $switch->value == "Yes" ? "Enabled" : "Disabled"
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
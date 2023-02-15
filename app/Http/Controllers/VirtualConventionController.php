<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Config;
use App\Enum\ConfigTypeEnum;

use App\Http\Requests\VCC\OpeningDate\Update;

use Carbon\Carbon;

use Exception;
use DB;

class VirtualConventionController extends Controller
{
    public function getOpeningDate() {
        $date_now = Carbon::today()->toDateString();
        $opening_date = Config::where('type', ConfigTypeEnum::VCC_OPENING_DATE)
            ->where('name', 'Date')
            ->first();

        if(!is_null($opening_date)) {
            return response()->json([
                'opening_date' => $opening_date->value,
                'date_now' => $date_now,
                'is_vcc_open' => Carbon::parse($date_now)->gte(Carbon::parse($opening_date->value))
            ]);
        } else {
            return response()->json(['message' => 'The opening date for VCC has not been set yet.'], 404);
        }
    }

    public function updateOpeningDate(Update $request) {
        $validated = $request->validated();

        $opening_date = Config::where('type', ConfigTypeEnum::VCC_OPENING_DATE)
            ->where('name', 'Date')
            ->first();

        DB::beginTransaction();
        try {
            if(is_null($opening_date)) {
                $opening_date = new Config();
                $opening_date->type = ConfigTypeEnum::VCC_OPENING_DATE;
                $opening_date->name = 'Date';
            }

            $opening_date->value = $validated["value"];
            $opening_date->save();

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated the opening date for VCC.',
                'opening_date' => $opening_date->value
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

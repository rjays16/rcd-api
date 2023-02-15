<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Sponsor;
use App\Models\SponsorType;
use App\Models\SponsorAsset;

use App\Enum\UserStatusEnum;
use App\Enum\RoleEnum;
use App\Enum\SponsorTypeEnum;

use App\Http\Requests\Sponsor\Create;

use Exception;
use DB;

class SponsorController extends Controller
{
    public function getSponsors() {
        $sponsors = Sponsor::with(['user', 'type'])->get();

        if($sponsors->isNotEmpty()) {
            return response()->json($sponsors);
        } else {
            return response()->json(['message' => 'Sponsors not found.'], 404);
        }
    }

    public function getSponsorsByType() {
        $platinum_sponsors = Sponsor::platinum()->notIndustryLectureOnly()->with(['user', 'type'])->get(); 
        $gold_sponsors = Sponsor::gold()->notIndustryLectureOnly()->with(['user', 'type'])->get(); 
        $silver_sponsors = Sponsor::silver()->notIndustryLectureOnly()->with(['user', 'type'])->get(); 
        $bronze_sponsors = Sponsor::bronze()->notIndustryLectureOnly()->with(['user', 'type'])->get(); 

        return response()->json([
            'platinum_sponsors' => $platinum_sponsors,
            'gold_sponsors' => $gold_sponsors,
            'silver_sponsors' => $silver_sponsors,
            'bronze_sponsors' => $bronze_sponsors,
        ]); 
    }

    public function getSponsorByID($id) {
        $sponsor = Sponsor::where('id', $id)
            ->with(['user', 'type', 'booth_design'])
            ->first();

        if(!is_null($sponsor)) {
            return response()->json([
                'sponsor' => $sponsor,
                'videos' => $sponsor->assets()->video()->get(),
                'brochures' => $sponsor->assets()->brochure()->get(),
                'product_catalogues' => $sponsor->assets()->catalogue()->get()
            ]);
        } else {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }
    }

    public function getSponsorBySlug($slug) {
        $sponsor = Sponsor::where('slug', $slug)        
            ->notIndustryLectureOnly()
            ->with(['user', 'type', 'booth_design'])
            ->first();

        if(!is_null($sponsor)) {
            return response()->json([
                'sponsor' => $sponsor,
                'videos' => $sponsor->assets()->video()->orderBy('position_number', 'asc')->get(),
                'brochures' => $sponsor->assets()->brochure()->orderBy('position_number', 'asc')->get(),
                'product_catalogues' => $sponsor->assets()->catalogue()->first(),
            ]);
        } else {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }
    }

    public function getSponsorTypes() {
        $types = SponsorType::get();

        if($types->isNotEmpty()) {
            return response()->json($types);
        } else {
            return response()->json(['message' => 'Sponsor types not found.'], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $other_user = User::where('email', $validated["email"])->first();
            if(is_null($other_user)) {
                $validated["first_name"] = $validated["name"];
                $validated["role"] = RoleEnum::SPONSOR;
                $validated["password"] = Hash::make(config('settings.DEFAULT_SPONSOR_PASSWORD'));
                $validated["status"] = UserStatusEnum::REGISTERED;
                $user = User::create($validated);

                if($request->hasFile('logo')) {
                    $fileExtension = $request->file('logo')->getClientOriginalName();
                    $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                    $extension = $request->file('logo')->getClientOriginalExtension();
                    $fileStore = $file.'_'.time().'.'.$extension;
                    $request->file('logo')->storeAs('/images/logos', $fileStore);
                    $validated["logo"] = config('settings.APP_URL')."/storage/images/logos/".$fileStore;
                }

                $validated["user_id"] = $user->id;
                Sponsor::create($validated);

                DB::commit();
                return response()->json([
                    'message' => 'Successfully created sponsor.'
                ]);
            } else {
                return response()->json([
                    'message' => 'This email has already been taken.'
                ], 400);
            }
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Create $request, $id) {
        $validated = $request->validated();

        $sponsor = Sponsor::where('id', $id)->first();

        if(is_null($sponsor)) {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }

        DB::beginTransaction();
        try {
            $user = $sponsor->user;

            $other_user = User::where('email', $validated["email"])->where('id', '!=', $user->id)->first();
            if(is_null($other_user)) {
                $validated["first_name"] = $validated["name"];
                $user->update($validated);
                $user->save();

                if($request->hasFile('logo')) {
                    $fileExtension = $request->file('logo')->getClientOriginalName();
                    $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                    $extension = $request->file('logo')->getClientOriginalExtension();
                    $fileStore = $file.'_'.time().'.'.$extension;
                    $request->file('logo')->storeAs('/images/logos', $fileStore);
                    $validated["logo"] = config('settings.APP_URL')."/storage/images/logos/".$fileStore;
                }

                $sponsor->update($validated);

                DB::commit();
                return response()->json([
                    'message' => 'Successfully updated sponsor.'
                ]);
            } else {
                return response()->json([
                    'message' => 'This email has already been taken.'
                ], 400);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
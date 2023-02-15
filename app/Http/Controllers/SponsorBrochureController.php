<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Sponsor;
use App\Models\SponsorAsset;

use App\Enum\SponsorAssetTypeEnum;

use App\Http\Requests\Sponsor\Brochure\Create;

use Exception;
use DB;

class SponsorBrochureController extends Controller
{
    public function getBrochures($id) {
        $sponsor = Sponsor::where('id', $id)->first();
        if(!is_null($sponsor)) {
            $brochures = SponsorAsset::where('sponsor_id', $id)->brochure()->get();

            if($brochures->isNotEmpty()) {
                return response()->json($brochures);
            } else {
                return response()->json([
                    'message' => 'No brochures yet.'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Sponsor not found.'
            ], 404);
        }
    }

    public function getBrochure($id, $brochure_id) {
        $sponsor = Sponsor::where('id', $id)->first();
        if(!is_null($sponsor)) {
            $brochure = SponsorAsset::where('sponsor_id', $id)->where('id', $brochure_id)->brochure()->first();

            if(!is_null($brochure)) {
                return response()->json($brochure);
            } else {
                return response()->json([
                    'message' => 'Brochure was not found.'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Sponsor not found.'
            ], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $sponsor = Sponsor::where('id', $validated["sponsor_id"])->first();
            $num_sponsor_brochures = $sponsor->assets()->brochure()->count();

            if($num_sponsor_brochures < $sponsor->type->max_brochures) {
                $validated["type"] = SponsorAssetTypeEnum::BROCHURE;
                SponsorAsset::create($validated);
                DB::commit();
                return response()->json(['message' => 'Successfully saved brochure.']);
            } else {
                return response()->json([
                    'message' => 'You have already met the maximum limit for brochures.',
                    'sponsor_type' => $sponsor->type,
                    'max_brochures' => $sponsor->type->max_brochures
                ], 400);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Create $request, $brochure_id) {
        $validated = $request->validated();

        $brochure = SponsorAsset::where('id', $brochure_id)->brochure()->first();
        if(is_null($brochure)) {
            return response()->json(['message' => 'This brochure was not found.'], 404);
        }

        DB::beginTransaction();
        try {
            $brochure->update($validated);
            DB::commit();
            return response()->json([
                'message' => 'Successfully updated brochure.'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function uploadBrochure(Request $request, $id) {
        DB::beginTransaction();
        try {
            $sponsor = Sponsor::where('id', $id)->first();
            if(!is_null($sponsor)) {
                $num_sponsor_brochures = $sponsor->assets()->brochure()->count();

                if($num_sponsor_brochures < $sponsor->type->max_brochures) {
                    if($request->hasFile('brochures')) {
                        foreach($request->file('brochures') as $key => $brochure) {
                            $fileExtension = $brochure->getClientOriginalName();
                            $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                            $extension = $brochure->getClientOriginalExtension();
                            $fileStore = $file.'_'.time().$key.'.'.$extension;
                            $path = $brochure->storeAs('public/brochures', $fileStore);

                            SponsorAsset::create([
                                'sponsor_id' => $id,
                                'type' => SponsorAssetTypeEnum::BROCHURE,
                                'name' => $fileStore,
                                'url' => config('settings.APP_URL')."/storage/brochures/".$fileStore
                            ]);
                        }

                        DB::commit();
                        return response()->json([
                            'message' => 'Successfully uploaded brochure/s.'
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'No file was uploaded.',
                            'sponsor_type' => $sponsor->type,
                            'max_brochures' => $sponsor->type->max_brochures
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'message' => 'You have already met the maximum limit for brochures.',
                        'sponsor_type' => $sponsor->type,
                        'max_brochures' => $sponsor->type->max_brochures
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'Sponsor not found.'
                ], 404);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($brochure_id) {
        $asset = SponsorAsset::where('id', $brochure_id)->brochure()->first();

        if(!is_null($asset)) {
            $asset->delete();
            return response()->json(['message' => 'Sponsor asset deleted.']);
        } else {
            return response()->json(['message' => 'Sponsor asset not found.'], 404);
        }
    }
}
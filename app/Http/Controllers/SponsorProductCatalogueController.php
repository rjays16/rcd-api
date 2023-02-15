<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Sponsor;
use App\Models\SponsorAsset;

use App\Enum\SponsorAssetTypeEnum;

use App\Http\Requests\Sponsor\ProductCatalogue\Create;

use Exception;
use DB;

class SponsorProductCatalogueController extends Controller
{
    public function getProductCatalogues($id) {
        $sponsor = Sponsor::where('id', $id)->first();
        if(!is_null($sponsor)) {
            $product_catalogues = SponsorAsset::where('sponsor_id', $id)->catalogue()->get();

            if($product_catalogues->isNotEmpty()) {
                return response()->json($product_catalogues);
            } else {
                return response()->json([
                    'message' => 'No product catalogue yet.'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Sponsor not found.'
            ], 404);
        }
    }

    public function getProductCatalogue($id, $product_catalogue_id) {
        $sponsor = Sponsor::where('id', $id)->first();
        if(!is_null($sponsor)) {
            $product_catalogue = SponsorAsset::where('sponsor_id', $id)->where('id', $product_catalogue_id)->catalogue()->first();

            if(!is_null($product_catalogue)) {
                return response()->json($product_catalogue);
            } else {
                return response()->json([
                    'message' => 'This product catalogue was not found.'
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
            $num_sponsor_product_catalogue = $sponsor->assets()->catalogue()->count();

            if($num_sponsor_product_catalogue < $sponsor->type->max_catalog) {
                $validated["type"] = SponsorAssetTypeEnum::PRODUCT_CATALOGUE;
                SponsorAsset::create($validated);
                DB::commit();
                return response()->json(['message' => 'Successfully saved product catalogue.']);
            } else {
                return response()->json([
                    'message' => 'You have already met the maximum limit for product catalogues.',
                    'sponsor_type' => $sponsor->type,
                    'max_catalog' => $sponsor->type->max_catalog
                ], 400);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Create $request, $product_catalogue_id) {
        $validated = $request->validated();

        $product_catalogue = SponsorAsset::where('id', $product_catalogue_id)->catalogue()->first();
        if(is_null($product_catalogue)) {
            return response()->json(['message' => 'This product catalogue was not found.'], 404);
        }

        DB::beginTransaction();
        try {
            $product_catalogue->update($validated);
            DB::commit();
            return response()->json([
                'message' => 'Successfully updated product catalogue.'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function uploadProductCatalogue(Request $request, $id) {
        DB::beginTransaction();
        try {
            $sponsor = Sponsor::where('id', $id)->first();
            if(!is_null($sponsor)) {
                $num_sponsor_product_catalogue = $sponsor->assets()->catalogue()->count();

                if($product_catalogue < $sponsor->type->max_catalog) {
                    if($request->hasFile('product_catalogue')) {
                        $fileExtension = $request->file('logo')->getClientOriginalName();
                        $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                        $extension = $request->file('logo')->getClientOriginalExtension();
                        $fileStore = $file.'_'.time().'.'.$extension;
                        $request->file('logo')->storeAs('public/product_catalogue', $fileStore);
                        $validated["logo"] = config('settings.APP_URL')."/storage/product_catalogue/".$fileStore;

                        SponsorAsset::create([
                            'sponsor_id' => $id,
                            'type' => SponsorAssetTypeEnum::PRODUCT_CATALOGUE,
                            'name' => $fileStore,
                            'url' => config('settings.APP_URL')."/storage/product_catalogue/".$fileStore
                        ]);

                        DB::commit();
                        return response()->json([
                            'message' => 'Successfully uploaded product catalogue.'
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'No file was uploaded.',
                            'sponsor_type' => $sponsor->type,
                            'max_catalog' => $sponsor->type->max_catalog
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'message' => 'You have already met the maximum limit for product catalogues.',
                        'sponsor_type' => $sponsor->type,
                        'max_catalog' => $sponsor->type->max_catalog
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'Sponsor not found'
                ], 404);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($product_catalogue_id) {
        $asset = SponsorAsset::where('id', $product_catalogue_id)->catalogue()->first();

        if(!is_null($asset)) {
            $asset->delete();
            return response()->json(['message' => 'Sponsor asset deleted.']);
        } else {
            return response()->json(['message' => 'Sponsor asset not found.'], 404);
        }
    }
}
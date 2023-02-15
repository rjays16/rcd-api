<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Sponsor;
use App\Models\SponsorAsset;

use App\Enum\SponsorAssetTypeEnum;

use App\Http\Requests\Sponsor\Video\Create;

use Exception;
use DB;

class SponsorVideoController extends Controller
{
    public function getVideos($id) {
        $sponsor = Sponsor::where('id', $id)->first();
        if(!is_null($sponsor)) {
            $videos = SponsorAsset::where('sponsor_id', $id)->video()->get();

            if($videos->isNotEmpty()) {
                return response()->json($videos);
            } else {
                return response()->json([
                    'message' => 'No videos yet.'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Sponsor not found.'
            ], 404);
        }
    }

    public function getVideo($id, $video_id) {
        $sponsor = Sponsor::where('id', $id)->first();
        if(!is_null($sponsor)) {
            $video = SponsorAsset::where('sponsor_id', $id)->where('id', $video_id)->video()->first();

            if(!is_null($video)) {
                return response()->json($video);
            } else {
                return response()->json([
                    'message' => 'Video was not found.'
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
            $num_sponsor_videos = $sponsor->assets()->video()->count();

            if($num_sponsor_videos < $sponsor->type->max_videos) {
                $validated["type"] = SponsorAssetTypeEnum::VIDEO;
                SponsorAsset::create($validated);
                DB::commit();
                return response()->json(['message' => 'Successfully saved video.']);
            } else {
                return response()->json([
                    'message' => 'You have already met the maximum limit for videos.',
                    'sponsor_type' => $sponsor->type,
                    'max_videos' => $sponsor->type->max_videos
                ], 400);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Create $request, $video_id) {
        $validated = $request->validated();

        $video = SponsorAsset::where('id', $video_id)->video()->first();
        if(is_null($video)) {
            return response()->json(['message' => 'This video was not found.'], 404);
        }

        DB::beginTransaction();
        try {
            $video->update($validated);
            DB::commit();
            return response()->json([
                'message' => 'Successfully updated video.'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function uploadVideo(Request $request, $id) {
        DB::beginTransaction();
        try {
            $sponsor = Sponsor::where('id', $id)->first();
            if(!is_null($sponsor)) {
                $num_sponsor_videos = $sponsor->assets()->video()->count();

                if($num_sponsor_videos < $sponsor->type->max_videos) {
                    if($request->exists('videos')) {
                        foreach($request->file('videos') as $key => $video) {
                            $fileExtension = $video->getClientOriginalName();
                            $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                            $extension = $video->getClientOriginalExtension();
                            $fileStore = $file.'_'.time().$key.'.'.$extension;
                            $path = $video->storeAs('public/videos', $fileStore);

                            SponsorAsset::create([
                                'sponsor_id' => $id,
                                'type' => SponsorAssetTypeEnum::VIDEO,
                                'name' => $fileStore,
                                'url' => config('settings.APP_URL')."/storage/videos/".$fileStore
                            ]);
                        }

                        DB::commit();
                        return response()->json([
                            'message' => 'Successfully uploaded video/s.'
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'No file was uploaded.',
                            'sponsor_type' => $sponsor->type,
                            'max_videos' => $sponsor->type->max_videos
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'message' => 'You have already met the maximum limit for videos.',
                        'sponsor_type' => $sponsor->type,
                        'max_videos' => $sponsor->type->max_videos
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

    public function delete($video_id) {
        $asset = SponsorAsset::where('id', $video_id)->video()->first();

        if(!is_null($asset)) {
            $asset->delete();
            return response()->json(['message' => 'Sponsor asset deleted.']);
        } else {
            return response()->json(['message' => 'Sponsor asset not found.'], 404);
        }
    }
}
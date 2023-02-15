<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Abstracts;
use App\Models\AbstractAuthor;
use App\Models\User;
use App\Models\ConventionMember;
use App\Models\Category;

use App\Enum\RegistrationTypeEnum;
use App\Enum\OrderStatusEnum;
use App\Enum\AbstractTypeEnum;
use App\Enum\RoleEnum;

use App\Http\Requests\Abstracts\Create;
use App\Http\Requests\Abstracts\CreateFromAdmin;
use App\Http\Requests\Abstracts\Update;

use App\Services\AbstractConfigService;

use Exception;
use DB;

class AbstractController extends Controller
{
    public function create(Create $request) {
        $validated = $request->validated();

        $abstract_config_service = new AbstractConfigService();
        $abstract_config = $abstract_config_service->checkSubmissibleStatus();

        if(!$abstract_config["is_submission_allowed"]) {
            return response()->json([
                'message' => $abstract_config["message"],
            ], $abstract_config["code"]);
        }

        $user_id = Auth::user()->id;

        $delegate_types = [
            RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR,
            RegistrationTypeEnum::INTERNATIONAL_LADS,
            RegistrationTypeEnum::INTERNATIONAL_NON_LADS,
            RegistrationTypeEnum::INTERNATIONAL_RESIDENT,
            RegistrationTypeEnum::LOCAL_PDS_MEMBER,
            RegistrationTypeEnum::LOCAL_PDS_RESIDENT,
            RegistrationTypeEnum::LOCAL_NON_PDS_MD,
            RegistrationTypeEnum::LOCAL_NON_PDS_RESIDENT_OF_APPLICANTS_INSTITUTIONS,
            RegistrationTypeEnum::INTERNATIONAL_LADS_OFFICER,
            RegistrationTypeEnum::LOCAL_PDS_COA_BOD_OC
        ];

        $delegate = ConventionMember::with(['user'])
            ->whereIn('type', $delegate_types)
            ->where('user_id', $user_id)
            ->whereHas('payment', function ($query) {
                $query->whereHas('order', function($sub_query) {
					$sub_query->where('status', OrderStatusEnum::COMPLETED);
				});
            })
            ->first();

        if(is_null($delegate)) {
            return response()->json(['message' => 'You are not allowed to submit an abstract.'], 404);
        }

        DB::beginTransaction();
        try {
            $validated['convention_member_id']= $delegate->id;
            $abstract = Abstracts::create($validated);

            foreach ($validated['authors'] as $author){
                $author['abstract_id'] = $abstract->id;
                $abstract_authotr = AbstractAuthor::create($author);
            }

            DB::commit();
            Abstracts::sendThankYouEmail($abstract->member->user, $abstract);

            return response()->json(['message' => 'Successfully submitted your abstract.']);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function createFromAdmin(CreateFromAdmin $request) {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $abstract = Abstracts::create($validated);
            foreach ($validated['authors'] as $author){
                $author['abstract_id'] = $abstract->id;
                $abstract_authotr = AbstractAuthor::create($author);
            }
            DB::commit();
            return response()->json(['message' => 'Successfully submitted your abstract.']);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Update $request, $id) {
        $validated = $request->validated();

        $abstract = Abstracts::where('id', $id)->first();

        if(is_null($abstract)) {
            return response()->json(['message' => 'The Abstract submission was not found'], 404);
        }

        DB::beginTransaction();
        try {
            if($request->exists("abstract_type") && $validated["is_finalist"]) {
                $validated["abstract_type"] = $request["abstract_type"];
            }

            $abstract->update($validated);

            DB::commit();

            return response()->json([
                'message' => 'Successfully updated abstract'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getUserAbstracts(Request $request) {
        $abstracts = Abstracts::where('convention_member_id', $request->member_id)
            ->with(['member'])
            ->get();

        if($abstracts->isNotEmpty()) {
            return response()->json($abstracts);
        } else {
            return response()->json(['message' => 'This member has no abstract submissions'], 404);
        }
    }

    public function getAbstract($id) {
        $abstract = Abstracts::where('id',$id)->with(['authors'])->first();

        if(!is_null($abstract)) {
            return response()->json($abstract);
        } else {
            return response()->json(['message' => 'This abstract submission does not exist.'], 404);
        }
    }

    public function search_Authors(Request $request) {
        $abstract_types = [
            AbstractTypeEnum::E_POSTER,
            AbstractTypeEnum::FREE_PAPER,
        ];

        $abstract = Abstracts::whereIn('abstract_type', $abstract_types)
            ->whereHas('authors')
            ->join('abstract_authors', 'abstract_authors.abstract_id', '=', 'abstracts.id');

            if($request->exists('is_search') && $request->is_search) {
                $abstract = $abstract->whereHas('authors', function ($query) use ($request) {
                    $query->where('title', 'like', "%$request->keyword%");
                });
            }elseif (!$request->show_all){
                $abstract = $abstract->limit(30);
            }
            $abstract = $abstract->with(['member.user', 'authors' ])
                ->get();

        if($abstract->isNotEmpty()) {
            return response()->json($abstract);
        } else {
            return response()->json(['message' => 'No title were found'], 404);
        }
    }

    public function getEPosterAbstracts() {
        $abstracts = Abstracts::where([['abstract_type', AbstractTypeEnum::E_POSTER], ['deleted_at', NULL]])
            ->with(['member.user', 'authors' ])
            ->orderBy('id', 'desc')
            ->get();

        if($abstracts->isNotEmpty()) {
            return response()->json($abstracts);
        } else {
            return response()->json(['message' => 'No abstract submissions found'], 404);
        }
    }

    public function getEPosterAbstractFinalists(Request $request) {
        $category = Category::where('id', $request->category_id)->first();

        if(!is_null($category)) {
            $abstracts = Abstracts::where([['abstract_type', AbstractTypeEnum::E_POSTER], ['is_finalist', 1], ['abstract_category', $category->category_value]])
                ->with(['member.user', 'authors'])
                ->orderBy('title', 'ASC')
                ->get();

            if($abstracts->isNotEmpty()) {
                return response()->json($abstracts);
            } else {
                return response()->json(['message' => 'No abstract submissions found.'], 404);
            }
        } else {
            return response()->json(['message' => 'This category was not found.'], 404);
        }
    }

    public function getFreepaperAbstractFinalists(Request $request) {
        $category = Category::where('id', $request->category_id)->first();

        if(!is_null($category)) {
            $abstracts = Abstracts::where([['abstract_type', AbstractTypeEnum::FREE_PAPER], ['is_finalist', 1], ['abstract_category', $category->category_value]])
                ->with(['member.user', 'authors'])
                ->orderBy('title', 'ASC')
                ->get();

            if($abstracts->isNotEmpty()) {
                return response()->json($abstracts);
            } else {
                return response()->json(['message' => 'No abstract submissions found.'], 404);
            }
        } else {
            return response()->json(['message' => 'This category was not found.'], 404);
        }
    }

    public function getFreePaperAbstracts() {
        $abstracts = Abstracts::where([['abstract_type', AbstractTypeEnum::FREE_PAPER], ['deleted_at', NULL]])
            ->with(['member.user', 'authors'])
            ->orderBy('id', 'desc')
            ->get();

        if($abstracts->isNotEmpty()) {
            return response()->json($abstracts);
        } else {
            return response()->json(['message' => 'No abstract submissions found'], 404);
        }
    }

    public function getEPosterAbstractForDefault(){
        $abstracts = Abstracts::select(DB::raw('DISTINCT(abstract_category)'))
            ->where([['abstract_type', AbstractTypeEnum::E_POSTER], ['is_finalist', 0], ['deleted_at', NULL]])
            ->with(['member.user', 'authors' ])
            ->get();

        if($abstracts->isNotEmpty()) {
            return response()->json($abstracts);
        } else {
            return response()->json(['message' => 'No abstract submissions found'], 404);
        }
    }

    public function getList(){
        $abstracts = Abstracts::where([['is_finalist', 0], ['deleted_at', NULL]])
            ->with(['member.user', 'authors' ])
            ->orderBy('id', 'desc')
            ->get();

        if($abstracts->isNotEmpty()) {
            return response()->json($abstracts);
        } else {
            return response()->json(['message' => 'No abstract submissions found'], 404);
        }
    }
    public function getFreepaperAbstractForDefault() {
        $abstracts = Abstracts::select(DB::raw('DISTINCT(abstract_category)'))
            ->where([['abstract_type', AbstractTypeEnum::FREE_PAPER], ['is_finalist', 0], ['deleted_at', NULL]])
            ->with(['member.user', 'authors' ])
            ->get();

        if($abstracts->isNotEmpty()) {
            return response()->json($abstracts);
        } else {
            return response()->json(['message' => 'No abstract submissions found'], 404);
        }
    }

    public function delete($id) {
        $abstract = Abstracts::where('id', $id)->first();

        if(!is_null($abstract)) {
            $abstract_authors = $abstract->authors;
            if(!empty($abstract_authors)) {
                foreach($abstract_authors as $abstract_author) {
                    $abstract_author->delete();
                }
            }
            $abstract->delete();
            return response()->json(['message' => 'Successfully deleted abstract.']);
        } else {
            return response()->json(['message' => 'The abstract submission was not found.'], 404);
        }
    }

    public function resendThankYouEmail($id) {
        $abstract_submission = Abstracts::where('id', $id)->first();
        if(!is_null($abstract_submission)) {
            $status = Abstracts::sendThankYouEmail($abstract_submission->member->user, $abstract_submission);

            if($status == 200) {
                return response()->json([
                    'message' => 'Successfully resent the email receipt of the abstract content to the Delegate.',
                    'email' => $abstract_submission->member->user->email,
                    'status' => $status,
                ]);
            } else {
                return response()->json([
                    'message' => 'Unable to resent the thank you email.',
                    'status' => $status
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'The abstract submission was not found.'
            ], 404);
        }
    }
}

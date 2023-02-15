<?php

namespace App\Http\Controllers;

use App\Models\Abstracts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\ConventionMember;
use App\Models\RegistrationType;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Fee;
use App\Models\ComplimentaryPass;
use App\Models\Sponsor;

use App\Enum\RoleEnum;
use App\Enum\UserStatusEnum;
use App\Enum\PaymentMethodEnum;
use App\Enum\RegistrationTypeEnum;
use App\Enum\FeeEnum;

use App\Services\OrderService;

use App\Http\Requests\ConventionMember\Create;

use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;

use Exception;
use DB;

class ConventionMemberController extends Controller
{
    public function getPending() {
        $members = ConventionMember::whereHas('user', function ($query) {
            $query->where('status', UserStatusEnum::IMPORTED_PENDING);
        })->with(['user', 'registration_type'])
        ->orderBy('updated_at', 'desc')
        ->get();

        if($members->isNotEmpty()) {
            return response()->json($members);
        } else {
            return response()->json(['message' => 'No pending members were found'], 404);
        }
    }

    public function getActive(Request $request) {
        $members = ConventionMember::whereHas('user', function ($query) {
            $query->where('status', UserStatusEnum::REGISTERED);
        });

        if($request->exists('is_search') && $request->is_search) {
            $members = $members->whereHas('user', function ($query) use ($request) {
                $query->where('first_name', 'like', "%$request->keyword%")
                    ->orWhere('middle_name', 'like', "%$request->keyword%")
                    ->orWhere('last_name', 'like', "%$request->keyword%")
                    ->orWhere('email', 'like', "%$request->keyword%")
                    ->orWhere('phone', 'like', "%$request->keyword%");
            });
        } else if(!$request->show_all) {
            $members = $members->limit(30);
        }

        $members = $members->with(['user', 'registration_type'])
            ->orderBy('updated_at', 'desc')
            ->get();

        if($members->isNotEmpty()) {
            return response()->json($members);
        } else {
            return response()->json(['message' => 'No active members were found'], 404);
        }
    }

    public function getConventionMembers(Request $request) {
        $members = ConventionMember::whereHas('user')
            ->join('users', 'users.id', '=', 'convention_members.user_id');

        if($request->exists('is_search') && $request->is_search) {
            $members = $members->whereHas('user', function ($query) use ($request) {
                $query->where('first_name', 'like', "%$request->keyword%")
                    ->orWhere('middle_name', 'like', "%$request->keyword%")
                    ->orWhere('last_name', 'like', "%$request->keyword%")
                    ->orWhere('email', 'like', "%$request->keyword%");
            });
        } else if(!$request->show_all) {
            $members = $members->limit(30);
        }

        $members = $members->with(['user', 'registration_type'])
            ->orderBy('users.last_name', 'asc')
            ->get();

        if($members->isNotEmpty()) {
            return response()->json($members);
        } else {
            return response()->json(['message' => 'No members were found'], 404);
        }
    }

    public function getAllMembers(Request $request) {
        $members = ConventionMember::whereHas('user')
        ->where('is_sponsor_exhibitor', false)
        ->join('users', 'users.id', '=', 'convention_members.user_id');

    if($request->exists('is_search') && $request->is_search) {
        $members = $members->whereHas('user', function ($query) use ($request) {
            $query->where('first_name', 'like', "%$request->keyword%")
                ->orWhere('middle_name', 'like', "%$request->keyword%")
                ->orWhere('last_name', 'like', "%$request->keyword%")
                ->orWhere('email', 'like', "%$request->keyword%");
        });
    } else if(!$request->show_all) {
        $members = $members->limit(30);
    }

    $members = $members->with(['user', 'registration_type'])
        ->orderBy('users.last_name', 'asc')
        ->get();

    if($members->isNotEmpty()) {
        return response()->json($members);
    } else {
        return response()->json(['message' => 'No members were found'], 404);
    }
    }

    public function getPaid(Request $request) {
        $paid_member_ids = User::whereHas('member.payments')->pluck('id')->toArray();
        $pass_ids = User::rightJoin('complimentary_passes', 'users.id', '=', 'complimentary_passes.user_id')->pluck('users.id')->toArray();

        $member_ids = array_unique(array_merge($paid_member_ids, $pass_ids));
        $members = User::whereIn('id', $member_ids);

        if($request->exists('is_search') && $request->is_search) {
            $members = $members->where('first_name', 'like', "%$request->keyword%")
                ->orWhere('middle_name', 'like', "%$request->keyword%")
                ->orWhere('last_name', 'like', "%$request->keyword%")
                ->orWhere('email', 'like', "%$request->keyword%")
                ->orWhere('phone', 'like', "%$request->keyword%");
        } else if(!$request->show_all) {
            $members = $members->limit(30);
        }

        $members = $members->with(['member.type', 'member.payments'])
            ->where('role', RoleEnum::CONVENTION_MEMBER)
            ->orderBy('last_name', 'asc')
            ->get();

        if($members->isNotEmpty()) {
            return response()->json($members);
        } else {
            return response()->json(['message' => 'No paid members were found'], 404);
        }
    }

    public function getConventionMember($id) {
        $member = ConventionMember::where('id', $id)->with(['user'])->first();

        if(!is_null($member)) {
            return response()->json($member);
        } else {
            return response()->json(['message' => 'Member not found'], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $other_user = User::where('email', $validated["email"])->first();
            if(is_null($other_user)) {
                $validated["role"] = RoleEnum::CONVENTION_MEMBER;
                $validated["password"] = Hash::make($validated["password"]);
                $validated["status"] = UserStatusEnum::ACTIVE;
                $user = User::create($validated);

                $validated["user_id"] = $user->id;
                ConventionMember::create($validated);

                DB::commit();
                return response()->json([
                    'message' => 'Successfully created member'
                ]);
            } else {
                return response()->json([
                    'message' => 'Email already exists'
                ], 400);
            }
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Create $request, $id) {
        $validated = $request->validated();

        $member = ConventionMember::where('id', $id)->with(['user'])->first();
        if(is_null($member)) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        DB::beginTransaction();
        try {
            if(Auth::user()->role == RoleEnum::ADMIN) {
                if($request->exists("is_good_standing")) {
                    $validated["is_good_standing"] = $request["is_good_standing"];
                }
            }

            $member->fill($validated);
            $member->save();

            $member->user->fill($validated);
            $member->user->save();

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated convention member'
            ]);
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        $member = ConventionMember::where('id', $id)->with(['user'])->first();

        if(!is_null($member)) {
            $member->delete();
            $member->user->delete();
            return response()->json(['message' => 'Member deleted']);
        } else {
            return response()->json(['message' => 'Member not found'], 404);
        }
    }

    public function getRegistrationTypes() {
        $registration_type = RegistrationType::all();

        if($registration_type->isNotEmpty()) {
            return response()->json($registration_type);
        } else {
            return response()->json(['message' => 'No registration types found'], 404);
        }
    }

    public function updateField(Request $request, $id) {
        $member = ConventionMember::where('id', $id)->first();
        if(is_null($member)) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        DB::beginTransaction();
        try {
            $member->update([$request->field => $request->value]);

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated field',
            ]);
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}

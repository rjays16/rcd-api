<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Enum\RoleEnum;
use App\Enum\UserStatusEnum;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\Member\Create;
use App\Http\Requests\Member\SetDefaultPassword;

use App\Mail\UpdatePassword;

use Exception;
use DB;

class AuthController extends Controller
{
    public function loginAdmin(LoginRequest $request) {
        $validated = $request->validated();

        $user = User::where('email', $validated["email"])
            ->whereIn('role', [RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
            ->first();

        if(is_null($user)) {
            return response()->json([
                'message' => 'Admin user not found'
            ], 404);
        }

        return self::checkUser($validated, $user);
    }

    public function loginMember(LoginRequest $request) {
        $validated = $request->validated();

        $user = User::where('email', $validated["email"])
            ->with(['member'])
            ->whereIn('role', [RoleEnum::CONVENTION_MEMBER, RoleEnum::SPONSOR])
            ->first();

        if(is_null($user)) {
            return response()->json([
                'message' => 'Member account not found'
            ], 404);
        }

        if($user->status != UserStatusEnum::REGISTERED){
            return response()->json([
                'message' => 'Member is not yet registered.'
            ], 404);
        }

        return self::checkUser($validated, $user);
    }

    public function loginSponsor(LoginRequest $request) {
        $validated = $request->validated();

        $user = User::where('email', $validated["email"])
            ->where('role', RoleEnum::SPONSOR)
            ->first();

        if(is_null($user)) {
            return response()->json([
                'message' => 'Sponsor account not found'
            ], 404);
        }

        return self::checkUser($validated, $user);
    }

    private static function checkUser($validated, $user) {
        try {
            // dd(Hash::check($validated["password"], $user->password));
            if(Hash::check($validated["password"], $user->password)) {
                $token = $user->createToken('API Token')->accessToken;
                if($user->role == RoleEnum::CONVENTION_MEMBER) {
                    $user->tokens()->limit(PHP_INT_MAX)->offset(1)->get()->map(function ($token) {
                        $token->revoke();
                    });
                }

                $user->active_token = $token;
                $user->save();

                return response()->json([
                    'token' => $token,
                    'user' => $user,
                ]);
            } else {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function logout(Request $request) {
        $token = $request->user();
        $token->token()->revoke();

        $user = User::where('id', Auth::user()->id)->first();
        $user->active_token = null;
        $user->save();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function getUser() {
        $user = User::where('id', Auth::user()->id)
            ->with('member')
            ->first();

        if(!is_null($user)) {
            return response()->json($user);
        } else {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    }

    public function getAdminUser() {
        $user = User::where('id', Auth::user()->id)
            ->whereIn('role', [RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
            ->with('admin_capability')
            ->first();

        if(!is_null($user)) {
            return response()->json($user);
        } else {
            return response()->json([
                'message' => 'Admin user account was not found'
            ], 404);
        }
    }

    public function getSponsorUser() {
        $user = User::where('id', Auth::user()->id)
            ->where('role', RoleEnum::SPONSOR)
            ->with('sponsor.type')
            ->first();

        if(!is_null($user)) {
            return response()->json($user);
        } else {
            return response()->json([
                'message' => 'Sponsor user not found'
            ], 404);
        }
    }

    public function setDefaultPassword(SetDefaultPassword $request) {
        $validated = $request->validated();

        $user = User::where('email', $validated["email"])
            ->where('role', RoleEnum::CONVENTION_MEMBER)
            ->first();

        if(!is_null($user)) {
            DB::beginTransaction();
            try {
                $user->password = Hash::make(config('settings.DEFAULT_MEMBER_PASSWORD'));
                $user->save();

                DB::commit();
                return response()->json([
                    'message' => 'Successfully reset password.'
                ]);
            } catch(Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } else {
            return response()->json([
                'message' => 'Member profile was not found.'
            ], 404);
        }
    }

    public function updatePassword(Request $request) {
        $user_password = User::where('id', Auth::user()->id)->first()->password;
        $current_password = $request->current_password;
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;

        if($new_password === $confirm_password) {
            if(Hash::check($current_password, $user_password)) {
                $user = Auth::user();
                $user->password = Hash::make($request->confirm_password);
                $user->save();

                // Mail::to(Auth::user()->email)->send(new UpdatePassword($user)); //if no email address need to input by user
                Mail::to($request->email)->send(new UpdatePassword($user));
                return response()->json([
                    'message' => 'Successfully updated password'
                ]);
            } else {
                return response()->json([
                    "message" => "Invalid current password"
                ], 400);
            }
        } else {
            return response()->json([
                "message" => "Incorrect password for confirmation"
            ], 400);
        }
    }

    public function update(Create $request, $id) {
        $validated = $request->validated();

        $user = User::where('id', $id)
            ->with('member')
            ->first();

        if(is_null($user)) {
            return response()->json(['message' => 'Member profile was not found'], 404);
        }

        DB::beginTransaction();
        try {
            if(Auth::user()->role == RoleEnum::ADMIN) {
                if($request->exists("is_good_standing")) {
                    $validated["is_good_standing"] = $request["is_good_standing"];
                }
            }

            if(!is_null($user->member)) {
                if($request->hasFile('resident_certificate')) {
                    $fileExtension = $request->file('resident_certificate')->getClientOriginalName();
                    $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                    $extension = $request->file('resident_certificate')->getClientOriginalExtension();
                    $fileStore = $file.'_'.time().'.'.$extension;
                    $request->file('resident_certificate')->storeAs('/images/resident', $fileStore);
                    $validated["resident_certificate"] = config('settings.APP_URL')."/storage/images/resident/".$fileStore;
                }
                $user->member->fill($validated);
                $user->member->save();

                $user->fill($validated);
                $user->save();

                DB::commit();
                return response()->json([
                    'message' => 'Successfully updated profile'
                ]);
            } else {
                return response()->json([
                    'message' => 'This user has no member account.'
                ], 404);
            }
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
    public function updateField(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $user->update([$request->field => $request->value]);

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
<?php

namespace App\Imports\VIP;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

use App\Models\User;
use App\Models\ConventionMember;
use App\Models\RegistrationSubType;

use App\Enum\RoleEnum;
use App\Enum\UserStatusEnum;
use App\Enum\RegistrationTypeEnum;

use DB;

class Import implements ToModel, WithStartRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */

    private $rejected_emails = [];
    private $num_imported = 0;
    protected $vip_type;

    public function __construct(int $vip_type) {
        $this->vip_type = $vip_type;
    }

    // Starts on the 3rd row so that other column/row names are excluded
    public function startRow():int {
        return 3;
    }

    public function getRejected() {
        return $this->rejected_emails;
    }

    public function getNumImported() {
        return $this->num_imported;
    }

    public function model(array $row) {
        if(!$row[0]) { // skip rows with no IDs - in the first index of the row
            return null;
        }

        $last_name = $row[1];
        $first_name = $row[2];
        $middle_name = $row[3];
        $certificate_name = $row[4];
        $email = $row[10];
        $delegate_sub_type_name = $row[11];
        
        $other_user = null;
        $other_user_query = User::where('role', RoleEnum::CONVENTION_MEMBER)
            ->whereHas('member', function ($query) { 
                $query->where('type', $this->vip_type);
            });

        if($email) {
            $other_user = User::where('email', $email)->first();
            if(!is_null($other_user)) {
                array_push($this->rejected_emails, $email);
            }
        } else {
            $other_user = $other_user_query->where('first_name', $first_name)
                ->where('last_name', $last_name)
                ->first();
        }

        if(!is_null($other_user)) {
            return null;
        }

        $delegate_sub_type_id = null;
        $delegate_sub_type = RegistrationSubType::where('name', 'like', "%$delegate_sub_type_name%")->first();
        if(is_null($delegate_sub_type)) {
            return null;
        } else {
            $delegate_sub_type_id = $delegate_sub_type->id;
        }

        DB::beginTransaction();
        try {
            $user = new User();
            $user->first_name = $first_name;
            $user->middle_name = $middle_name ?? NULL;
            $user->last_name = $last_name;
            $user->certificate_name = $certificate_name ?? NULL;
            $user->email = $email ?? NULL;
            $user->password = Hash::make(config('settings.DEFAULT_MEMBER_PASSWORD'));
            $user->role = RoleEnum::CONVENTION_MEMBER;
            $user->status = UserStatusEnum::IMPORTED_PENDING;
            $user->save();

            // Create the speaker account
            $member = ConventionMember::create([
                'user_id' => $user->id,
                'type' => $this->vip_type,
                'sub_type' => $delegate_sub_type_id,
            ]);
            
            DB::commit();
            if($member) {
                $this->num_imported += 1;
            }
        } catch(Exception $e) {
            DB::rollback();
            return null;
        }
    }
}
<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\PlenaryAttendance;
use App\Models\PlenaryDay;
use App\Enum\RoleEnum;

class PlenaryAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('role', RoleEnum::CONVENTION_MEMBER)->with('member')->get();
        $plenary_days = PlenaryDay::all();

        foreach($users as $user) {
            foreach($plenary_days as $plenary_day){
                $plenary_attendance = new PlenaryAttendance();
                $plenary_attendance->convention_member_id = $user->member->id;
                $plenary_attendance->date = $plenary_day->date;
                $plenary_attendance->plenary_day_id = $plenary_day->id;
                $plenary_attendance->save();
            }
        }
    }
}

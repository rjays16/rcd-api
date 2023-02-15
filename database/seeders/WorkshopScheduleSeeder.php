<?php

use Illuminate\Database\Seeder;

use App\Models\WorkshopSchedule;

class WorkshopScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $workshop_schedules = [
            ['workshop_name' => 'Laser', 'workshop_sdate' => '2022-08-30', 'workshop_edate' => '2022-08-30', 'is_active' => 1],
            ['workshop_name' => 'Aesthetic', 'workshop_sdate' => '2022-08-31', 'workshop_edate' => '2022-08-31', 'is_active' => 1],
            ['workshop_name' => 'Both Aesthetic and Laser', 'workshop_sdate' => '2022-08-30', 'workshop_edate' => '2022-08-31', 'is_active' => 1]

        ];

        foreach($workshop_schedules as $workshop_schedule) {
            try {
                $workshop = WorkshopSchedule::where([
                    ['workshop_name', $workshop_schedule['workshop_name']],
                    ['workshop_sdate', $workshop_schedule['workshop_sdate']],
                    ['workshop_edate', $workshop_schedule['workshop_edate']],
                    ['is_active', $workshop_schedule['is_active']]
                ])
                    ->first();

                if(is_null($workshop)) {
                    WorkshopSchedule::create($workshop_schedule);
                } else {
                    $workshop->update($workshop_schedule);
                }

                DB::commit();
            } catch(Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}

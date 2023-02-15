<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use App\Models\PlenaryAttendance;

use Exception;
use DB;

use Carbon\Carbon;

class CreateLogoutForPlenaryAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plenaryAttendance:createLogout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the logout data for the recorded plenary attendance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $current_date = Carbon::today()->toDateString();
        $time_now = Carbon::now()->toTimeString();
        
        $channel = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/plenary_attendance/cron.log'),
        ]);

        $plenary_attendance_today_query = PlenaryAttendance::query()->where('date', $current_date)->where('logged_out_at', NULL);
        $plenary_attendance_today = $plenary_attendance_today_query->get();
            
        if($plenary_attendance_today->isNotEmpty()) {
            DB::beginTransaction();
            try {
                $plenary_attendance_today_query->update(['logged_out_at' => $time_now]);
                DB::commit();
                Log::stack(['slack', $channel])->info("Successfully added the time out for the date of: $current_date \n");
            } catch(Exception $e) {
                DB::rollBack();
                Log::critical(['slack', $channel])->info("Could not record the time out for the date of: $current_date  \n");
                throw $e;
            }
        } else {
            Log::stack(['slack', $channel])->info("Their was no recorded data for the plenary attendance for the date of: $current_date \n");
        }
    }
}
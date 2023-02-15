<?php

namespace App\Exports\OnDemandLogs;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class ExportPlenary implements WithHeadings, FromCollection, ShouldAutoSize
{
    use Exportable;

    public function headings(): array {
        $headers = [
            'Date',
            'Logged Date',
            'Attendee',
            'Logged Day',
            'Time Log In',  
            'Time Log Out',
            'Est. Session Duration',
        ];

        return $headers;
    }

    public function collection() {
        $plenary_logs = collect(DB::select("CALL RCDsp_OnDemandPlenaryLogs()"));
        return $plenary_logs;
    }
}
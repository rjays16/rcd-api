<?php

namespace App\Exports\OnDemandLogs;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class ExportIndustry implements WithHeadings, FromCollection, ShouldAutoSize
{
    use Exportable;

    public function headings(): array {
        $headers = [
            'Logged Date',
            'Attendee',
            'URL'
        ];

        return $headers;
    }

    public function collection() {
        $plenary_logs = collect(DB::select("CALL RCDsp_OnDemandIndustryLogs()"));
        return $plenary_logs;
    }
}
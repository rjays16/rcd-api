<?php

namespace App\Exports\Attendance;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

use DB;

class ExportPlenary implements WithHeadings, FromCollection
{
    use Exportable;

    public function headings(): array {
        $headers = [
            'Date',
            'Attendees',
            'Description',
            'Time Log In',
            'Time Log Out',
            'Est. Session Duration',
            'Status'
        ];

        return $headers;
    }

    public function collection() {
        $plenary_attendace = collect(DB::select("CALL RCDsp_LedgerListPlenaryAttendance()"));

        return $plenary_attendace;
    }
}

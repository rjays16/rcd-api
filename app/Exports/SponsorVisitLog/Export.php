<?php

namespace App\Exports\SponsorVisitLog;

use Maatwebsite\Excel\Concerns\Exportable; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\SponsorVisitLog;
use DB;

class Export implements WithHeadings, FromCollection
{
    use Exportable;

    protected $sponsor_id;

    public function __construct(int $sponsor_id) {
        $this->sponsor_id = $sponsor_id;
    }

    public function headings(): array {
        $headers = [
            'First Name',
            'Last Name',
            'Date Visited',
        ];

        return $headers;
    }

    public function collection() {
        $visit_logs = SponsorVisitLog::join('users', 'users.id', '=', 'sponsor_member_visit_logs.user_id')
            ->select(
                'first_name', 'last_name',
                DB::raw('DATE_FORMAT(sponsor_member_visit_logs	.date, "%M %d, %Y") as date'),
            )
            ->where('sponsor_id', $this->sponsor_id)
            ->get();

        return $visit_logs;
    }
}
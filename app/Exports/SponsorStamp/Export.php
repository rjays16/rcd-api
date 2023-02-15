<?php

namespace App\Exports\SponsorStamp;

use Maatwebsite\Excel\Concerns\Exportable; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\SponsorStamp;
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
            'Stamped Date',
        ];

        return $headers;
    }

    public function collection() {
        $stamps = SponsorStamp::join('users', 'users.id', '=', 'sponsor_stamps.user_id')
            ->select(
                'first_name', 'last_name',
                DB::raw('DATE_FORMAT(sponsor_stamps.date, "%M %d, %Y") as date'),
            )
            ->where('sponsor_id', $this->sponsor_id)
            ->get();

        return $stamps;
    }
}
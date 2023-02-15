<?php

namespace App\Exports\Payment;

use Maatwebsite\Excel\Concerns\Exportable; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\Payment;
use DB;

class Export implements WithHeadings, FromCollection
{
    use Exportable;

    public function __construct() {
    }

    public function headings(): array {
        $headers = [
            'Name',
            'Email',
            'Delegate Type',
            'Description',
            'Total Payment',
            'Payment Method',
            'Status',
            'Date Paid'
        ];

        return $headers;
    }

    public function collection() {
        return collect(DB::select('CALL spGetPayments()'));
    }
}
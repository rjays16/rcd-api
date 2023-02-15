<?php

namespace App\Exports\VIP;

use Maatwebsite\Excel\Concerns\Exportable; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;

class Template implements WithHeadings, WithEvents
{
    use Exportable, RegistersEventListeners;

    public function __construct() {
    }

    public function headings(): array {
        $headers = [
            'Last Name',
            'First Name',
            'Middle Name',
            'Certificate Name',
            'Email',
        ];

        return $headers;
    }

    public static function afterSheet(AfterSheet $event) {
        $row_data = [
            'Smith',
            'John',
            'R',
            'John R. Smith',
            'johnsmith@gmail.com',
        ];

        $event->sheet->appendRows(array($row_data), $event);
    }
}
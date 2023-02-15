<?php

namespace App\Exports\SymposiaView;

use Maatwebsite\Excel\Concerns\Exportable; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Symposia;
use DB;

class Export implements WithHeadings, FromCollection, ShouldAutoSize
{
    use Exportable;

    public function __construct() {
    }

    public function headings(): array {
        $headers = [
            'ID',
            'Symposia Title',
            'Number of Views',
        ];

        return $headers;
    }

    public function collection() {
        $symposia_views = Symposia::get()
            ->makeHidden(['author', 'thumbnail', 'video', 'category_id', 'card_title', 'created_at', 'updated_at', 'deleted_at']);
        
        return $symposia_views;
    }
}
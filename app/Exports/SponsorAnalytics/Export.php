<?php

namespace App\Exports\SponsorAnalytics;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Export implements WithMultipleSheets
{
    use Exportable;

    protected $sponsor_id;

    public function __construct(int $sponsor_id) {
        $this->sponsor_id = $sponsor_id;
    }

    public function sheets(): array {
        $sheets = [
            new BoothStatisticsExport($this->sponsor_id),
            new BrochuresExport($this->sponsor_id),
            new VideosExport($this->sponsor_id)
        ];

        return $sheets;
    }
}
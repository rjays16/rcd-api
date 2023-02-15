<?php

namespace App\Exports\SponsorAnalytics;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\Sponsor;
use DB;

class VideosExport implements WithHeadings, FromCollection, ShouldAutoSize, WithTitle
{
    use Exportable;

    protected $sponsor_id;

    public function __construct(int $sponsor_id) {
        $this->sponsor_id = $sponsor_id;
    }

    public function title(): string {
        return 'Videos Analytics';
    }

    public function headings(): array {
        $headers = [
            'ID No.',
            'Link',
            'Clicks',
        ];

        return $headers;
    }

    public function collection() {
        $sponsor = Sponsor::where('id', $this->sponsor_id)
            ->whereHas('user')
            ->first();
        if(is_null($sponsor)) {
            return null;
        }

        $video_stats = $sponsor->assets()->video()
            ->select(['id', 'url'])
            ->get();

        return $video_stats;
    }
}
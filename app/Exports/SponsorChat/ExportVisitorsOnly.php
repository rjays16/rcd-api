<?php

namespace App\Exports\SponsorChat;

use Maatwebsite\Excel\Concerns\Exportable; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\Chat;
use App\Models\Sponsor;
use App\Models\SponsorVisitLog;
use DB;

class ExportVisitorsOnly implements WithHeadings, FromCollection
{
    use Exportable;

    protected $sponsor_id;

    public function __construct(int $sponsor_id) {
        $this->sponsor_id = $sponsor_id;
    }

    public function headings(): array {
        $headers = [
            'First Name',
            'Middle Name',
            'Last Name',
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

        $visitor_ids = $sponsor->visit_logs()->pluck('user_id')->toArray();
        $sender_ids = Chat::where('receiver_id', $sponsor->user->id)->pluck('sender_id')->toArray();

        $visitors_only_ids = array_unique(array_diff($visitor_ids, $sender_ids));

        $visit_logs = SponsorVisitLog::join('users', 'users.id', '=', 'sponsor_member_visit_logs.user_id')
            ->distinct('sponsor_member_visit_logs.user_id')
            ->select(
                'first_name', 'middle_name', 'last_name',
                // DB::raw('DATE_FORMAT(sponsor_member_visit_logs.date, "%M %d, %Y") as date'),
            )
            ->whereIn('user_id', $visitors_only_ids)
            ->where('sponsor_id', $this->sponsor_id)
            ->get();

        return $visit_logs;
    }
}
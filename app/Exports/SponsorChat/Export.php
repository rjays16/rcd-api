<?php

namespace App\Exports\SponsorChat;

use Maatwebsite\Excel\Concerns\Exportable; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\User;
use App\Models\Chat;
use App\Models\Sponsor;
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

        $sender_ids = Chat::where('receiver_id', $sponsor->user->id)->pluck('sender_id');
        $senders = User::whereIn('id', $sender_ids)
            ->where('is_anon_for_chat', false) // only include those who are no longer anonymous
            ->select(['first_name', 'middle_name', 'last_name'])
            ->get()
            ->makeHidden(['full_name']);

        return $senders;
    }
}
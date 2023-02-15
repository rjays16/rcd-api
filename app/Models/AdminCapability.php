<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminCapability extends Model
{
    public $table = "admin_capabilities";

    protected $fillable = [
        'user_id',
        'delegates',
        'abstracts',
        'can_delete_abstract',
        'can_resend_abstract_ty_mail',
        'vip',
        'can_update_members',
        'fees',
        'payments',
        'orders',
        'can_update_orders',
        'sponsors',
        'can_update_sponsors',
        'plenary',
        'can_update_plenary',
        'symposia',
        'can_update_symposia',
        'industry_lecture',
        'can_update_industry_lecture',
        'site_settings',
    ];

    public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}
}
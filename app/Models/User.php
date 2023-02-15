<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Enum\RoleEnum;

use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use HasApiTokens, Authenticatable, Authorizable, SoftDeletes, CanResetPassword, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'certificate_name',
        'email',
        'password',
        'country',
        'role',
        'status',
        'is_anon_for_chat'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'full_name'
    ];

    protected $casts = [
        'is_anon_for_chat' => 'boolean',
    ];

    public function user_role() {
        return $this->belongsTo(Role::class, 'role');
    }

    public function user_status() {
        return $this->belongsTo(UserStatus::class, 'status');
    }

    public function member() {
        return $this->hasOne(ConventionMember::class, 'user_id');
    }

    public function sponsor() {
        return $this->hasOne(Sponsor::class, 'user_id');
    }

    public function sponsor_exhibitor() {
        return $this->hasOne(SponsorExhibitor::class, 'user_id');
    }

    public function admin_capability() {
        return $this->hasOne(AdminCapability::class, 'user_id');
    }

    public function getFullNameAttribute() {
        return join(' ', array_filter(array($this->first_name, $this->middle_name, $this->last_name)));
    }
}

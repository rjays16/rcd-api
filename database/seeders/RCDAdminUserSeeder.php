<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\AdminCapability;

use App\Enum\RoleEnum;

class RCDAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rcd_emails = ['rcd_abstract@gmail.com'];
        
        foreach($rcd_emails as $rcd_email) {
            $user = User::where('email', $rcd_email)->first();
            if(is_null($user)) {
                $user = new User();
                $user->first_name = "Admin";
                $user->email = $rcd_email;
                $user->password = app('hash')->make(config('settings.DEFAULT_ADMIN_PASSWORD'));
                $user->role = RoleEnum::ADMIN;
                $user->save();
            }
        }

        // RCD Admin User - Abstract Only
        $rcd_admin_user_abstract = User::where('email', 'rcd_abstract@gmail.com')->first();
        if(!is_null($rcd_admin_user_abstract)) {
            $admin_capability = AdminCapability::where('user_id', $rcd_admin_user_abstract->id)->first();
            if(is_null($admin_capability)) {
                $admin_capability = new AdminCapability();
            }
            $admin_capability->user_id = $rcd_admin_user_abstract->id;
            $admin_capability->abstracts = true;
            $admin_capability->save();
        }

        // RCD Admin User
        $rcd_admin_user = User::where('email', 'rcdsupp2022@gmail.com')->first();
        if(!is_null($rcd_admin_user)) {
            $admin_capability = AdminCapability::where('user_id', $rcd_admin_user->id)->first();
            if(is_null($admin_capability)) {
                $admin_capability = new AdminCapability();
            }
            $admin_capability->user_id = $rcd_admin_user->id;
            $admin_capability->delegates = true;
            $admin_capability->vip = true;
            $admin_capability->fees = true;
            $admin_capability->payments = true;
            $admin_capability->save();
        }

        $super_admin_user_emails = ['dave.c@rightteamprovider.com', 'john.r@rightteamprovider.com', 'samuel.s@rightteamprovider.com'];
        foreach($super_admin_user_emails as $super_admin_user_email) {
            $super_admin_user = User::where('email', $super_admin_user_email)->where('role', RoleEnum::SUPER_ADMIN)->first();
            if(!is_null($super_admin_user)) {
                $super_admin_user_capability = AdminCapability::where('user_id', $super_admin_user->id)->first();
                if(is_null($super_admin_user_capability)) {
                    $super_admin_user_capability = new AdminCapability();
                }
                $super_admin_user_capability->user_id = $super_admin_user->id;
                $super_admin_user_capability->delegates = true;
                $super_admin_user_capability->abstracts = true;
                $super_admin_user_capability->can_delete_abstract = true;
                $super_admin_user_capability->can_resend_abstract_ty_mail = true;
                $super_admin_user_capability->vip = true;
                $super_admin_user_capability->can_update_members = true;
                $super_admin_user_capability->fees = true;
                $super_admin_user_capability->payments = true;
                $super_admin_user_capability->orders = true;
                $super_admin_user_capability->can_update_orders = true;
                $super_admin_user_capability->sponsors = true;
                $super_admin_user_capability->can_update_sponsors = true;
                $super_admin_user_capability->plenary = true;
                $super_admin_user_capability->can_update_plenary = true;
                $super_admin_user_capability->symposia = true;
                $super_admin_user_capability->can_update_symposia = true;
                $super_admin_user_capability->industry_lecture = true;
                $super_admin_user_capability->can_update_industry_lecture = true;
                $super_admin_user_capability->site_settings = true;
                $super_admin_user_capability->save();
            }
        }
    }
}
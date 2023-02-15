<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdminCapabilitiesAddCanResendAbstractTyMail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_capabilities', function (Blueprint $table) {
            $table->boolean('can_resend_abstract_ty_mail')->default(false)->after('can_delete_abstract');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_capabilities', function (Blueprint $table) {
            $table->dropColumn(['can_resend_abstract_ty_mail']);
        });
    }
}

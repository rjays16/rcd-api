<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConventionMembersAddCanGenerateCertificate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convention_members', function (Blueprint $table) {
            $table->boolean('can_generate_certificate')->default(false)->after('current_stamp_round_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convention_members', function (Blueprint $table) {
            $table->dropColumn(['can_generate_certificate']);
        });
    }
}

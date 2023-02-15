<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConventionMembersAddCurrentStampRoundNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convention_members', function (Blueprint $table) {
            $table->integer('current_stamp_round_number')->default(0)->after('is_eligible_for_next_stamp_round');
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
            $table->dropColumn(['current_stamp_round_number']);
        });
    }
}

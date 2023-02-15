<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConventionMembersAddNumRaffleTicketsIsEligibleForNextStampRound extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convention_members', function (Blueprint $table) {
            $table->integer('num_raffle_tickets')->default(0)->after('is_sponsor_exhibitor');
            $table->boolean('is_eligible_for_next_stamp_round')->default(false)->after('num_raffle_tickets');
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
            $table->dropColumn(['num_raffle_tickets', 'is_eligible_for_next_stamp_round']);
        });
    }
}

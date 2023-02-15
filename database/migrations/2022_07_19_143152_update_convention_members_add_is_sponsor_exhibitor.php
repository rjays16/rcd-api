<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConventionMembersAddIsSponsorExhibitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convention_members', function (Blueprint $table) {
            $table->boolean('is_sponsor_exhibitor')->default(false)->after('is_good_standing');
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
            $table->dropColumn(['is_sponsor_exhibitor']);
        });
    }
}

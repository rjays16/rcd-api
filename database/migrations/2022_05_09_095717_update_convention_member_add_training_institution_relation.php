<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConventionMemberAddTrainingInstitutionRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convention_members', function (Blueprint $table) {
            $table->unsignedBigInteger('training_institution')->nullable()->after('ws_to_attend');
            $table->foreign('training_institution')->references('id')->on('training_institutions');
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
            $table->dropForeign(['training_institution']);
            $table->dropColumn(['training_institution']);
        });
    }
}

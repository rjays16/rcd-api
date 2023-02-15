<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConventionMemberAddApplicantTrainingRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convention_members', function (Blueprint $table) {
            $table->unsignedBigInteger('applicant_institution')->nullable()->after('training_institution');
            $table->foreign('applicant_institution')->references('id')->on('applicant_institutions');
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
            $table->dropForeign(['applicant_institution']);
            $table->dropColumn(['applicant_institution']);
        });
    }
}

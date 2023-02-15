<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSponsorTypesAddMaxExhibitorsMaxIndustryLectureAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sponsor_types', function (Blueprint $table) {
            $table->integer('max_exhibitors')->after('has_ticker_text')->default(0);
            $table->integer('max_industry_lecture_account')->after('max_exhibitors')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sponsor_types', function (Blueprint $table) {
            $table->dropColumn(['max_exhibitors', 'max_industry_lecture_account']);
        });
    }
}

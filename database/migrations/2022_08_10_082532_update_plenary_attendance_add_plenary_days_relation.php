<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePlenaryAttendanceAddPlenaryDaysRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plenary_attendance', function (Blueprint $table) {
            $table->unsignedBigInteger('plenary_day_id')->nullable()->after('plenary_event_id');
            $table->foreign('plenary_day_id')->references('id')->on('plenary_days');
            $table->time('logged_out_at')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plenary_attendance', function (Blueprint $table) {
            $table->dropForeign(['plenary_day_id']);
            $table->dropColumn(['plenary_day_id']);
        });
    }
}

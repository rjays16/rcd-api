<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlenaryAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plenary_attendance', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('convention_member_id');
            $table->foreign('convention_member_id')->references('id')->on('convention_members');
            $table->unsignedBigInteger('plenary_event_id')->nullable();
            $table->foreign('plenary_event_id')->references('id')->on('plenary_events');
            $table->time('logged_in_at')->default(0);
            $table->time('logged_out_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plenary_attendance');
    }
}

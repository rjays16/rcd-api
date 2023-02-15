<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        date_default_timezone_set('Asia/Manila');
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('convention_member_id');
            $table->foreign('convention_member_id')->references('id')->on('convention_members');
            $table->dateTime('date_time');
            $table->string('url');
            $table->boolean('is_login')->default(false);
            $table->boolean('is_logout')->default(false);
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
        Schema::dropIfExists('logs');
    }
}

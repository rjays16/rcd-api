<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkshopSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshop_schedules', function (Blueprint $table) {
            $table->id();
            $table->string("workshop_name", 255);
            $table->date("workshop_sdate")->nullable();
            $table->date("workshop_edate")->nullable();
            $table->boolean("is_active")->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workshop_schedules');
    }
}

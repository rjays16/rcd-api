<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePlenaryEventsAddHeaderColor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plenary_events', function (Blueprint $table) {
            $table->string('header_color')->after('ends_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plenary_events', function (Blueprint $table) {
            $table->dropColumn(['header_color']);
        });
    }
}

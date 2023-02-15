<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFeesAddRegistrationTypeRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->unsignedBigInteger('registration_type')->nullable()->after('uses_late_amount');
            $table->foreign('registration_type')->references('id')->on('registration_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropForeign(['registration_type']);
            $table->dropColumn(['registration_type']);
        });
    }
}

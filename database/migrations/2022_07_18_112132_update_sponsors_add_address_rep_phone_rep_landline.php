<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSponsorsAddAddressRepPhoneRepLandline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->string('address', 500)->after('announcement')->nullable();

            $table->string('rep_phone', 191)->after('rep_name')->nullable();
            $table->string('rep_landline', 191)->after('rep_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropColumn(['address', 'rep_phone', 'rep_landline']);
        });
    }
}

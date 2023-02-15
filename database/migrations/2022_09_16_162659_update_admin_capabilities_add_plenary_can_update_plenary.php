<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdminCapabilitiesAddPlenaryCanUpdatePlenary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_capabilities', function (Blueprint $table) {
            $table->boolean('plenary')->default(false)->after('can_update_sponsors');
            $table->boolean('can_update_plenary')->default(false)->after('plenary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_capabilities', function (Blueprint $table) {
            $table->dropColumn(['plenary', 'can_update_plenary']);
        });
    }
}

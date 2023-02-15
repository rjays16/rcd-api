<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdminCapabilitiesAddSponsorsAndCanUpdateSponsors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_capabilities', function (Blueprint $table) {
            $table->boolean('sponsors')->default(false)->after('can_update_orders');
            $table->boolean('can_update_sponsors')->default(false)->after('sponsors');
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
            $table->dropColumn(['sponsors', 'can_update_sponsors']);
        });
    }
}

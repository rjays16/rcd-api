<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdminCapabilitiesTableAddCanDeleteAbstract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_capabilities', function (Blueprint $table) {
            $table->boolean('can_delete_abstract')->default(false)->after('abstracts');
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
            $table->dropColumn(['can_delete_abstract']);
        });
    }
}

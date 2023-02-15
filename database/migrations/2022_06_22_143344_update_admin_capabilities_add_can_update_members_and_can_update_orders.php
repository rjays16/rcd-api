<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdminCapabilitiesAddCanUpdateMembersAndCanUpdateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_capabilities', function (Blueprint $table) {
            $table->boolean('can_update_members')->default(false)->after('vip');
            $table->boolean('orders')->default(false)->after('payments');
            $table->boolean('can_update_orders')->default(false)->after('orders');
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
            $table->dropColumn(['can_update_members', 'orders', 'can_update_orders']);
        });
    }
}

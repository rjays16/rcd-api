<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAbstractsTableAddFlagsForConflictInterestAndCommercialFunding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('abstracts', function (Blueprint $table) {
            $table->boolean('is_conflict_interest')->default(false)->after('keywords');
            $table->boolean('is_commercial_funding')->default(false)->after('conflict_interest');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('abstracts', function (Blueprint $table) {
            $table->dropColumn(['is_conflict_interest', 'is_commercial_funding']);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSymposiaCategoriesAddCardHeaderColor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('symposia_categories', function (Blueprint $table) {
            $table->string('card_header_color')->after('subtitle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('symposia_categories', function (Blueprint $table) {
            $table->dropColumn(['card_header_color']);
        });
    }
}

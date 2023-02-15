<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSponsorTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsor_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('stamp_timer')->default(0);
            $table->integer('max_brochures')->default(0);
            $table->integer('max_catalog')->default(0);
            $table->integer('max_videos')->default(0);
            $table->boolean('has_360_view')->default(false);
            $table->boolean('has_ticker_text')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sponsor_types');
    }
}

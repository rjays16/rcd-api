<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageIframesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_iframes', function (Blueprint $table) {
            $table->id();
            $table->longtext('facade');
            $table->longtext('entrance');
            $table->longtext('lobby');
            $table->longtext('sponsors');
            $table->longtext('plenary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_iframes');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSponsorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('sponsor_type_id');
            $table->foreign('sponsor_type_id')->references('id')->on('sponsor_types');
            $table->string('logo', 500)->nullable();
            $table->string('name', 300);
            $table->string('rep_name', 255)->nullable();
            $table->string('website', 300)->nullable();
            $table->string('description', 700)->nullable();
            $table->string('phone', 191)->nullable();
            $table->string('company_email', 255)->nullable();
            $table->string('interactive_profile', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sponsors');
    }
}

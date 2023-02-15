<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConventionMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convention_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('pma_number',191)->nullable();
            $table->string('prc_license_number',191)->nullable();
            $table->timestamp('prc_expiration_date')->nullable();
            $table->string('pds_number',191)->nullable();
            $table->unsignedBigInteger('type')->nullable();
            $table->foreign('type')->references('id')->on('registration_types');
            $table->boolean('is_interested_for_ws')->default(false);
            $table->unsignedBigInteger('ws_to_attend')->nullable();
            $table->foreign('ws_to_attend')->references('id')->on('workshops');
            $table->string('resident_certificate')->nullable();
            $table->boolean('is_good_standing')->default(false);
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
        Schema::dropIfExists('convention_members');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetAbstractFieldsToNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('abstracts', function (Blueprint $table) {
            // $table->dropForeign(["convention_member_id"]);
            $table->unsignedBigInteger("convention_member_id")->nullable(true)->change();
            $table->string("keywords", 255)->nullable(true)->change();
            $table->string("study_design", 255)->nullable(true)->change();
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
            // $table->unsignedBigInteger('convention_member_id');
            // $table->foreign('convention_member_id')->references('id')->on('convention_members');
            $table->unsignedBigInteger("convention_member_id")->nullable(false)->change();
            $table->string("keywords", 255)->nullable(false)->change();
            $table->string("study_design", 255)->nullable(false)->change();
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sponsor_user_id');
            $table->foreign('sponsor_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('attendee_user_id');
            $table->foreign('attendee_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('last_chat_id')->nullable();
            $table->text('last_message')->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->boolean('viewed_sponsor')->nullable()->default(false);
            $table->boolean('viewed_attendee')->nullable()->default(false);
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
        Schema::dropIfExists('chat_lists');
    }
}

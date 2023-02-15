<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type')->nullable();
            $table->foreign('type')->references('id')->on('fee_types'); # Workshop / Registration
            $table->string('name');
            $table->string('description')->nullable();
            $table->year('year')->nullable();
            $table->boolean('scope')->default(0); # if true, it is global (USD). If false, it is local (PHP)
            $table->decimal('amount')->default(0);
            $table->decimal('intl_amount')->default(0);
            $table->boolean('status')->default(false);            
            $table->decimal('late_amount')->default(0);
            $table->date('late_amount_starts_on')->nullable();
            $table->boolean('uses_late_amount')->default(false);
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
        Schema::dropIfExists('fees');
    }
}

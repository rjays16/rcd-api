<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAbstractsTableAddIsFinalistAndEmbedPdfUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('abstracts', function (Blueprint $table) {
            $table->boolean('is_finalist')->default(false)->after('abstract_type');
            $table->string('embed_url', 700)->nullable()->after('is_finalist');
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
            $table->dropColumn(['is_finalist', 'embed_url']);
        });
    }
}

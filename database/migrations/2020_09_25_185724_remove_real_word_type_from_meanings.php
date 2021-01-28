<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRealWordTypeFromMeanings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meanings', function (Blueprint $table) {
            $table->dropColumn('real_word_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meanings', function (Blueprint $table) {
            $table->integer('real_word_type')->unsigned()->index();
        });
    }
}

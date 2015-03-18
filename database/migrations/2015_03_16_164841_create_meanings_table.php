<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeaningsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meanings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('real_word_type')->unsigned()->index();
			$table->integer('meaning_type_id')->unsigned()->index();
			$table->foreign('meaning_type_id')->references('id')->on('meaning_types');
			$table->char('root', 255)->nullable();
			$table->softDeletes();
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
		Schema::drop('meanings');
	}
}
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('word_languages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('short_name', 3);
			$table->char('name', 35);
			$table->char('image', 35);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('word_languages');
	}
}
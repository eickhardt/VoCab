<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWotdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wotds', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('date');
			$table->integer('meaning_id')->unsigned()->index();
			$table->foreign('meaning_id')->references('id')->on('meanings');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('wotds');
	}
}
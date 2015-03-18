<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\MeaningType;

class MeaningTypeTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('meaning_types')->delete();

		MeaningType::create([
			'name' => 'adjective'
		]);

		MeaningType::create([
			'name' => 'noun'
		]);

		MeaningType::create([
			'name' => 'verb'
		]);

		MeaningType::create([
			'name' => 'adverb'
		]);

		MeaningType::create([
			'name' => 'other'
		]);
	}

}

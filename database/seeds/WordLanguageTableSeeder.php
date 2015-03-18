<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\WordLanguage;

class WordLanguageTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('word_languages')->delete();

		WordLanguage::create([
			'name' => 'English',
			'short_name' => 'en',
			'image' => '/img/flags/en.png'
		]);

		WordLanguage::create([
			'name' => 'French',
			'short_name' => 'fr',
			'image' => '/img/flags/fr.png'
		]);

		WordLanguage::create([
			'name' => 'Danish',
			'short_name' => 'da',
			'image' => '/img/flags/dk.png'
		]);

		WordLanguage::create([
			'name' => 'Polish',
			'short_name' => 'pl',
			'image' => '/img/flags/pl.png'
		]);

		WordLanguage::create([
			'name' => 'Spanish',
			'short_name' => 'es',
			'image' => '/img/flags/es.png'
		]);
	}
}
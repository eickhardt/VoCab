<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MeaningTypeTableSeeder::class);
        $this->call(WordLanguageTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(MeaningTableSeeder::class);
    }
}
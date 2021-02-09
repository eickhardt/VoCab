<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('MeaningTypeTableSeeder');
        $this->call('WordLanguageTableSeeder');
        $this->call('UserTableSeeder');
        $this->call('MeaningTableSeeder');
    }
}
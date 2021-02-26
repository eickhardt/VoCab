<?php

use App\WordLanguage;
use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create([
                         'email'            => 'user1@gmail.com',
                         'password'         => Hash::make(Config::get('app.user1pw')),
                         'name'             => 'Chilly Cold',
                         'root_language_id' => WordLanguage::orderBy('id', 'desc')->first()->id,
                         'is_admin'         => true
                     ]);

        User::create([
                         'email'            => 'user2@gmail.com',
                         'password'         => Hash::make(Config::get('app.user2pw')),
                         'name'             => 'Hot Warm',
                         'root_language_id' => WordLanguage::orderBy('id', 'desc')->first()->id
                     ]);

        User::create([
                         'email'            => 'robot@mail.com',
                         'password'         => Hash::make(Config::get('app.cronpw')),
                         'name'             => 'Robot Robertson',
                         'root_language_id' => WordLanguage::orderBy('id', 'desc')->first()->id
                     ]);

        User::create([
                         'email'            => 'dummy@mail.com',
                         'password'         => Hash::make('dummy'),
                         'name'             => 'Dummy User',
                         'root_language_id' => WordLanguage::orderBy('id', 'desc')->first()->id
                     ]);
    }
}
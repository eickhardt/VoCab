<?php

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

        User::create(array(
            'email' => 'user1@gmail.com',
            'password' => Hash::make(Config::get('app.user1pw')),
            'name' => 'Chilly Cold',
            'is_admin' => true
        ));

        User::create(array(
            'email' => 'user2@gmail.com',
            'password' => Hash::make(Config::get('app.user2pw')),
            'name' => 'Hot Warm'
        ));

        User::create(array(
            'email' => 'robot@mail.com',
            'password' => Hash::make(Config::get('app.cronpw')),
            'name' => 'Robot Robertson'
        ));

        User::create(array(
            'email' => 'dummy@mail.com',
            'password' => Hash::make('dummy'),
            'name' => 'Dummy User'
        ));
    }
}
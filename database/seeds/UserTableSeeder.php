<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->delete();
		
		User::create(array(
			'email' => 'ddeickhardt@gmail.com',
			'password' => Hash::make( env('USER1_PW') ),
			'name' => 'Daniel Eickhardt'
		));

		User::create(array(
			'email' => 'g.tranchet@gmail.com',
			'password' => Hash::make( env('USER2_PW') ),
			'name' => 'Gabrielle Tranchet'
		));

		User::create(array(
			'email' => 'robot@mail.com',
			'password' => Hash::make( env('CRON_PW') ),
			'name' => 'Robot Robertson'
		));

		User::create(array(
			'email' => 'dummy@mail.com',
			'password' => Hash::make( 'dummy' ),
			'name' => 'Dummy User'
		));
	}
}
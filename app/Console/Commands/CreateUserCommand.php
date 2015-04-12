<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Hash;

use App\User;

class CreateUserCommand extends Command {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'create_user';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates a new user from the given params.';

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['email', InputArgument::REQUIRED, 'Email belonging to the new user.'],
			['password', InputArgument::REQUIRED, 'Password for the new user.'],
			['name', InputArgument::REQUIRED, 'First and last name of the new user.'],
		];
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Creating new user...');

		User::create([
			'email' => $this->argument('email'),
			'password' => Hash::make( $this->argument('password') ),
			'name' => $this->argument('name')
		]);

		$this->info('Language "'.$this->argument('name').'" was created.');
	}
}
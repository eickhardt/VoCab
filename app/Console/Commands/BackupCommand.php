<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Backup;
use Auth;
use Artisan;

class BackupCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'backup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Backup the database and store it locally.';

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['email', InputArgument::REQUIRED, 'Email of the user performing the action.'],
			['password', InputArgument::REQUIRED, 'Password of the user performing the action.'],
		];
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		if (Auth::attempt(['email' => $this->argument('email'), 'password' => $this->argument('password')]))
		{
			$user = Auth::user();
			$allowed_users = ['Daniel Eickhardt', 'Gabrielle Tranchet', 'Robot Robertson'];
			if (in_array($user->name, $allowed_users))
			{
				Artisan::call('backup:run');

				$backup_path = storage_path().'/app/';
				$files = scandir($backup_path, SCANDIR_SORT_DESCENDING);
				$newest_file = $files[2];
				$backup = Backup::create(['user_id' => $user->id, 'file' => $newest_file]);

				$this->info('Snapshot has been saved.');
			}
			else
			{
				$this->info('You do not have permission to do that.');
			}
		}
	}
}
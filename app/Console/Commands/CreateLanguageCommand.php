<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\WordLanguage;

class CreateLanguageCommand extends Command {

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
	protected $name = 'create_language';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates a new language from the given params.';

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'Full capitalized name og the language.'],
			['short', InputArgument::REQUIRED, 'Language code in 2 chars.'],
		];
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Creating new language...');

		WordLanguage::create([
			'name' => $this->argument('name'),
			'short_name' => $this->argument('short'),
			'image' => '/img/flags/'.$this->argument('short').'.png'
		]);

		$this->info('Language "'.$this->argument('name').'" was created.');
	}
}
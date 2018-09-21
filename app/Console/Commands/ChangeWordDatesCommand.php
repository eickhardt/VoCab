<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use DB;
use Illuminate\Support\Facades\Storage;

use App\Word;
use App\Meaning;

class ChangeWordDatesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'changeworddates';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'For use once to set the dates of the French and English words way back.';

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
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->info('Starting date change command.');

		$affected_rows = Word::where('language_id', 1)->update(array('created_at' => '1970-01-01 00:00:01'));
		$affected_rows = Word::where('language_id', 2)->update(array('created_at' => '1970-01-01 00:00:01'));

		$this->info('Dates have been changed to 1970-01-01 00:00:01');
	}
}
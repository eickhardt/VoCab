<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use DB;
use Illuminate\Support\Facades\Storage;

use App\Word;
use App\Meaning;

class SavenewmwCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'savenewmw';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Saves Meaning/Word data to json.';

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
	public function fire()
	{
		$this->info('Starting save new MW command.');

		$words = Word::withTrashed()->get();
		$words_count = $words->count();

		Storage::put('words.json', $words->toJson());
		$this->info('Words saved. Count: '.$words_count);

		$meanings = Meaning::withTrashed()->get();
		$meanings_count = $meanings->count();
		Storage::put('meanings.json', $meanings->toJson());
		$this->info('Meanings saved. Count: '.$meanings_count);
	}
}
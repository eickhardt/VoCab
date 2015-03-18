<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Word;
use App\Wotd;
use App\Meaning;

use DB;

class WotdCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'setwordofday';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Select a random word and set it as the word of the day.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// Pick a random word from the meanings table
		$meaning = Meaning::orderBy(DB::raw("RAND()"))->first();

		// Add it as a word of the day
		Wotd::create(['date' => date('Y-m-d'), 'meaning_id' => $meaning->id]);

		$this->info('Word of the day has been set.');
	}

}

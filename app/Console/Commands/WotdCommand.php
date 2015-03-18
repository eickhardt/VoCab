<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\WordN;
use App\WordWotd;
use App\WordMeaning;

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
		// Pick a random word from the words_all table
		$word = WordMeaning::orderByRaw("RAND()")->take(1)->first();

		// Add it as a word of the day
		WordWotd::create(['date' => date('Y-m-d'), 'word_meaning_id' => $word->id]);

		$this->info('Word of the day has been set.');
	}

}

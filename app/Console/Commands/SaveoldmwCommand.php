<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use DB;
// use Storage;
use Illuminate\Support\Facades\Storage;

use App\WordN;
use App\WordMeaning;

class SaveoldmwCommand extends Command {


	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'saveoldmw';


	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Saves Meaning/Word data from old tables to json.';


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
		$this->info('Starting save old MW command.');

		$relations = DB::select("SELECT * FROM word_word_meaning");

		$words_count = 0;
		$c = new \Illuminate\Database\Eloquent\Collection;

		foreach ($relations as $relation) 
		{
			$words_count++;
			$word = WordN::find($relation->word_meaning_id);
			if ($word)
			{
				$word->meaning_id = $relation->word_id;
				$c->add($word);
			}
			else
			{
				$this->info('A word was not found: '.$relation->word_meaning_id);
			}
		}

		Storage::put('static/words.json', $c->toJson());
		$this->info('Words saved. Count: '.$words_count);

		$meanings = WordMeaning::all();
		$meanings_count = $meanings->count();
		Storage::put('static/meanings.json', $meanings->toJson());
		$this->info('Meanings saved. Count: '.$meanings_count);
	}

}

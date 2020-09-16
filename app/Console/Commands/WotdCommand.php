<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

use App\Wotd;
use App\Meaning;

use Artisan;
use DB;

class WotdCommand extends Command
{
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
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['user_id', InputArgument::REQUIRED, 'Id of the user to set the word of the day for.'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $user_id = $this->argument('user_id');

        // Pick a random meaning from the meanings table for this user
        $meaning = Meaning::where('user_id', $user_id)
            ->orderBy(DB::raw("RAND()"))
            ->first();

        // Add it as a word of the day
        Wotd::create([
            'date' => date('Y-m-d'),
            'meaning_id' => $meaning->id,
            'user_id' => $user_id
        ]);

        $this->info('Word of the day has been set.');
    }
}

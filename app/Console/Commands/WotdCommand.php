<?php namespace App\Console\Commands;

use App\User;
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
            [
                'user_id',
                InputArgument::OPTIONAL,
                'Id of the user to set the wotd for. If not provided, will set for all users.'
            ],
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

        if ($user_id != null) {
            $this->setWotdForUser(User::find($user_id));
        } else {
            $users = User::all();
            foreach ($users as $user) {
                $this->setWotdForUser($user);
            }
        }

        $this->info('Word of the day command completed successfully.');
    }

    /**
     * Set word of day for the user with the given id.
     *
     * @param User $user
     * @return void
     */
    private function setWotdForUser(User $user)
    {
        // Pick a random meaning from the meanings table for this user
        $meaning = Meaning::where('user_id', $user->id)
            ->orderBy(DB::raw("RAND()"))
            ->first();

        // Check if a wotd is already set for today for this user
        $existing_wotd = Wotd::where('user_id', $user->id)
            ->orderBy('date', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        if ($existing_wotd == null || !$existing_wotd->date->isToday()) {
            // Add it as a word of the day if it didnt already exist
            Wotd::create([
                'date' => date('Y-m-d'),
                'meaning_id' => $meaning->id,
                'user_id' => $user->id
            ]);
            $this->info('Word of the day has been set fur user ' . $user->name . '.');
        } else {
            $this->info('Word of the day was already set for user ' . $user->name . '.');
        }
    }
}

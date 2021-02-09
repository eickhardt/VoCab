<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class SetRootLanguageForAllUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setrootlanguage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets the root language to english for all users where it has not been set yet.';

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
        $updated_count = User::whereNull('root_language_id')->update(['root_language_id' => 1]);

        $this->info('Updated users where no root language wes set: ' . $updated_count);
    }
}

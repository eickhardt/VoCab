<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Inspire',
        'App\Console\Commands\BackupCommand',
        'App\Console\Commands\WotdCommand',
        'App\Console\Commands\SaveoldmwCommand',
        'App\Console\Commands\RestoreoldmwCommand',
        'App\Console\Commands\SavenewmwCommand',
        'App\Console\Commands\ChangeWordDatesCommand',
        'App\Console\Commands\CreateUserCommand',
        'App\Console\Commands\CreateLanguageCommand',
        'App\Console\Commands\SetRootLanguageForAllUsersCommand',
        'App\Console\Commands\WotdCommand',
        'App\Console\Commands\AddAdditionalLanguagesToProdCommand',
        'App\Console\Commands\HousekeepCsvExports',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('setwordofday')->daily()->at('00:00');
        $schedule->command('housekeep:csv-exports')->daily()->at('00:30');
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run', ['--only-db' => true])->daily()->at('02:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}

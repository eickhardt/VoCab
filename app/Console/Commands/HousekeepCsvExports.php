<?php

namespace App\Console\Commands;

use App\CsvExport;
use App\Port\CsvConstants;
use Carbon\Carbon;
use File;
use Illuminate\Console\Command;
use Log;

class HousekeepCsvExports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'housekeep:csv-exports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'House keeps CSV file exports in accordance with the configured lifetime';

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
        Log::info('Starting HousekeepCsvExports');

        $exports = CsvExport::where('file_exists', 1)
                            ->where(
                                'updated_at',
                                '>',
                                Carbon::now()
                                      ->subHours(config('app.hours_to_keep_csv_export_files'))
                                      ->toDateTimeString()
                            )->get();

        if (count($exports)) {
            foreach ($exports as $export) {
                File::delete(storage_path(CsvConstants::CSV_EXPORT_FOLDER . $export->file_name));

                $export->file_exists = false;
                $export->save();
            }
        }

        Log::info('Completed HousekeepCsvExports', ['count' => count($exports)]);
    }
}

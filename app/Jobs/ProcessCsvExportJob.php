<?php

namespace App\Jobs;

use App\CsvExport;
use App\Port\Export\CsvExporter;
use App\Port\CsvPortUtil;
use App\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use Storage;

class ProcessCsvExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User The user we are exporting for.
     */
    protected $user;

    /**
     * @var string Unique id of this export.
     */
    protected $export_id;

    /**
     * Create a new job instance.
     *
     * @param User $user The user we are exporting for.
     * @param string $export_id Unique id of this export.
     */
    public function __construct(User $user, $export_id)
    {
        $this->user      = $user;
        $this->export_id = $export_id;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info(self::class . ' is being handled', [
            'user_id'   => $this->user->id,
            'export_id' => $this->export_id
        ]);

        // Grab data from DB
        $data = $this->user->meanings()->withTrashed()->with('words')->get();

        // Generate the string content and save as CSV file to storage
        $csv_content = CsvExporter::export(
            $data,
            $this->user->languages
        );

        $file_name = CsvPortUtil::generateCsvExportFileName(
            $this->user->id,
            $this->export_id
        );
        $file_path = CsvPortUtil::getCsvExportFilePath($file_name);

        Storage::put($file_path, $csv_content);

        // Save export result in database for later reference
        $export = CsvExport::create([
                                        'user_id'   => $this->user->id,
                                        'file_name' => $file_name
                                    ]);

        Log::info(CsvExport::class . ' created', [
            'id'        => $export->id,
            'user_id'   => $this->user->id,
            'file_name' => $file_name
        ]);

        $this->finalize(true);
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     */
    public function failed(Exception $exception)
    {
        Log::error($exception);

        $this->finalize(false);
    }

    /**
     * Job is completed, send the user a status and allow them to use porting again.
     *
     * @param $success bool Whether or not the export was successful.
     */
    protected function finalize($success)
    {
        $this->user->unlockPorting();

        // Send status email to the user
        SendCsvExportStatusEmailJob::dispatch($this->user, $success, $this->export_id);
    }
}

<?php

namespace App\Jobs;

use App\Port\Export\Services\CsvExportDataProcessorService\ICsvExportDataProcessorService;
use App\Port\Export\Services\CsvExportService\ICsvExportService;
use App\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

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
    protected $request_fingerprint;

    /**
     * @var ICsvExportDataProcessorService Entity that will export the data to file.
     */
    protected $exporter;

    /**
     * Create a new job instance.
     *
     * @param User $user The user we are exporting data for.
     * @param string $request_fingerprint
     */
    public function __construct(User $user, string $request_fingerprint)
    {
        $this->user                = $user;
        $this->request_fingerprint = $request_fingerprint;
    }

    /**
     * Execute the job.
     *
     * @param ICsvExportService $exporter Entity that will export the data to file.
     */
    public function handle(ICsvExportService $exporter)
    {
        Log::info(self::class . ' is being handled', [
            'user_id'             => $this->user->id,
            'request_fingerprint' => $this->request_fingerprint
        ]);

        $exporter->export($this->user, $this->request_fingerprint);

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
     * @param $success bool Whether the export was successful.
     */
    protected function finalize(bool $success)
    {
        $this->user->unlockPorting();

        SendCsvExportStatusEmailJob::dispatch($this->user, $success, $this->request_fingerprint);
    }
}

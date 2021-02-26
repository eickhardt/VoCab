<?php

namespace App\Jobs;

use App\Exceptions\ImportException;
use App\Port\Import\CsvImporter;
use App\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class ProcessCsvImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User The user to import for.
     */
    protected $user;

    /**
     * @var string Unique id of this import.
     */
    protected $request_fingerprint;

    /**
     * @var CsvImporter The entity that will perform the import.
     */
    protected $csv_importer;

    /**
     * Create a new job instance.
     *
     * @param User $user The user we are exporting for.
     * @param CsvImporter $csv_importer The entity that will perform the import.
     * @param string $request_fingerprint Unique id of this import.
     */
    public function __construct(User $user, CsvImporter $csv_importer, string $request_fingerprint)
    {
        $this->user                = $user;
        $this->csv_importer        = $csv_importer;
        $this->request_fingerprint = $request_fingerprint;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info(self::class . ' is being handled', [
            'user_id'             => $this->user->id,
            'request_fingerprint' => $this->request_fingerprint
        ]);

        try {
            $message = $this->csv_importer->import();

            $this->finalize(true, $message);

        } catch (ImportException $e) {

            $this->finalize(false, $e->getMessage());

        } catch (Exception $e) {

            $this->logError($e);
            $this->finalize(false);
        }
    }

    /**
     * The job failed to process.
     *
     * @param Exception $e
     */
    public function failed(Exception $e)
    {
        $this->logError($e);

        $this->finalize(false);
    }

    /**
     * Job is completed, send the user a status and allow them to use porting again.
     *
     * @param bool $success Whether or not the export was successful.
     * @param string $message Message for the user.
     */
    protected function finalize(bool $success, $message = '')
    {
        $this->user->unlockPorting();

        $this->csv_importer->deleteLocalCsvFile();

        // Send status email to the user
        SendCsvImportStatusEmailJob::dispatch($this->user, $success, $message, $this->request_fingerprint);

        Log::info(self::class . ' finalized', [
            'user_id'   => $this->user->id,
            'import_id' => $this->request_fingerprint,
            'success'   => $success,
            'message'   => $message
        ]);
    }

    /**
     * @param Exception $e Error to log.
     */
    protected function logError(Exception $e)
    {
        Log::error($e, ['import_id' => $this->request_fingerprint]);
    }
}

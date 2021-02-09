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
    protected $import_id;

    /**
     * @var CsvImporter The entity that will perform the import.
     */
    protected $csv_importer;

    /**
     * Create a new job instance.
     *
     * @param User $user The user we are exporting for.
     * @param CsvImporter $csv_importer The entity that will perform the import.
     * @param string $import_id Unique id of this import.
     */
    public function __construct(User $user, CsvImporter $csv_importer, $import_id)
    {
        $this->user         = $user;
        $this->csv_importer = $csv_importer;
        $this->import_id    = $import_id;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info(self::class . ' is being handled', [
            'user_id'   => $this->user->id,
            'import_id' => $this->import_id
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
    protected function finalize($success, $message = '')
    {
        $this->user->unlockPorting();

        $this->csv_importer->deleteLocalCsvFile();

        // Send status email to the user
        SendCsvImportStatusEmailJob::dispatch($this->user, $success, $message, $this->import_id);

        Log::info(self::class . ' finalized', [
            'user_id'   => $this->user->id,
            'import_id' => $this->import_id,
            'success'   => $success,
            'message'   => $message
        ]);
    }

    /**
     * @param Exception $e Error to log.
     */
    protected function logError(Exception $e)
    {
        Log::error($e, ['import_id' => $this->import_id]);
    }
}

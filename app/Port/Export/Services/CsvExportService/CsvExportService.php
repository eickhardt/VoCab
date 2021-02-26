<?php


namespace App\Port\Export\Services\CsvExportService;


use App\CsvExport;
use App\Port\Export\CsvExportFileMaker;
use App\Port\Export\Services\CsvExportDataProcessorService\ICsvExportDataProcessorService;
use App\User;
use Log;

class CsvExportService implements ICsvExportService
{
    /**
     * @var ICsvExportDataProcessorService Entity that will process DB data to CSV.
     */
    protected $csv_data_processor;

    /**
     * @param ICsvExportDataProcessorService $csv_data_processor Entity that will process DB data to CSV.
     */
    public function __construct(ICsvExportDataProcessorService $csv_data_processor)
    {
        $this->csv_data_processor = $csv_data_processor;
    }

    /**
     * Exports the given users data to a CSV file.
     *
     * @param User $user
     * @param string $request_fingerprint
     */
    public function export(User $user, string $request_fingerprint)
    {
        $processed_data = $this->csv_data_processor->process($user->getCsvExportData());

        $file_name = CsvExportFileMaker::make($processed_data, $request_fingerprint);

        $export = CsvExport::create([
                                        'user_id'   => $user->id,
                                        'file_name' => $file_name
                                    ]);

        Log::info(CsvExport::class . ' created', [
            'request_fingerprint' => $request_fingerprint,
            'user_id'             => $user->id,
            'export_id'           => $export->id,
            'file_name'           => $file_name
        ]);
    }
}
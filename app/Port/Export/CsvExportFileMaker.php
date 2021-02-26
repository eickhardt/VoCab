<?php


namespace App\Port\Export;


use App\Dtos\CsvExportResultDTO;
use App\Port\CsvConstants;
use App\Port\CsvPortUtil;

class CsvExportFileMaker
{
    /**
     * Create a CSV file containing the given export data.
     *
     * @param CsvExportResultDTO $csv_data Data generated from export data service.
     * @param string $request_fingerprint
     * @return string Name of the generated file.
     */
    public static function make(CsvExportResultDTO $csv_data, string $request_fingerprint): string
    {
        $file_name = CsvPortUtil::generateCsvExportFileName($request_fingerprint);
        $file_path = CsvPortUtil::getCsvExportFilePath($file_name);

        $file_handle = fopen($file_path, 'w');
        fputcsv($file_handle, $csv_data->getColumns(), CsvConstants::CSV_COLUMN_DELIMITER);

        foreach ($csv_data->getRows() as $row) {
            fputcsv($file_handle, $row, CsvConstants::CSV_COLUMN_DELIMITER);
        }

        fclose($file_handle);

        return $file_name;
    }
}
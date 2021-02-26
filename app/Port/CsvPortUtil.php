<?php


namespace App\Port;


use Carbon\Carbon;

class CsvPortUtil
{
    /**
     * Extract the field names from a CSV header line as string array.
     *
     * @param $csv_header_line string Header line from a CSV file import.
     * @return string[] List of string names of the fields.
     */
    public static function getCsvLineValues(string $csv_header_line): array
    {
        return self::removeEolsFromValues(explode(CsvConstants::CSV_COLUMN_DELIMITER, $csv_header_line));
    }

    /**
     * Generate unique file name for export file.
     *
     * @param string $request_fingerprint Unique id of the export.
     * @return string
     */
    public static function generateCsvExportFileName(string $request_fingerprint): string
    {
        return 'export_' . $request_fingerprint . '_' . time() . '.csv';
    }

    /**
     * Get the path for a generated CSV export file.
     *
     * @param string $file_name Name of the file to get path for.
     * @return string
     */
    public static function getCsvExportFilePath(string $file_name): string
    {
        return storage_path('app/' . CsvConstants::CSV_EXPORT_FOLDER . $file_name);
    }

    /**
     * Create a name for a served CSV export file. This should be the filename the user sees when downloading.
     *
     * @param Carbon $export_time The timestamp of the export.
     * @return string
     */
    public static function generateCsvExportDownloadFileName(Carbon $export_time): string
    {
        return strtolower(config('app.name'))
               . '-export-'
               . self::carbonToSimpleTimestampString($export_time)
               . '.csv';
    }

    /**
     * Generate unique file name for import file.
     *
     * @param string $request_fingerprint Unique id of the import.
     * @return string
     */
    public static function generateCsvImportFileName(string $request_fingerprint): string
    {
        return 'import_' . $request_fingerprint . '_' . time() . '.csv';
    }

    /**
     * Get the path where we want to save an uploaded CSV import file.
     *
     * @param string $file_name Name of the file to get path for.
     * @return string
     */
    public static function getCsvImportFilePath(string $file_name): string
    {
        return storage_path('app/' . CsvConstants::CSV_IMPORT_FOLDER . $file_name);
    }

    /**
     * Get a simple string from a Carbon instance. 2012-09-05 23:26:11 -> 20120905-232611
     *
     * @param Carbon $time
     * @return string
     */
    public static function carbonToSimpleTimestampString(Carbon $time): string
    {
        $time = $time->toDateTimeString();
        $time = str_replace(':', '-', $time);
        $time = str_replace('-', '', $time);

        return str_replace(' ', '-', $time);
    }

    /**
     * Remove EOLs from field names. They occur at the last field, since it contains the string name and the EOL.
     *
     * @param string[] $header_fields Array of header fields extracted from the CSV header line.
     * @return string[] Header fields without EOLs.
     */
    protected static function removeEolsFromValues(array $header_fields): array
    {
        $cleaned_fields = [];
        foreach ($header_fields as $field) {
            $cleaned_fields[] = str_replace(CsvConstants::CSV_END_OF_LINE, '', $field);
        }
        return $cleaned_fields;
    }
}
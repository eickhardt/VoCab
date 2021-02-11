<?php


namespace App\Port;


class CsvPortUtil
{
    /**
     * Extract the field names from a CSV header line as string array.
     *
     * @param $csv_header_line string Header line from a CSV file import.
     * @return string[] List of string names of the fields.
     */
    public static function getCsvLineValues($csv_header_line)
    {
        return self::removeEolsFromValues(explode(CsvConstants::CSV_COLUMN_DELIMITER, $csv_header_line));
    }

    /**
     * Check if the given string ends with the given string.
     *
     * @param string $column_name The string the search.
     * @return bool Whether or not the haystack ends with the needle.
     */
    public static function isValidWordColumnName($column_name)
    {
        foreach (CsvConstants::getAllWordCsvColumnNames() as $valid_column) {

            // Check if the column is prefixed with what could be a language shortname
            if (strlen($column_name) - strlen($valid_column) === CsvConstants::LANGUAGE_SHORTNAME_LENGTH + 1) {

                // Check if the column ends with a valid column name i.e. "en_text" -> "text"
                if (substr($column_name, -strlen($valid_column)) === $valid_column) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Generates a random string of 10 chars using alphanumerics.
     *
     * @return false|string
     */
    public static function getNextPortId()
    {
        return substr(str_shuffle(MD5(microtime())), 0, 10);
    }

    /**
     * Generate unique file name for export file.
     *
     * @param int $user_id Id of the user that we are exporting for.
     * @param string $export_id Unique id of the export.
     * @return string
     */
    public static function generateCsvExportFileName($user_id, $export_id)
    {
        return 'export_' . $user_id . '_' . $export_id . '_' . time() . '.csv';
    }

    /**
     * Get the path for a generated CSV export file.
     *
     * @param string $file_name Name of the file to get path for.
     * @return string
     */
    public static function getCsvExportFilePath($file_name)
    {
        return CsvConstants::CSV_EXPORT_FOLDER . $file_name;
    }

    /**
     * Create a name for a served CSV export file.
     *
     * @return string
     */
    public static function getCsvExportDownloadFileName()
    {
        return strtolower(config('app.name')) . '-export.csv';
    }

    /**
     * Generate unique file name for import file.
     *
     * @param int $user_id Id of the user that we are importing for.
     * @param string $import_id Unique id of the import.
     * @return string
     */
    public static function generateCsvImportFileName($user_id, $import_id)
    {
        return 'import_' . $user_id . '_' . $import_id . '_' . time() . '.csv';
    }

    /**
     * Get the path where we want to save an uploaded CSV import file.
     *
     * @param string $file_name Name of the file to get path for.
     * @return string
     */
    public static function getCsvImportFilePath($file_name)
    {
        return CsvConstants::CSV_IMPORT_FOLDER . $file_name;
    }

    /**
     * Remove EOLs from field names. They occur at the last field, since it contains the string name and the EOL.
     *
     * @param string[] $header_fields Array of header fields extracted from the CSV header line.
     * @return string[] Header fields without EOLs.
     */
    protected static function removeEolsFromValues(array $header_fields)
    {
        $cleaned_fields = [];
        foreach ($header_fields as $field) {
            $cleaned_fields[] = str_replace(CsvConstants::CSV_END_OF_LINE, '', $field);
        }
        return $cleaned_fields;
    }
}